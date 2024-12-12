<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Report 1</title>
<link href="assets/css/bootstrap.css" rel="stylesheet"/>
<!-- BOOTSTRAP STYLES -->
<link href="assets/css/font-awesome.css" rel="stylesheet"/>
	
<link href="assets/css/basic.css" rel="stylesheet"/>
	
<link href="assets/css/custom.css" rel="stylesheet"/>
	
<link href="assets/css/bootstrap-fileupload.min.css" rel="stylesheet"/>
	
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-fileupload.js"></script>	
</head>
<body>
<?php
include("functions.php");
$now=date("Y-m-d H:i:s");
	
try{
	$username="xfo404"; //credentials
	$password="KLDhxc*qkyztHvP";

	try {
		$dblink=db_connect("doc_management4743");
	//$dblink2=db_connect("errorLog");
	} catch(Exception $e) {
		logging_err($now,'Database Connection',$dblink->errno,$dblink->error);
	}
	
	echo '<div id="page-inner">';
	echo '<h1 class="page-head-line">Report 3</h1>';
	echo '<div class="panel-body">';
	
	$sql = "Select count(*) as `total_count` from `documents`";
	$result=$dblink->query($sql) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);

	if($data=$result->fetch_assoc()){
		$totalCount=$data['total_count'];
		echo '<h2>Total Count of all Documents recieved from API: '.$totalCount.'</h2>';
	} else {
		echo '<h2>Total Count of Documents: Not available</h2>';
	}

	$sql2 = "Select avg(`doc_count`) as `average_documents` 
                     from (Select substring_index(`file_name`, '-', 1) AS `loan_number`, count(*) as `doc_count` 
					 from `documents` 
					 group by `loan_number`) as `loan_docs`";
	$result=$dblink->query($sql2) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);

	if($data=$result->fetch_assoc()){
		$avgDocs=$data['average_documents'];
		echo '<h3>Average Number of Documents per Loan: '.$avgDocs.'</h3>';
	} else {
		echo '<h2>Average Number of Documents per Loan: Not available</h2>';
	}


	echo '</div>';
	echo '</div>';
	
} catch (Exception $e) {
	logging_err($now,'Error: ',$e->errno,$e->error);
}
	
?>
</body>
</html>
