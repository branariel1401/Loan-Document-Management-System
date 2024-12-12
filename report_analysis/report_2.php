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
$dblink=db_connect("doc_management4743");
echo '<div id="page-inner">';
echo '<h1 class="page-head-line">Report 2</h1>';
echo '<div class="panel-body">';
$sql = "Select sum(`file_size`) as `total_size` from `documents`";
$result=$dblink->query($sql) or
	logging_err($now,'Database',$dblink->errno,$dblink->error);

if($data=$result->fetch_assoc()){
	$totalNum=$data['total_size'];
	echo '<h2>Total Size of Documents recieved from API: '.$totalNum.'KB</h2>';
} else {
	echo '<h2>Total Size of Documents: Not available</h2>';
}

$sql2="Select avg(`file_size`) as `avg_size` from `documents`";
$result=$dblink->query($sql2) or
	logging_err($now,'Database',$dblink->errno,$dblink->error);
	
if($data=$result->fetch_assoc()){
	$avgSize=$data['avg_size'];
	echo '<h3>Average Size of all Documents: '.$avgSize.'KB</h3>';
}


echo '</div>';
echo '</div>';
	
?>
</body>
</html>
