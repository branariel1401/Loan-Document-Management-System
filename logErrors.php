<?php

try {
	$dblink=db_connect("errorLog");
} catch(Exception $e) {
	echo $e->getMessage()."\r\n\r\n";
}

function logging_err() {
	$contentClean=addslashes($content);
	$fileName=addslashes($value);
	$fileSize=strlen($content);
	$now=date("Y-m-d H:i:s"); //now, sql, and contentClean
	$sql="Insert into `errorLog` (`file_name`,`file_size`,`date_upload`,`upload_type`,`content`) values ('$fileName','$fileSize','$now','cron','$contentClean')";

	if(!$dblink->query($sql)) {
		$errorMessage=addslashes("[".$now."] - $apiCall - ".$dblink->errno." - ".$dblink->error);
		throw new Exception($errorMessage);
	}
}

?>