<?PHP

$DO_NOT_UPDATE = $DO_NOT_DELETE = [];

$RANGES = [];
$dateFormat = 'Y/m/d'; // format

if (isset($_GET['range1'])){
	$d = new DateTime($_GET['range1']);
} else {
	$d = new DateTime('2014-01-01');
}
if (isset($_GET['range2'])){
	$now = new DateTime($_GET['range2']);
} else {
	$now = new DateTime;
}
if (isset($_GET['rangeinterval'])){
	$i = new DateInterval($_GET['rangeinterval']);
} else {
	$i = new DateInterval('P1W');
} 

while ($d < $now) {
	$RANGES[] = [
		$d->format($dateFormat),
		$d->add($i)->format($dateFormat),
	];
}

$obj = 'exptyp';
$atExpnsTyps = $at->search($obj, '', 0);
$atExpnsTypKeys = AtTask::pivot($atExpnsTyps, 'extRefID');
if (isset($_GET['verbose'])) {
	echo 'AT dependency: ' . count($atExpnsTyps) . ' expns, ' .
		count($atExpnsTypKeys) . ' extRefID-pivoted' . PHP_EOL;
}

if (isset($_GET['update'])){
	$obj = 'project';
	$atProject = $at->search($obj, '', 0);
	$atProjectKeys = AtTask::pivot($atProject, 'extRefID');
	if ( isset( $_GET['verbose'] ) ) {
		echo 'AT dependency: ' . count($atProject) . ' projects, ' . 
			count($atProjectKeys) . ' extRefID-pivoted' . PHP_EOL;
	}
	
	$parameterID = '54062f2b00a6be1fe351c98c10402d28';
	$atPoptFunctionCodes = $at->search('popt', [
		'fields'      => 'extRefID,value,label',
		'parameterID' => $parameterID, // product code
		], 0);
	$atPoptFunctionCodeKeys = AtTask::pivot($atPoptFunctionCodes, 'extRefID');
	if (isset($_GET['verbose'])) {
	echo 'AT dependency: ' . count($atPoptFunctionCodes) . ' popt function codes, ' . 
		count($atPoptFunctionCodeKeys) . ' extRefID-pivoted' . PHP_EOL;
	}
}

$s = $f = $p = 0;
foreach ($RANGES as $RANGE) {
	$actualTally = 1;
	echo 'RANGE1: ' . $RANGE[0] . ' and RANGE2: ' . $RANGE[1] . PHP_EOL;
	$fields = [ // must be the same as the $data array in the UPDATE block below
		'actualAmount',
		'categoryID',
		'DE:Billing Type',
		'DE:Cost Category',
		'DE:Function Code',
		'description',
		'expenseTypeID',
		'extRefID',
		'isBillable',
		'projectID',
		'topObjCode',
		'topObjID',
	];
	$obj = 'expns';
	$atExpns = $at->search($obj, [
			'fields' => implode(',', $fields),
			'plannedDate'       => AtTask::date2($RANGE[0]),
			'plannedDate_Range' => AtTask::date2($RANGE[1]),
			'plannedDate_Mod'   => 'between',
		], 0);
	$atExpnsKeys = AtTask::pivot($atExpns, 'extRefID');

	
	
	$q = "SELECT
		EAC.JOB_COST_AMOUNT,
		EAC.BUS_UNIT_ID,
		EAC.FUNCTION_NAME,
		EAC.FUNC_GRP_GROUP_ID,
		EAC.FUNCTION_ID,
		et.FUNCTION_ID,	
		f.FUNC_NB_FLAG,
		et.ESTIMATE_CREATE_DATE,
		f.FUNC_BILLCODE_FLAG,
		f.DEFAULT_COST_CAT_ID,
		f.DEFAULT_COST_CAT_CODE,
		EAC.JOB_ID,
		et.JOB_ID
	FROM <table name removed> et 
	LEFT JOIN <table name removed> f ON et.FUNCTION_ID = f.FUNCTION_ID
	LEFT JOIN <table name removed> EAC on EAC.FUNCTION_ID = f.FUNCTION_ID 
	WHERE
		FINAL_EST_FLAG = 1
		AND EAC.JOB_ID = et.JOB_ID
		AND EAC.BUS_UNIT_ID = 1000
		AND et.AMOUNT IS NOT NULL
		AND EAC.JOB_COST_AMOUNT > 0
		AND F.FUNCTION_ID <> 1079
		AND et.FUNCTION_GROUP_ID NOT IN(1007,1081,1141,1142,1143,1144,1145,
										 1146,1151,1161,1201,1202,1203,1204,
										 1205,1206,1207,1208,1209,1291,1292,
										 1301,1302,1303,1311,1321,1322,1323)
		AND et.ESTIMATE_CREATE_DATE 
		BETWEEN TO_DATE('$RANGE[0]', 'yyyy/mm/dd')
		AND TO_DATE('$RANGE[1]', 'yyyy/mm/dd')
		";

		$EAS->executeQuery($q, RESULTS_ASSOC_ARRAY);
		$mbEstimateExpneses = $EAS->getResults();
		$at->log(count($mbEstimateExpneses) . ' MB (EAS) Estimated Expenses to UPDATE');
		$mbEE = count($mbEstimateExpneses);
		foreach ($mbEstimateExpneses as $v) {
			$primary = $v['JOB_ID'] . '-' . $v['FUNCTION_ID'];

						// flush to output
			flush();
			ob_flush();
			$number = ' Number ' . $actualTally . ' out of: ' . $mbEE . ' ';
			$actualTally++;
			echo $number . ' ==> ' . $primary . ' ==> ';

			switch ($v['FUNC_NB_FLAG']) {
				case 0:
					$DEBillingType = 'Billable';
					break;
				
				case 1:
					$DEBillingType = 'Non-Billable - Client';
					break;

				case 2:
					$DEBillingType = 'Non-Billable - Internal';
					break;

				default:
					$DEBillingType = '';
					break;
			}

			switch ($v['DEFAULT_COST_CAT_ID']) {
				case 1:
					$DECostCategory = 'Vendor';
					break;
				
				case 2:
					$DECostCategory = 'Hours';
					break;

				case 3:
					$DECostCategory = 'Expenses';
					break;

				case 4:
					$DECostCategory = 'Income';
					break;

				case 7:
					$DECostCategory = 'Agency Sales Tax';
					break;

				case 8:
					$DECostCategory = 'Fee Billing';
					break;

				case 9:
					$DECostCategory = 'Prebilling';
					break;

				default:
					echo 'No matching cost category: ' . $v['DEFAULT_COST_CAT_ID'];
					$DECostCategory = '';
					break;
			}
						

			if ($funcGrpGroupID = @$atExpnsTypKeys[$v['FUNC_GRP_GROUP_ID']]->ID){
				$expenseTypeID = $funcGrpGroupID; 
			} else {
				echo 'No expense type';
			}

			if (strtoupper($v['FUNC_BILLCODE_FLAG']) == 'Y') {
				$isBillable = 'true';
			} else {
				$isBillable = 'false';
			}

			$jobID = $v['JOB_ID'];
			
			if ($projectID = @$atProjectKeys[$jobID]->ID){
				$topObjID = $projectID;
			} else {
				$topObjID = '';
				$projectID = '';
			}

			$description = $v['DEFAULT_COST_CAT_CODE'] . ' - ' . $v['FUNCTION_NAME'];

			if ($DEFunctionCode = @$atPoptFunctionCodeKeys[$v['FUNCTION_ID']]->label){

			} else {
				echo 'Function code: ' . $v['FUNCTION_ID'] . ' not found: setting DE:Function Code to Null' . PHP_EOL;
				$DEFunctionCode = '';
			}
							
			$data = [
				'actualAmount'      => $v['JOB_COST_AMOUNT'],
				'categoryID'        => '<table name removed>',
				'DE:Billing Type'   => $DEBillingType,
				'DE:Cost Category'  => $DECostCategory,
				'DE:Function Code'  => $DEFunctionCode,
				'description'       => $description,
				'expenseTypeID'     => $expenseTypeID,
				'extRefID'          => $primary,
				'isBillable'        => $isBillable,
				'projectID'         => $projectID,
				'topObjCode'        => 'PROJ',
				'topObjID'          => $topObjID,
			];
			if (isset($_GET['list'])) {
				echo var_export($data, 1) . PHP_EOL;
			}

			$where = null;
			$old_data = [];
			// $extRefID = $at_role_keys['extRefID'];
			if ($old = @$atExpnsKeys[$primary]) {
				foreach ($fields as $key) {
					$old_data[$key] = AtTask::bool($old->$key);
				}
				$where = $old->ID;
			}

			if (in_array($primary, $DO_NOT_UPDATE)) {
				echo 'SKIP (reserved)';
				$p++;
			} elseif ($data == $old_data) {
				echo 'SKIP (nothing to update)';
				$p++;
			} elseif (isset($_GET['sim']) || $at->post($obj, $data, $where)) {
				echo 'SUCCESS' . (isset($_GET['sim']) ? ' (sim)' : '');
				$s++;

				if (isset($_GET['verbose'])) {
					if ($old) { // updating
						echo ' UPDATE ' . $old->ID;
						foreach ($fields as $key) {
							if ($old_data[$key] != $data[$key]) {
								echo PHP_EOL . '  ' . $key . ': "' . $old_data[$key] . '" ==> "' . $data[$key] . '"';
							}
						}
					} else {
						echo ' CREATE:';
						foreach ($fields as $key) {
							echo PHP_EOL . '  ' . $key . ': "' . $data[$key] . '"';
						}
					}
				}
			} else {
				echo 'FAIL';
				$f++;

				if ($err = $at->getLastError()) {
					echo ": $err->message";
					if (isset($err->code)) {
						echo " (code: $err->code)";
					}
				}
			}
		echo PHP_EOL;
		} // End of loop
		$at->log("Total $s success, $f fail, $p skipped");
		$at->log('Time elapsed: ' . $at->time($time));
		$at->log('==========================');
		$at->log(PHP_EOL);
	
	echo PHP_EOL;
} //end of date range foreach loop




/* End of File */
