<?php
$page="createSID";
$username="xfo404";
$password="KLDhxc*qkyztHvP";
$data="username=$username&password=$password";
$ch=curl_init('https://cs4743.professorvaladez.com/api/clear_session');
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded', 
	'content-length: '. strlen($data)));
$time_start=microtime(true); 
$result=curl_exec($ch);
$time_end=microtime(true); //stop watch
$executon_time=($time_end-$time_start)/60; 
//result will hold json encoded string, convert to an array to manipulate information, json sucks
$cinfo=json_decode($result,true); //always give me data
echo "<pre>";
print_r($cinfo);
echo "</pre>";
//log_info($execution_time,$dateTimeStamp,$apiStatusResponse,$apiRoute,$page);
//reuse logging calls
?>
