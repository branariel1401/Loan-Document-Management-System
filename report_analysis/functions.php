<?php
function db_connect($db){
	$hostname = "localhost";
	$username = "webuser";
	$password = "MlcHfBaRSsw-oNmR";
		
	$dblink = new mysqli($hostname, $username, $password, $db);
		
	if ($dblink->connect_error){
		throw new Exception("Error connecting to database". $dblink->connect_error);
	}
	
	return $dblink;
}

function redirect ($uri)
{
	?>
	<script type="text/javascript">
		document.location.href="<?php echo $uri; ?>";
	</script>
<?php die;
}

function api_requests($api, $data, $apiCall) {
	//try{
		$now=date("Y-m-d H:i:s");
		$ch=curl_init($api); //declaring endpoint that we're accessing 
		curl_setopt($ch, CURLOPT_POST,1); //all option are stands for every curl handler
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'content-type: application/x-www-form-urlencoded', 
			'content-length: '. strlen($data)));
		$time_start=microtime(true); 
		$res=curl_exec($ch);

	
		if(curl_errno($ch)) {
			$errorMessage="[".$now."] - $apiCall - ".curl_errno($ch)." - ".curl_error();
			throw new Exception($errorMessage);
		}
		else{
			$time_end=microtime(true); //tracking info
			$executon_time=($time_end-$time_start)/60; 
			curl_close($ch);
		}
		$time_end=microtime(true);
		curl_close($ch);

	return $res;
}

function request_all_documents($username, $sid) {
	$data="uid=$username&sid=$sid";
	$result=api_requests('https://cs4743.professorvaladez.com/api/request_all_loans', $data, 'request_all_loans');
	return json_decode($result,true);
}

function request_all_loans($username, $sid) {
	$data="uid=$username&sid=$sid";
	$result=api_requests('https://cs4743.professorvaladez.com/api/request_all_loans', $data, 'request_all_loans');
	return json_decode($result,true);
}

function request_file_by_loan($username, $sid, $loanid) {
	$data="uid=$username&sid=$sid";
	$result=api_requests('https://cs4743.professorvaladez.com/api/request_all_loans', $data, 'request_all_loans');
	return json_decode($result,true);
}

function create_session($username, $password) {
	$data="username=$username&password=$password";;
	$result=api_requests('https://cs4743.professorvaladez.com/api/create_session', $data, 'query_files');
	return json_decode($result,true);
}

function close_session($sid) {
	$data="sid=$sid";
	$result=api_requests('https://cs4743.professorvaladez.com/api/close_session', $data, 'close_session');
	return json_decode($result,true);
}

function clear_session($username, $password) {
	$data="username=$username&password=$password";
	$result=api_requests('https://cs4743.professorvaladez.com/api/clear_session', $data, 'clear_session');
	return json_decode($result,true);
}

?>





?>