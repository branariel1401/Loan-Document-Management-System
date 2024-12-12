<?php
include("functions.php");
$dblink2 = db_connect("doc_management4743");
$file="/var/www/html/logs/createQueryRequestStoreDBCRON.log";

parse_Error_Log($file, $dblink2);

function parse_Error_Log($file, $dblink2) {
	$lastTS=getTS($dblink2);
	
	if(file_exists($file)) {
		$logfile=fopen($file, "r");
		
		while($line = fgets($logfile)) {
			preg_match('^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]', $line, $matches);
			
			if(count($matches) > 0) {
				$logTimeStamp=strtotime($matches[1]);
				
				if($logTimeStamp > $lastTS) {
					log_errors($line);
				}
			}
		}
		fclose($logfile);
	}
	return null;
}

function getTS($dblink2) {
	try {
	$sql="SELECT MAX(date_occurred) as last_processed FROM errorLog";
	$result=$dblink2->query($sql);
	
	if($result && $row = $result->fetch_assoc()) {
		return strtotime($row['last_processed']);
	}
	
	return 0;
	} catch(Exception $e) {
		echo "Error retrieving last processed timestamp\r\n\r\n";
		return 0;
	}
}

function log_errors($error) {
	global $dblink2;
	
	try{
		if($error){
			$temp=explode(" - ",$error);

			if(count($temp) == 4) {
				$now = addslashes(trim($temp[0]));
				$apiCall=addslashes(trim($temp[1]));
				$responseCode=addslashes(trim($temp[2]));
				$err_messg=addslashes(trim($temp[3]));

				$sql="Insert into `errorLog` (`date_occurred`,`api_call`,`response_code`,`err_mssg`) values ('$now','$apiCall','$responseCode','$err_messg')";
				if(!$dblink2->query($sql)) {
					$logMessage="[".$now."] - Database Error: ".$db->error." - Query: $sql\r";
					echo "<h3>$logMessage</h3>";

				}else {
					echo "Error logged in database!\r\n\r\n";
				}
			} else {
				echo "Log entry format is invalid.\r\n\r\n";
			}
		}
	} catch(Exception $e) {
		echo $e->getMessage()."\r\n\r\n";
	}
	
	
}
?>