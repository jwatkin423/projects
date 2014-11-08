<?php

// primary AT field for this script is `name`

$DO_NOT_DELETE = $DO_NOT_UPDATE = [
]; // by name


// MB titles
$q = "SELECT 
	e.eas_client_id AS easClientID, 
	e.title_card_id AS eTitleCardID, 
	t.title_card_id AS tTitleCardID, 
	t.title_salary_id AS titleSalaryID, 
	t.salary, 
	t.title_id, 
	e.multiplier, 
	e.hours_per_year, 
	e.is_active, 
	e.client_code, 
	t.eas_title_id, 
	titles.in_pricing
FROM eas_clients e 
	LEFT OUTER JOIN <table name removed> t ON e.title_card_id = t.title_card_id
	 INNER JOIN <table name removed> ON t.title_id = titles.id
WHERE in_pricing = 1
	AND t.salary IS NOT NULL
";
if ($at->limit) {
	$q .= "LIMIT $at->limit";
}
$dbObj->executeQuery($q, RESULTS_ASSOC_ARRAY);
$mbBillingRates = $dbObj->getResults();


// pivot
$obj = 'company';
$list = $at->search($obj, '', 0);
$atCompanyKeys = AtTask::pivot($list, 'extRefID');


$fields =[
	'extRefID',
	'companyID',
	'rateValue',
	'roleID',
];


$obj = 'rate';
$atRates = $at->search($obj, [
	'fields' => implode(',', $fields),
	'projectID_Mod' => 'isnull',
], 0);
$atRatesKeys = AtTask::pivot($atRates, 'extRefID');


$fields2 =[
	'extRefID',
	'description'
];

$obj2 = 'role';
$list = $at->search($obj2, [
	'fields' => implode(',', $fields2),
], 0);
$atRoles = AtTask::pivot($list, 'extRefID');
$atRolesDescrpt = AtTask::pivot($list, 'description');

if (isset($_GET['verbose'])) {
	echo 'AT dependency: ' . count($list) . ' companies, ' .
		count($atCompanyKeys) . ' extRefID-pivoted' . PHP_EOL;
	echo 'AT dependency: ' . count($atRates) . 'rates, '. PHP_EOL;
}

// UPDATE block
if (isset($_GET['update'])) {
	$at->log(count($mbBillingRates) . ' MB titles to UPDATE');
	$s = $f = $p = 0;
	$titleIDArray = [];
	foreach ($mbBillingRates as $v) {
	$easClientID = $v['easClientID']; // primary

		// flush to output
		flush();
		ob_flush();

		$rateValue = ROUND(($v['salary'] / $v['hours_per_year']) * $v['multiplier'], 2); //Rate Value
		$description = $v['title_id'];
		if ($extReFIDRoleID = @$atRolesDescrpt[$v['title_id']]->ID){

		} else {
			if (!in_array($v['title_id'], $titleIDArray)){
				array_push($titleIDArray, $v['title_id']);
				trigger_error($v['title_id'] ." Does not exist in description");
			}
			continue;
		}
		if ($companyID = @$atCompanyKeys[$easClientID]->ID) {
		}
		$extRefID = $v['titleSalaryID'] . "-" . $v['client_code'];



		echo str_pad($extRefID, 5) . ' ==> ';

		$data = [
			'extRefID'  => $extRefID,
			'companyID' => $companyID,
			'rateValue' => $rateValue,
			'roleID'    => $extReFIDRoleID,
		];
		if (isset($_GET['list'])) {
			echo var_export($data, 1) . PHP_EOL;
		}





		// check current object status
		$where = null;
		$old_data = [];
		// $extRefID = $at_role_keys['extRefID'];
		if ($old = @$atRatesKeys[$extRefID]) {
			foreach ($fields as $key) {
				$old_data[$key] = AtTask::bool($old->$key);
			}
			$where = $old->ID;
		}

		
		

		if (in_array($easClientID, $DO_NOT_UPDATE)) { // skip reserved
			echo 'SKIP (reserved)';
			$p++;
		} elseif ($data == $old_data) {
			echo 'SKIP (nothing to update)';
			$p++;
		} elseif (isset($_GET['sim'])
			|| $at->post($obj, $data, $where)
		) {
			echo 'SUCCESS' . (isset($_GET['sim']) ? ' (sim)' : '');
			$s++;

			if (isset($_GET['verbose'])) {
				if ($old) { // updating
					echo ' UPDATE:';
					foreach ($fields as $key) {
						if ($old_data[$key] != AtTask::bool($data[$key])) {
							echo PHP_EOL . '  ' . $key . ': "' . $old_data[$key] . '" ==> "' . $data[$key] . '"';
						}
					}
				} else { // creating
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

	 }
$at->log("Total $s success, $f fail, $p skipped");
}


/* End of file */
