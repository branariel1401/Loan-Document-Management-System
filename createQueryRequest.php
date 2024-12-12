<?php
$page="createSID";
$username="xfo404"; //credentials
$password="KLDhxc*qkyztHvP";
$data="username=$username&password=$password"; //payload
$ch=curl_init('https://cs4743.professorvaladez.com/api/create_session'); //declaring endpoint that we're accessing 
curl_setopt($ch, CURLOPT_POST,1); //all option are stands for every curl handler
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded', 
	'content-length: '. strlen($data)));

$time_start=microtime(true); 
$result=curl_exec($ch);
$time_end=microtime(true); //tracking info
$executon_time=($time_end-$time_start)/60; 
curl_close($ch);
$cinfo=json_decode($result,true); //converts json to ARRAY
echo "<pre>";
print_r($cinfo); //result of the array that holds session id
echo "</pre>";

//check current status
if($cinfo[0]=="Status: OK")
{
	$sid=$cinfo[2]; //index 2 holds the sessionID in the array
	$data="uid=$username&sid=$sid";
	//echo "<h3> Payload for query_files: $data</h3>";
	$ch=curl_init('https://cs4743.professorvaladez.com/api/query_files'); //we're querying files, next endpoint
	curl_setopt($ch, CURLOPT_POST,1); //all option are stands for every curl handler
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'content-type: application/x-www-form-urlencoded', 
		'content-length: '. strlen($data)));
	$time_start=microtime(true);
	$result=curl_exec($ch);
	$time_end=microtime(true);
	$execTime=($time_end-$time_start)/60; //track this and the size of the payload
	curl_close($ch);  //close cause then server crashes
	$cinfo=json_decode($result,true);
	echo "<pre>";
	print_r($cinfo);
	echo "</pre>";
	$temp=explode(":",$cinfo[1]);
	$payLoad=json_decode($temp[1]);
	//process our payload
	foreach($payLoad as $key=>$value)//key=index value=data pair
	{
		$data="sid=$sid&uid=$username&fid=$value";
		$ch=curl_init('https://cs4743.professorvaladez.com/api/request_file'); //we're querying files, next endpoint
		curl_setopt($ch, CURLOPT_POST,1); //all option are stands for every curl handler
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'content-type: application/x-www-form-urlencoded', 
			'content-length: '. strlen($data)));
		$time_start=microtime(true);
		$result=curl_exec($ch);
		$time_end=microtime(true);
		$execTime=($time_end-$time_start)/60;
		curl_close($ch);
		if(strstr($result, "Status")){
			echo "<h2> There was an error with file:$value</h2>";
			echo "<pre>";
			echo $result;
			echo "</pre>";
			continue;
		}
		else{
		$content=$result;
		if(strlen($content) == 0)
		{
			echo "<h2> File was empty</h3>";
			continue; //really bad with bugs
		}
		$fp=fopen("/var/www/files/$value","wb");
		fwrite($fp, $content);
		fclose($fp);
		echo "<h3> File $value written to filesystem</h3>";
		}
	}
	
	$data="sid=$sid";
	$ch=curl_init('https://cs4743.professorvaladez.com/api/close_session'); //we're querying files, next endpoint
		curl_setopt($ch, CURLOPT_POST,1); //all option are stands for every curl handler
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'content-type: application/x-www-form-urlencoded', 
			'content-length: '. strlen($data)));
	curl_exec($ch);
	echo "<h3> Session closed. Done.</h3>";
}
else
{
	echo "<pre>";
	print_r($cinfo); //result of the array that holds session id
	echo "</pre>";
}

//
?>































