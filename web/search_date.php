<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Search By Loan ID</title>
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
include("otherFunctions.php");
$dblink=db_connect("doc_management4743");
if(!isset($_POST['submit']))
{
	echo '<div id="page-inner">';
	echo '<h1 class="page-head-line">Search By Date</h1>';
	echo '<div class="panel-body">';
	echo '<form action="" method="post">';
	echo '<div class="form-group">';
	echo '<label for="date" class="control-label">Select a Specific Date:</label>';
    echo '<input type="date" name="date" class="form-control" />';           
	echo '<hr>';
	echo '<button type="submit" name="submit" value="submit" class="btn btn-lg btn-block btn-success">Search Files</button>';
	echo '</div>';	
	echo '</form>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

if(isset($_POST['submit'])) 
{
	$date=$_POST['date'];
	$sql="Select `file_name`, `auto_id` from `documents` where `date_upload` like '$date%'";
	$result=$dblink->query($sql) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);
		
	echo '<div id="page-inner">';
	echo '<h1 class="page-head-line">Results by Date: </h1>';
	echo '<div class="panel-body">';
	echo '<table class="table table-hover">';
	echo '<tbody>';

	while($data=$result->fetch_array(MYSQLI_ASSOC)) {
		echo '<tr><td>';
		echo '<a href="view_doc.php?docid='.$data['auto_id'].'">'.$data['file_name'].'</a>';
		echo '</td></tr>';
	}
		
	
	echo '</tbody>';
	echo '<table>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
?>
</body>
</html>








