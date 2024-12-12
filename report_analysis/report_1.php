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
echo '<h1 class="page-head-line">Report 1</h1>';
echo '<div class="panel-body">';
$sql = "SELECT DISTINCT LEFT(`file_name`, LOCATE('-', `file_name`) - 1) AS `loan_number` FROM `documents`";
$result=$dblink->query($sql) or
	logging_err($now,'Database',$dblink->errno,$dblink->error);

$totalNum=$result->num_rows;
echo '<h2>Total Number of Unique Loans: '.$totalNum.'</h2>';
	
result_pdfile($result);


echo '</div>';
echo '</div>';
	
function result_pdfile($result) {
	$count=0;
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{	
		$count++;
		echo '<h3>'.$count.'. '.$data['loan_number'].'</h3>';
	}
}
?>
</body>
</html>
