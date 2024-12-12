<?php

try {
	$dblink=db_connect("doc_management4743");
} catch(Exception $e) {
	echo $e->getMessage()."\r\n\r\n";
}

function logging_err($now,$name,$errNum,$errMssg) {
	$sql="Insert into `errorLog` (`date_occurred`,`api_call`,`response_code`,`err_mssg`) values ('$now','$name','$errNum','$errMssg')";
	$result=$dblink->query($sql) or
		die("Something went wrong: ").$dblink->error;
}

?>