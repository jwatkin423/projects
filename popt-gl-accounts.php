<?php

// primary AT field for this script is 'extRefID' that is mapped from EAS.VW_JOBS.JOB_ID

$DO_NOT_DELETE = $DO_NOT_UPDATE = [];


// MB (EAS) products
$q = "SELECT 
	T1.NAME,
	T1.ACCOUNT_ID,
	T1.INT_ACCT_TYPE_ID,
	T1.CP_ONLY_FLAG,
	T1.PREVENT_CP_POSTING,
	T1.PL_ID3
FROM
(SELECT 
	NAME,
	ACCOUNT_ID,
	INT_ACCT_TYPE_ID,
	CP_ONLY_FLAG,
	PREVENT_CP_POSTING,
	PL_ID3
FROM
	<table name removed>
WHERE 
	PL_ID3 = 1016 
	OR PL_ID3 IS NULL
) T1
WHERE
	T1.CP_ONLY_FLAG <> 1
	AND T1.PREVENT_CP_POSTING <> 1
	AND T1.INT_ACCT_TYPE_ID = 1003
";
if ($at->limit) {
	$q .= "WHERE ROWNUM <= $at->limit 
	";
}
$q .= "ORDER BY 1";

// echo $q; exit;
$EAS->executeQuery($q, RESULTS_ASSOC_ARRAY);
$mbGLAccounts = $EAS->getResults();






$fields = [ // must be the same as the $data array in the UPDATE block below
	'displayOrder',
	'extRefID',
	'parameterID',
	'label',
	'value',
];


// dependency: AT popt product codes
//if ( ! isset($atPoptFunctionCodes)) {
$obj = 'popt';

if (isset($_GET['production'])){
	$parameterID = '<table name removed>';
} else {
	$parameterID = '<table name removed>';
}


$atPoptFunctionCodes = $at->search($obj, [
	'parameterID' => $parameterID,
	'fields'      => implode(',', $fields),
], 0);
$atPoptFunctionCodesKeys = AtTask::pivot($atPoptFunctionCodes, 'extRefID');
//}


// UPDATE block
if (isset($_GET['update'])) {
	$at->log(count($mbGLAccounts) . ' MB GL Account codes to UPDATE');

	$s = $f = $p = $displayOrder = 0;
	foreach ($mbGLAccounts as $v) {
		$primary = $v['ACCOUNT_ID'];

		// flush to output
		flush();
		ob_flush();

		echo $v['NAME'] . ' ==> ';

		
		$label = $v['NAME'];

		$data = [
			'extRefID'     => $primary,
			'parameterID'  => $parameterID,
			'label'        => $label,
			'value'        => $label,
			'displayOrder' => ++$displayOrder,
		];
		if (isset($_GET['list'])) {
			echo var_export($data, 1) . PHP_EOL;
		}





		// check current object status
		$where = null;
		$old_data = [];
		if ($old = @$atPoptFunctionCodesKeys[$primary]) {
			foreach ($fields as $key) {
				$old_data[$key] = AtTask::bool($old->$key);
			}
			$where = $old->ID;
		}


		if (in_array($primary, $DO_NOT_UPDATE)) { // skip reserved
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






// no DELETE block




/* End of file */