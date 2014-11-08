<?php

// primary AT field for this script is `extRefID`
ini_set('error_log', '/var/log/attask/revenues/revenues-' . date('Y_m_d') . 'error.log');

$DO_NOT_DELETE = $DO_NOT_UPDATE = [];

// MB revenue_forms and revenue_department_splits
$q = "SELECT
	rf.id AS rfID,
	rs.department_split_id AS deptSplitID,
	rf.actual_start_date AS startDate,
	rf.actual_end_date AS endDate,
	rf.billing_amount AS billingAmount,
	rf.status_id AS statusID,
	rf.est_amount AS estAmount,
	rf.eas_job_id AS easJobID,
	rf.po_number AS poNumber,
	rf.revenue_origin_id AS roi,
	ROUND(SUM(rs.cost), 2) AS total_billing,
	ROUND(SUM(rs.amended_cost), 2) AS amendCost,
	rf.use_amended AS useAmended,
	rs.is_selected AS isSelected,
	rs.department_type_id
FROM
	<table name removed> rf
	LEFT JOIN <table name removed> rs ON rf.id = rs.revenue_form_id
WHERE
	rf.status_id IS NOT NULL
	AND rf.status_id IN(4, 5)
	AND rs.is_selected = 1
GROUP BY
	easJobID,
	deptSplitID,
	rfID
";
if ($at->limit) {
	$q .= "LIMIT $at->limit";
}
$dbObj->executeQuery($q, RESULTS_ASSOC_ARRAY);
$mbRevenues = $dbObj->getResults();


// 'DE:Actual Start Date',
// 'DE:Actual End Date',
// 'DE:Revenue Form Status',
$fields = [ // must be the same as the $data array in the UPDATE block below
	'extRefID',
	'DE:Revenue Form ID',
	'DE:Signed Estimate Amount', // total of all fields below
	'DE:DIG - Account Management',
	'DE:DIG - Analytics',
	'DE:DIG - Branded Entertainment',
	'DE:DIG - Comm Planning',
	'DE:DIG - Creative',
	'DE:DIG - Development',
	'DE:DIG - Digital Production',
	'DE:DIG - Labs',
	'DE:DIG - Mobile',
	'DE:DIG - Strategy',
	'DE:DIG - User Experience',
	'DE:DIG - Account Management - CHI',
	'DE:DIG - Creative - CHI',
	'DE:DIG - Development - CHI',
	'DE:DIG - Digital Production - CHI',
	'DE:DIG - Strategy - CHI',
	'DE:DIR - Account Management',
	'DE:DIR - Creative',
	'DE:TOTAL REVENUE - STUDIO',
	'DE:TRAD - Account Management',
	'DE:TRAD - Art Production',
	'DE:TRAD - Broadcast Production',
	'DE:TRAD - Creative',
	'DE:TRAD - Design',
	'DE:Executive Mgmt',
	'DE:Finance',
	'DE:International',
	'DE:TRAD - Media',
	'DE:TRAD - Print Production',
	'DE:TRAD - Strategy',
	'DE:TRAD - Account Management - CHI',
	'DE:TRAD - Art Production - CHI',
	'DE:TRAD - Broadcast Production - CHI',
	'DE:TRAD - Creative - CHI',
	'DE:TRAD - Design - CHI',
	'DE:TRAD - Print Prod - CHI',
	'DE:TRAD - Strategy - CHI',
	'DE:DIG - Analytics - CHI',
	'DE:Executive Mgmt - CHI',
	'DE:Operations - Digital',
	'DE:TOTAL REVENUE - STUDIO - CHI',
	'DE:Administrative',
	'DE:DIG - Media',
];

$obj = 'project';
$list = $at->search($obj, [
	'fields' => implode(',', $fields),
], 0);
$at_projects_keys = AtTask::pivot($list, 'extRefID');
if (isset($_GET['verbose'])) {
	echo 'AT dependency: ' . count($list) . ' projects, ' .
		count($at_projects_keys) . ' extRefID-pivoted' . PHP_EOL;
}

// UPDATE block
if (isset($_GET['update'])) {

	// first: group up all mb records by easJobID field to conform with AT
	$mbRevenuesGrouped = [];
	foreach ($mbRevenues as $v) {

		// flush to output
		flush();
		ob_flush();

		$id = $v['easJobID']; // primary
		
		// create/reset all fields at first
		if ( ! isset($mbRevenuesGrouped[$id])) {
			$mbRevenuesGrouped[$id] = [];
			foreach ($fields as $fd) {
				$mbRevenuesGrouped[$id][$fd] = '';
			}
		}
		$mbRevenuesGrouped[$id]['extRefID'] = $id;

		if ($v['useAmended'] == 1) {
			$totalSplitIDBilled = $v['amendCost'];
		} else {
			$totalSplitIDBilled = $v['total_billing'];
		}
		//$totalSplitIDBilled = money_format($totalSplitIDBilled, 'en_US');
				// total of all costs
		$totalSplitIDBilled = $totalSplitIDBilled + 0;
		// echo $totalSplitIDBilled . PHP_EOL;
		$mbRevenuesGrouped[$id]['DE:Signed Estimate Amount'] += $totalSplitIDBilled;

		// collect all unique rfIDs
		$mbRevenuesGrouped[$id]['DE:Revenue Form ID'] .= ',' . $v['rfID'];

		$field = '';
		switch ($v['deptSplitID']) {
			case 1060:
			case 39:
				$field = 'DE:DIG - Account Management';
				break;

			case 1072:
			case 34:
				$field = 'DE:DIG - Analytics';
				break;

			case 1343:
			case 51:
				$field = 'DE:DIG - Branded Entertainment';
				break;

			case 1340:
			case 45:
				$field = 'DE:DIG - Comm Planning';
				break;

			case 1070:
			case 38:
				$field = 'DE:DIG - Creative';
				break;

			case 1074:
			case 35:
				$field = 'DE:DIG - Development';
				break;

			case 1073:
			case 36:
				$field = 'DE:DIG - Digital Production';
				break;

			case 1210:
			case 41:
				$field = 'DE:DIG - Labs';
				break;

			case 1341:
			case 44:
				$field = 'DE:DIG - Mobile';
				break;

			case 1120:
			case 40:
				$field = 'DE:DIG - Strategy';
				break;

			case 1220:
			case 50:
				$field = 'DE:DIG - User Experience';

				break;

			case 1320:
			case 95:
				$field = 'DE:DIG - Account Management - CHI';
				break;

			case 1321:
			case 96:
				$field = 'DE:DIG - Creative - CHI';
				break;

			case 1330:
			case 99:
				$field = 'DE:DIG - Development - CHI';

				break;

			case 1322:
			case 97:
				$field = 'DE:DIG - Digital Production - CHI';
				break;

			case 1323:
			case 98:
				$field = 'DE:DIG - Strategy - CHI';
				break;

			case 1050:
			case 27:
				$field = 'DE:DIR - Account Management';
				break;

			case 1051:
			case 28:
				$field = 'DE:DIR - Creative';
				break;

			case 1006:
			case 7:
				$field = 'DE:TOTAL REVENUE - STUDIO';
				break;

			case 1001:
			case 1:
				$field = 'DE:TRAD - Account Management';
				break;

			case 1351:
			case 48:
				$field = 'DE:TRAD - Art Production';
				break;

			case 1003:
			case 3:
				$field = 'DE:TRAD - Broadcast Production';
				break;

			case 1002:
			case 2:
				$field = 'DE:TRAD - Creative';
				break;

			case 1352:
			case 49:
				$field = 'DE:TRAD - Design';
				break;

			case 1000:
			case 17:
				$field = 'DE:Executive Mgmt';
				break;

			case 1021:
			case 21:
				$field = 'DE:Finance';
				break;

			case 1354:
			case 47:
				$field = 'DE:International';
				break;

			case 1080:
			case 29:
				$field = 'DE:TRAD - Media';
				break;

			case 1004:
			case 5:
				$field = 'DE:TRAD - Print Production';
				break;

			case 1005:
			case 6:
				$field = 'DE:TRAD - Strategy';
				break;

			case 1030:
			case 86:
				$field = 'DE:TRAD - Account Management - CHI';
				break;

			case 1350:
			case 100:
				$field = 'DE:TRAD - Art Production - CHI';
				break;

			case 1110:
			case 92:
				$field = 'DE:TRAD - Broadcast Production - CHI';
				break;

			case 1031:
			case 87:
				$field = 'DE:TRAD - Creative - CHI';
				break;

			case 1353:
			case 101:
				$field = 'DE:TRAD - Design - CHI';
				break;

			case 1090:
			case 101:
			case 90:
				$field = 'DE:TRAD - Print Prod - CHI';
				break;

			case 1100:
			case 91:
				$field = 'DE:TRAD - Strategy - CHI';
				break;

			case 1410:
			case 106:
				$field = 'DE:DIG - Analytics - CHI';
				break;

			case 1380:
			case 105:
				$field = 'DE:Executive Mgmt - CHI';
				break;

			case 1342:
			case 46:
				$field = 'DE:Operations - Digital';
				break;

			case 1260:
			case 94:
				$field = 'DE:TOTAL REVENUE - STUDIO - CHI';
				break;

			case 1007:
			case 19:
				$field = 'DE:Administrative';
				break;

			case 1071:
			case 33:
				$field = 'DE:DIG - Media';
				break;

			default:
				trigger_error('dept split ID: ' . $v['deptSplitID'] . ' not recognized');
			}
			// echo PHP_EOL . 'totalSplitIDBilled: ' . $totalSplitIDBilled . PHP_EOL;
		if ($field) {
			if ($mbRevenuesGrouped[$id][$field] != '') {
				$mbRevenuesGrouped[$id][$field] += $totalSplitIDBilled;
			} else {
				$mbRevenuesGrouped[$id][$field] = $totalSplitIDBilled;
			}

		} else {
			trigger_error('dept split ID: ' . $v['deptSplitID'] . ' not recognized');
		}
	}


	$at->log(count($mbRevenuesGrouped) . ' MB revenues to UPDATE');
	//AtTask::debugList($mbRevenues, ['id', 'username'] );
	$s = $f = $p = 0;
	
	// insert each grouped record into AT
	foreach ($mbRevenuesGrouped as $data) {

		$id = $data['extRefID']; // primary
		echo $id . ' ==> ';
		// clean up rfIDs
		$data['DE:Revenue Form ID'] = explode(',', $data['DE:Revenue Form ID']);
		$data['DE:Revenue Form ID'] = array_unique($data['DE:Revenue Form ID']); // get rid of duplicates
		$data['DE:Revenue Form ID'] = array_filter($data['DE:Revenue Form ID']); // get rid of empty
		$data['DE:Revenue Form ID'] = implode(',', $data['DE:Revenue Form ID']);


		// Converting to string to avoid irrational deciamls (bug 66188)
		foreach ($data as &$v) {
			$v = AtTask::bool($v);
		} unset($v);

		if (isset($_GET['list'])) {
			echo var_export($data, 1) . PHP_EOL;
		}

				$where = null;
				$old_data = [];
				if ($old = @$at_projects_keys[$id]) {
					foreach ($fields as $key) {
						if ( ! property_exists($old, $key)) {
							echo "Project with extRefID '$old->extRefID' does not have the '$key' field" . PHP_EOL;
							$p++;
							continue 2;
						}
						$old_data[$key] = $at->bool($old->$key);
					}
					$where = $old->ID;
				}


				if ($data == $old_data) {
					echo 'SKIP (nothing to update)';
					$p++;
				} elseif (isset($_GET['sim']) || $at->post($obj, $data, $where)) {
					echo 'SUCCESS' . (isset($_GET['sim']) ? ' (sim)' : '');
					$s++;
					if (isset($_GET['verbose'])) {
						if ($old) { // updating

							echo ' UPDATE ' . $old->ID;
							foreach ($fields as $key) {
								if ($old_data[$key] !== AtTask::bool($data[$key])) {
									echo PHP_EOL . '  ' . $key . ': "' . $old_data[$key] . '" ==> "' . $data[$key] . '"' . PHP_EOL;
								} 
							}
						} else { // creating
							echo ' CREATE:';
							foreach ($fields as $key) {
								echo PHP_EOL . '  ' . $key . ': "' . $data[$key] . '"' . PHP_EOL;
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
		}
	$at->log("Total $s success, $f fail, $p skipped");
}

		// No DELETE block

		/* End of file */
