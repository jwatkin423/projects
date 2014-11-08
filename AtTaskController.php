<?php

//require_once files have been removed for sanitation purposes



class AttaskController extends BaseController {

		

//Beginning of AtTask Class

		// config
		// 0 = Production
		// 1 = Sandbox set 1
		// 2 = Sandbox set 2

		protected $method;
		// 'GET'; // SELECT
		// 'PUT', // update by ID // UPDATE
		// 'POST', // create new // INSERT INTO
		// 'DELETE',

		protected $obj;
		// 'login',
		// 'user', // USER
		// 'project', // PROJ
		// 'company', // CMPY
		// 'task', // TASK
		// 'issue', // OPTASK
		// 'hour',

		protected $act;
		// '' // none (get by ID)
		// '/search',
		// '/count',
		// '/metadata',

		protected $error;
		protected $session = '';
		protected $session_max = '3600'; // renew session after an hour, just in case
		protected $session_exp = 0;
		protected $data = [];
		protected $first;
		protected $no_connection;
		protected $log = '';
		protected $API_max = 2000; // max records API can return (will paginate if there are more)
		protected $debug = 0;
		// 0 - none
		// 1 - notice
		// 2 - verbose
		// 3 - data and urls

		public $version = 'v4.0/'; // '' == 'v2.0/'
		public $limit = 0;

		public function __construct($server = 'sandbox') {
			if (Input::exists('production')){
				$server = 'production';
				$this->production = 'production';
			}

			$this->config = require_once ($_ENV['WORKDIR'] . "/inc/config/attask/$server.php");
			//DB portion of the construct
			$this->DB = new AdminObj(false);
			$this->helper = new Helper;
			$this->EAS = new EASObj(false);
			$this->constants = $this->DB->getConstants();
			// only enable error_reporting if NOT PRODUCTION
			if ($server == 'sandbox') {
				error_reporting(E_ALL);
			}
					//Batch Number
			if (Input::exists('batch')){
				$this->batch = Input::get('batch');
			} else {
				$this->batch = 'No Batch Number supplied.';
			}
			//Batched by date 
			//Batch Start Date 
			if ($r1 = Input::exists('range1')){
				$newDateStart = new DateTime($r1);
				$dateStart = $newDateStart->format('Y-m-d');
				$this->range1 = $dateStart;
			} else {
				$dateStart = new DateTime('2014-01-01');
				$this->range1 = $dateStart;
			}
			//Batch End Date
			if ($r2 = Input::exists('range2')){
				$newDateEnd = new DateTime($r2);
				$dateEnd = $newDateEnd->format('Y-md-d');
				$this->range2 = $dateEnd;
			} else {
				$now = new DateTime;
				$this->range2 = $now;
			}
			//Range interval
			if (Input::exists('rangeinterval')){
				$this->rangeInterval = Input::get('rangeinterval');
			} else {
				$this->rangeInterval = 'P1W';
			}
			
			if (Input::exists('update')){
				$this->update = 'update';
			} else {
				$this->update = 'notupdate';
			}

			if (Input::exists('delete')){
				$this->delete = 'delete';
			} else {
				$this->delete = 'donotdelete';
			} 

			if (Input::exists('verbose')) {
				$this->verbose = 'verbose';
			} else {
				$this->verbose = 'notverbose';
			} 

			if (Input::exists('sim')) {
				$this->sim = 'sim';
			} else {
				$this->sim = 'notsim';
			}

			

			if (Input::exists('list')){
				$this->list = 'list';
			} else {
				$this->list = 'nolist';
			}
			
			if (Input::exists('limit')) {
				$this->setLimit = 'True';
				if ($limit = Input::has('limit') != '5') {
				} else {
					$this->limit = 5;
				}
			} else {
				$this->limit = '';
			}
			$this->whereFrom = '2013/01/01';
			$this->whereCity = '1, 2';
		}

		public function dispDbInfo(){
			$dbObj = $this->DB;
			$constants = $this->constants;
			$dbObj->executeQuery("SELECT DATABASE() AS db", RESULTS_ASSOC_ARRAY);
			$r = $dbObj->getResults();
			$this->log("Current DB: " . $r[0]['db']);

			$c = $constants->getDataSet('EAS');
			preg_match('/\(\s*SERVICE_NAME\s*=\s*([\w\.]+)\s*\)/', $c['HOST'], $m);
			$this->log("EAS server: $m[1]");
		}

		protected function _login() {
			$this->_log(__FUNCTION__ . '()', 2);

			$r = $this->_fire('POST', 'login', '', [ // Login requests must use the POST method
				'username' => $this->config['user'],
				'password' => $this->config['pass'],
			]);

			if ($r) {
				$this->session = $r->data->sessionID;
				$this->_log('Session received: ' . $this->session, 2);
				$this->_log('Connected to ' . $this->getHost(), 2);
				$this->session_exp = time() + $this->session_max;
			} else {
				$this->session = false;
				$this->_log('Connection failed');
			}
			return $this->session;
		}
		//Single record fire and search
		public static function searchSingleRecord($obj, $data = ''){
			return self::fireSingleRecord('GET', $obj, '/search', $data);

		}
		
		public static function fireSingleRecord($method, $obj, $act, $data, $host){
			$url = 'https://' . $host . '/attask/api/v4.0/' . $obj .$act . '?' . http_build_query($data);

			$ch = curl_init($url);
			curl_setopt_array($ch, [
				CURLOPT_CUSTOMREQUEST  => $method,
				CURLOPT_RETURNTRANSFER => true,
			]);
			$r = curl_exec($ch);
			$error = false;

			// bad error
			if ( ! $r || curl_errno($ch)) {
				$error = true;
				//$this->_log('AtTask cURL error: ' . curl_error($ch));
			}

			curl_close($ch);

			$r = json_decode($r);
			return $r;
		}

		//multiple record search in AtTask

		protected function _fire($method, $obj, $act, $data) {
			$this->_log(__FUNCTION__ . '()', 2);

			$time = microtime(1); // profile this call

			if ($obj != 'login') { // any but login call
				if (time() > $this->session_exp) { // session expired
					$this->session = $this->_login();
				}
				if ($this->session) {
					$data['sessionID'] = $this->session;
				} else {
					$this->_log('connection failed2');
					return false;
				}
			}

			$this->_log('sending data :' . print_r($data, 1), 3);

			// curl
			$url = 'https://' . $this->config['host'] . '/attask/api/' . $this->version . $obj . $act . '?' . http_build_query($data);
			$this->_log($url, 3);

			$ch = curl_init($url);
			curl_setopt_array($ch, [
				CURLOPT_CUSTOMREQUEST  => $method,
				CURLOPT_RETURNTRANSFER => true,
			]);
			$r = curl_exec($ch);
			$error = false;

			// bad error
			if ( ! $r || curl_errno($ch)) {
				$error = true;
				$this->_log('AtTask cURL error: ' . curl_error($ch));
			}

			curl_close($ch);

			$r = json_decode($r);
			$this->_log('received data :' . print_r($r, 1), 3);

			// good error
			if ($this->error = @$r->error) { // save for later reference
				$error = true;
				$this->_log('AtTask API Error: ' . $r->error->message);
			}

			// profile
			$time = microtime(1) - $time;
			$time = round($time, 2);
			$this->_log("Time: $time sec", 2);

			return $error ? false : $r;
		}

		public function getHost() {
			$c = $this->config;
			return "$c[name] ($c[host])";
		}

		//searches a single record in attask
		// public static function searchSingleRecord($obj, $data = ''){
		// 	return self::fireSingleRecord('GET', $obj, '/search', $data);

		// }
		public function search($obj, $data = '', $limit_override = null) {
			$this->_log(__FUNCTION__ . '()', 2);
			
			// default data
			if ( ! $data) {
				$data = [
					'fields' => 'extRefID',
				];
			}

			$limit = is_null($limit_override) ? $this->limit : $limit_override;
			if ($limit) { // explicitly set limit wins
				$data['$$LIMIT'] = (int) $limit;
			} elseif (isset($data['$$LIMIT'])) {
				$limit = $data['$$LIMIT'];
			}

			// count total ($$LIMIT is ignored in this call)
			$total = $this->count($obj, $data);

			// lower total to limit
			if ($limit && $limit < $total) {
				$total = $limit;
			}

			// break up in steps
			$steps = [];
			$l = floor($total / $this->API_max);
			$m = $total % $this->API_max;
			for ($i = 0; $i <= $l; $i++) {
				$first = $i ? $i * $this->API_max : 0;
				$last = $i == $l ? $m : $this->API_max;
				$steps[] = [$first, $last];
			}

			$out = [];
			while ($steps) {
				list($data['$$FIRST'], $data['$$LIMIT']) = array_shift($steps);

				if ($r = $this->_fire('GET', $obj, '/search', $data)) {
					$out = array_merge($out, $r->data);
				}
			}

			// sort
			if ($out) {
				usort($out, function($a, $b) {
					if (isset($a->name)) {
						$key = 'name';
					} elseif (isset($a->label)) {
						$key = 'label';
					} else {
						$key = 'ID';
					}
					return strcmp(strtoupper($a->$key), strtoupper($b->$key));
				});
			}

			return $out;
		}

		/**
		 * convert AT's response to a hash array
		 * @param  array  $list AT_response->data
		 * @param  string  $key  element to use as key
		 * @param  string  $val  key to flatten the value to
		 * @return array        transposed array
		 */
		public static function pivot($list, $key = 'ID', $val = false) {
			$out = [];
			foreach ($list as $v) {
				$obj = gettype($v) == 'object';
				$id = $obj ? $v->$key : $v[$key];
				if ($val !== false) {
					$v = $obj ? $v->$val : $v[$val];
				}
				$out[$id] = $v;
			}
			return $out;
		}

		/**
		 * Find max and min length in the array
		 */
		public static function getMax($list, $key, $echo = false) {
			$max = $min = '';
			foreach ($list as $v) {
				$v = (object) $v;
				if ( ! isset($v->$key)) {
					$v->$key = '';
				}
				$l = strlen($v->$key);
				if ($l > strlen($max)) {
					$max = $v->$key;
				}
				if ( ! $min || $l < strlen($min)) {
					$min = $v->$key;
				}
			}
			if (isset($_GET['list']) && $echo) {
				echo "  Longest '$key' is " . strlen($max) . ' characters: "' . $max . '"' . PHP_EOL;
				echo "  Shortest '$key' is " . strlen($min) . ' characters: "' . $min . '"' . PHP_EOL;
			}
			return strlen($max);
		}

		public static function debugList($list, $fields) {
			if ( ! isset($_GET['list']) || ! $list) {
				return false;
			}

			$max = [];
			foreach ($fields as $f) {
				$max[$f] = self::getMax($list, $f);
			}

			foreach ($list as $v) {
				$v = (object) $v;

				$arr = [];
				foreach ($fields as $f) {
					$arr[] = str_pad($v->$f, $max[$f]);
				}
				echo implode(' ==> ', $arr) . PHP_EOL;
			}
		}

		protected function _log($msg, $lvl = 1) {
			if ($this->debug >= $lvl) {
				// trigger_error($msg);
			}
			return $this;
		}

		/**
		 * count AT objects
		 * @param  string $obj  object
		 * @param  array  $data data to pass
		 * @return number
		 */
		public function count($obj, $data = []) {
			$this->_log(__FUNCTION__ . '()', 2);

			$out = $this->_fire('GET', $obj, '/count', $data);
			if ($out) {
				$out = $out->data->count;
			}
			return $out;
		}

		public function post($obj, $data, $where = null) {
			$this->_log(__FUNCTION__ . '()', 2);

			// recursive
			if ( is_array($where) ) { // if $where is given as array, find object first, then redirect recursively
				$this->_log(' where given as array, looking for object first', 2);

				$where['$$LIMIT'] = 1;
				$old = $this->search($obj, $where);
				if ($old) {
					$this->_log('object found - UPDATE', 2);
					return $this->post($obj, $data, $old[0]->ID);
				} else {
					$this->_log('object not found - INSERT', 2);
					return $this->post($obj, $data);
				}
			}

			// straight
			if ($where) {
				$this->_log(' $where is a hash ID - UPDATE ', 2);
				$data['ID'] = $where;
				$method = 'PUT';
			} else {
				$this->_log(' $where is not provided, so its a clean INSERT ', 2);
				$method = 'POST';
			}
			return $this->_fire($method, $obj, '', $data);
		}

		public function del($obj, $id, $force = false) {
			$data = [
				'ID' => $id,
			];

			if ($force) {
				$data['force'] = 'true';
			}
			return $this->_fire('DELETE', $obj, '', $data);
		}

		/**
		 * Convert AT date
		 *
		 * AT uses weird standard that looks like ISO 8601
		 * but has extra 3 digits for miliseconds. Cutting them off.
		 * E.g.: 
		 * AT format = 2013-01-10T00:00:00:000-0500
		 * ISO 8601  = 2013-01-10T00:00:00-0500
		 *
		 * @param  (string)  $d date to be converted
		 *
		 * @return (string)  converted date
		 */
		public static function date($d) {
			if ( ! $d) {
				return '';
			}
			if (strlen($d) == 28) {
				$d = substr($d, 0, 19) . substr($d, -5);
			}
			return date('c', strtotime($d));
		}


		/**
		 * Convert AT date for hour.entryDate field
		 *
		 * AT uses this specific format
		 * for the entryDate field of the hour object
		 * E.g.: 
		 * 2014-01-01
		 *
		 * @param  (string)  $d date to be converted
		 *
		 * @return (string)  converted date
		 */
		public static function date2($d) {
			if ( ! $d) {
				return '';
			}
			$d = new DateTime($d);
			return $d->format('Y-m-d');
		}


		public static function department_type($s) {
			if (strpos($s, 'Digital') === 0) {
				$s = 'DIG';
			} elseif (strpos($s, 'Direct') === 0) {
				$s = 'DIR';
			} elseif (strpos($s, 'Studio') === 0) {
				$s = 'STU';
			} elseif (strpos($s, 'Traditional') === 0) {
				$s = 'TRAD';
			} else {
				$s = '';
			}

			if ($s) {
				$s .= ' - ';
			}

			return $s;
		}

		public static function titleAbbr($title, $dept, $office) {
			$title = str_replace('Assistant', 'Assist', $title);
			$title = str_replace('Associate', 'Assoc', $title);
			$title = str_replace('Communication', 'Comm', $title);
			$title = str_replace('Development', 'Dev', $title);
			$title = str_replace('Director', 'Dir', $title);
			$title = str_replace('Executive', 'Exec', $title);
			$title = str_replace('International', 'Intl', $title);
			$title = str_replace('Management', 'Mgmt', $title);
			$title = str_replace('Manager', 'Mgr', $title);
			$title = str_replace('User Experience', 'UX', $title);

			$title = self::department_type($dept) . $title; // add department type in the front

			if ($office && $office != 'NYC') {
				$title .= ' - ' . $office;
			}

			return $title;
		}

		public static function departmentAbbr($name, $department_type) {
			$name = preg_replace('/ - (Digital|Direct)/', '', $name); // remove " - Digital" or " - Direct"
			$name = self::department_type($department_type) . $name; // Traditional ==> TRAD, etc.
			return $name;
		}

		public function getLastError() {
			return $this->error;
		}

		public function setDebug($D = 0) {
			$this->debug = (int) $D;
			return $this;
		}

		public static function bool($a) {
			if (is_null($a)) {
				$a = '';
			} elseif (is_bool($a)) {
				$a = $a ? 'true' : 'false';
			} elseif (is_numeric($a)) {
				$a = (string) $a;
			} elseif (preg_match('/^\d\d\d\d-\d\d-\d\dT/', $a)) { // looks like a date
				$a = self::date($a);
			}
			return $a;
		}

		public function log($s, $echo = true) {
			$s .= PHP_EOL;
			$this->log .= $s;
			if ($echo) {
				echo $s;
			}
			return $this;
		}

		public function getLog() {
			return $this->log;
		}

		public function time($time) {
			$time = round(microtime(1) - $time);

			if ($time > 59) { // longer than a minute
				$time = floor($time / 60) . ' min ' . $time % 60;
			}

			return $time . ' sec';
		}



	/* End of file */
//End of AtTask Class

	public function pricingTotals() {
		set_time_limit(0);

		// 2014 Maserati/Disney Standard/Rate Card Details projects hardcoded here for a sample
		$criteria = [
			'client_ids' => [5429, 1059], //
			// 'version_ids' => [1221], // has/had 0 duration exception
			'fiscal_dates' => [201401, 201406] // start and end in order
			// 'fiscal_start' => 201404 // start only
		];

		$totals = \Finance\Reports\PricingProjectsTotals::getTotals($criteria);

		/*error_log(print_r([
			'file'   => __FILE__ . ' line ' . __LINE__,
			'totals' => $totals,
		], true));*/
		// print_r(['file'   => __FILE__ . ' line ' . __LINE__, 'totals' => $totals,]);

		foreach ($totals as $total){
			echo 'project_id: ' . $total['project_id'] . ' project Name: ' . $total['project_name'] . ' eas_client_id: ' . $total['eas_client_id'] . ' client name: ' . $total['client_name'] . ' cost: ' . $total['cost'];
			echo PHP_EOL;
			foreach ($total['components'] as $tc){
				echo "\t" . 'component_id: ' . $tc['component_id'] . ' component_name: ' . $tc['cost'] . ' cost: ' . $tc['cost'];
				echo PHP_EOL;
				foreach ($tc['versions'] as $tcv){
					echo "\t\t" . 'tversion_id: ' . $tcv['version_id'] . ' version_number: ' . $tcv['version_number'] . ' start(_year/_month_week): ' . $tcv['start_year'] . '/' 
					. $tcv['start_month'] . '/' . $tcv['start_week'] . ' end(_year/_month/_week): ' 
					. $tcv['end_year'] . '/' . $tcv['end_month'] . '/' . $tcv['end_week'] . ' is_current: ' . $tcv['is_current'] 
					. ' duration: ' . $tcv['duration'] . ' cost: ' . $tcv['cost'];
					echo PHP_EOL;
					foreach ($tcv['titles'] as $tcvt){
						echo "\t\t\t" .'eas_title_id: ' . $tcvt['eas_title_id'] . ' title_name: ' . $tcvt['title_name'] . ' cost: ' . $tcvt['cost'] 
						. ' hours: ' . $tcvt['hours'] . ' percentOfTime: ' . $tcvt['percentOfTime'];
						echo PHP_EOL;
					} //titles forech loop
				} //version foreach loop
			} //components foreach loop
		} //project foreach loop 
	} //End of Function


	public function allJobs(){
	
		$this->clientCompanies();
		
		//POPT scripts
		$this->poptClientCompanies();
		$this->poptJobCategory();
		$this->poptProductCodes();
		$this->poptVendors();
		$this->poptGlAccounts();
		$this->poptFunctionCodes();

		//run after popt scripts
		$this->feeBillingRecords();

	}

	public function functionExpenseTypes() {
		$time = microtime(1);


		$EAS = $this->EAS; 
		$this->dispDbInfo();

		$delete     = $this->delete;
		$limit      = $this->limit;
		$listData   = $this->list;
		$sim        = $this->sim;
		$update     = $this->update;
		$verbose    = $this->verbose;

		$DO_NOT_DELETE = $DO_NOT_UPDATE = [
			'General',
			'Consulting',
			'Advertising',
			'Travel',
			'Entertainment',
			'Materials',
			'Non-Billable'
		]; // by name


		// MB titles
		$q = "SELECT 
			FUNCTION_GROUP_ID AS functionGroupID,
			FUNCTION_GROUP_NAME AS funcitonGroupName
		FROM
			<table name removed>
		WHERE FUNCTION_GROUP_ID > = 1
		";
		if ($limit != '') {
			$q .= "AND ROWNUM <= $limit";
		}
		$q .= "
		GROUP BY 
			FUNCTION_GROUP_ID, 
			FUNCTION_GROUP_NAME";

		$EAS->executeQuery($q, RESULTS_ASSOC_ARRAY);
		$mbFuncExpTypes = $EAS->getResults();


		$fields =[
			'extRefID',
			'name',
		];


		$obj = 'exptyp';
		$list = $this->search($obj, [
			'fields' => implode(',', $fields),
		], 0);
		$atExptTypesKeys = AtTask::pivot($list, 'extRefID');


		if ($verbose == 'verbose') {
			echo 'AT dependency: ' . count($list) . ' expense types, ' .
				count($atExptTypesKeys) . ' name-pivoted' . PHP_EOL;
		}

		// UPDATE block
		if ($update == 'update') {
			$this->log(count($mbFuncExpTypes) . ' MB titles to UPDATE');
			$s = $f = $p = 0;
			$titleIDArray = [];
			foreach ($mbFuncExpTypes as $v) {
			$extRefID = $v['FUNCTIONGROUPID']; // primary
			$name =  $v['FUNCITONGROUPNAME'];

				// flush to output
				flush();
				ob_flush();

				
				echo str_pad($extRefID, 5) . ' ==> ' . str_pad($name, 5) . ' ';

				if($extRefID == 1004){
					$name = 'Misc General';
				}

				$data = [
					'extRefID' => $extRefID,
					'name' => $name,
				];

				if ($listData == 'list') {
					echo var_export($data, 1) . PHP_EOL;
				}


				// check current object status
				$where = null;
				$old_data = [];
				// $extRefID = $at_roleKeys['extRefID'];
				if ($old = @$atExptTypesKeys[$extRefID]) {
					foreach ($fields as $key) {
						$old_data[$key] = AtTask::bool($old->$key);
					}
					$where = $old->ID;
				}


				if (in_array($extRefID, $DO_NOT_UPDATE)) { // skip reserved
					echo 'SKIP (extRefID reserved)';
					$p++;
				} elseif (in_array($name, $DO_NOT_UPDATE)){
					echo 'SKIP (Name reserved)';
					$p++;
				} elseif ($data == $old_data) {
					echo 'SKIP (nothing to update)';
					$p++;
				} elseif ($sim == 'sim' || $this->post($obj, $data, $where)) {
					echo 'SUCCESS' . ($sim == 'sim' ? ' (sim)' : '');
					$s++;

					if ($verbose == 'verbose') {
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

					if ($err = $this->getLastError()) {
						echo ": $err->message";
						if (isset($err->code)) {
							echo " (code: $err->code)";
						}
					}
				}
					echo PHP_EOL;

			 }
		$this->log("Total $s success, $f fail, $p skipped");
		}
		$this->log('Time elapsed: ' . $this->time($time));
		$this->log('==========================');
	}

	

	/*
		POPT Scripts:				Runs
		1) popt-clients-companies 	X
		2) popt-function-codes    	X
		3) popt-gl-accounts       	X
		4) popt-job-category      	X
		5) popt-product-code      	X
		6) popt-vendor            	X
	*/
	
	public function poptClientCompanies(){
	//1)
		$time = microtime(1);
		// primary AT field for this script is 'extRefID' that is mapped from MB.eas_clients.eas_client_id

		$dbObj = $this->DB;
		$this->dispDbInfo();

		$delete     = $this->delete;
		$limit      = $this->limit;
		$listData   = $this->list;
		$sim        = $this->sim;
		$update     = $this->update;
		$verbose    = $this->verbose;

		$DO_NOT_DELETE = $DO_NOT_UPDATE = [];

		$q = "SELECT 
			e.eas_client_id, 
			e.client_name, 
			e.client_short_name, 
			e.client_code, 
			e.is_active, 
			e.lead_office_id, 
			c.id AS mb_id, 
			c.`name` AS mb_name, 
			e.title_card_id, 
			e.type_id,
			e.multiplier,
			e.hours_per_year,
			e.rate_card_id
		FROM <table name removed> e 
			LEFT OUTER JOIN <table name removed> c ON e.client_code = c.`code`
		ORDER BY 3 ASC
		";
		if ($limit != '') {
			$q .= "LIMIT $limit";
		}
		$dbObj->executeQuery($q, RESULTS_ASSOC_ARRAY);
		$mbClients = $dbObj->getResults();
		$mbClientsKeys = array_keys(AtTask::pivot($mbClients, 'client_code'));
		$clientMax = AtTask::getMax($mbClients, 'client_short_name', 1);
		
		// MB clients
		$fields = [ // must be the same as the $data array in the UPDATE block below
			'extRefID',
			'isHidden',
			'parameterID',
			'label',
			'value',
			'displayOrder',
		];


		// dependency: AT popt-companies
		$obj = 'popt';
		$parameterID = '534c06f402838e2be3ce5af671f3ef3a'; // Client Name
		$aPopt = $this->search($obj, [
			'parameterID' => $parameterID,
			'fields'      => implode(',', $fields),
		], 0);
		$aPoptId = AtTask::pivot($aPopt, 'extRefID');



		// UPDATE block
		if ($update == 'update') {
			$this->log(count($mbClients) . ' MB clients to UPDATE');
			AtTask::debugList($mbClients, ['eas_client_id', 'client_short_name'] );

			$s = $f = $p = $displayOrder = 0;
			foreach ($mbClients as $v) {
				$primary = $v['eas_client_id'];

				// flush to output
				flush();
				ob_flush();

				echo str_pad($v['client_short_name'], $client_max) . ' ==> ';

				$label = $v['client_name'];
				
				$data = [
					'extRefID'     => AtTask::bool($v['eas_client_id']),
					'isHidden'     => AtTask::bool( ! $v['is_active']),
					'parameterID'  => $parameterID,
					'label'        => $label,
					'value'        => $label, // unique
					'displayOrder' => ++$displayOrder,
				];
				if ($listData == 'list') {
					echo var_export($data, 1) . PHP_EOL;
				}


				// check current object status
				$where = null;
				$old_data = [];
				if ($old = @$aPoptId[$primary]) {
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
				} elseif (isset($_GET['sim']) || $this->post($obj, $data, $where)) {
					echo 'SUCCESS' . ($sim == 'sim' ? ' (sim)' : '');
					$s++;

					if ($verbose == 'verbose') {
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
					
					if ($err = $this->getLastError()) {
						echo ": $err->message";
						if (isset($err->code)) {
							echo " (code: $err->code)";
						}
					}
				}
				echo PHP_EOL;
			}
			$this->log("Total $s success, $f fail, $p skipped");
		}
		$this->log('Time elapsed: ' . $this->time($time));
		$this->log('==========================');
	

}

