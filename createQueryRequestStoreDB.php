<?php
error_reporting(E_ALL);
ini_set('display_errors','1');

include("functions.php");
date_default_timezone_set('America/Chicago');

try {
	try {
		$dblink=db_connect("doc_management4743");
		//$dblink2=db_connect("errorLog");
	} catch(Exception $e) {
		echo $e->getMessage()."\r\n\r\n";
	}

	$page="createSID";
	$username="xfo404"; //credentials
	$password="KLDhxc*qkyztHvP";

	$cinfo=create_session($username,$password); //converts json to ARRAY that holds session ID
	//check current status
	echo "";
	print_r($cinfo);
	echo "\r\n\r\n";
	$sid=$cinfo[2];
	if($cinfo[0]=="Status: OK") {
		//$sid=$cinfo[2]; //index 2 holds the sessionID in the array
		$cinfo=query_files($username, $sid);

		echo "\r";
		print_r($cinfo);
		echo "\r\n\r\n";
		$temp=explode(":",$cinfo[1]);
		$payLoad=json_decode($temp[1], true);

		foreach($payLoad as $key=>$value) {//key=index value=data pair, process our payload
			try {
				$now=date("Y-m-d H:i:s");
				$result=request_files($username, $sid, $value);
				$apicall='request_files';

				if(strstr($result, "Status"))	
				{
					$errorMessage=addslashes("[".$now."] - $apicall - ".$value." - ".$result);
					echo "$errorMessage\r\n\r\n";
					throw new Exception($errorMessage);
				}
				else 
				{
					$content=$result;

					if(strlen($content)==0)
					{
						$code=204;
						$mssg='file is empty.';
						$errorMessage=addslashes("[".$now."] - $apicall - ".$code." - ".$mssg);
						throw new Exception($errorMessage);
					}
					else 
					{
						$fp=fopen("/var/www/html/filesCollected/$value","wb");
						fwrite($fp, $content);
						fclose($fp);
						$contentClean=addslashes($content);
						$fileName=addslashes($value);
						$fileSize=strlen($content);
						$now=date("Y-m-d H:i:s"); //now, sql, and contentClean
						$sql="Insert into `documents` (`file_name`,`file_size`,`date_upload`,`upload_type`,`content`) values ('$fileName','$fileSize','$now','cron','$contentClean')";

						if(!$dblink->query($sql)) {
							$errorMessage=addslashes("[".$now."] - $apiCall - ".$dblink->errno." - ".$dblink->error);
							throw new Exception($errorMessage);
						}


						echo "File $value requested successfully at [".$now."] \r\n\r\n";
					}
				}
			} catch (Exception $e) {
				$now=date("Y-m-d H:i:s");
				echo "[".$now."] - request_files -".$e->getCode()." - ".$e->getMessage()."\r\n\r\n";
			}
		}

		$closed=close_session($sid);
		echo "[".date("Y-m-d H:i:s")."] - closed_session\n";
		print_r($closed);
		echo "\r\n\r\n";
		
		if($closed) {
			$cleared=clear_session($username,$password);
			echo "[".date("Y-m-d H:i:s")."] - clear_session\n";
			print_r($cleared);
			echo "\r\n\r\n";
		}
	
	}
	else
	{
		print_r($cinfo); //result of the array that holds session id
		echo "\r\n\r\n";
		$closed=close_session($sid);
		echo "[".date("Y-m-d H:i:s")."] - closed_session\n".print_r($closed)."\r\n\r\n";
	}
} catch(Exception $e) {
	$now=date("Y-m-d H:i:s");
	$errorMsgphp="[".$now."] - PHP Error - ".$e->getCode()." - ".$e->getMessage();
	echo $errorMsgphp."\r\n\r\n";
}
//
?>
