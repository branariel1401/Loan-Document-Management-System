<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Search Document Type</title>
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
	echo '<h1 class="page-head-line">Select the Document Type:</h1>';
	echo '<div class="panel-body">';
	echo '<form action="" method="post">';
	echo '<div class="form-group">';
	echo '<label for="docType" class="control-label">Document Type</label>';
	echo '<select class="form-control" name="docType">';
	$sql = "Select * from `doc_required`";
	$result=$dblink->query($sql) or
			logging_err($now,'Database',$dblink->errno,$dblink->error);
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<option value="'.$data['auto_id'].'">'.$data['name'].'</option>';
	}
	echo '</select>';
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
	$docTypeId=$_POST['docType'];
	$sql="Select `name` from `doc_required` where `auto_id`='$docTypeId'";
	$result=$dblink->query($sql) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);
	$tmp=$result->fetch_array(MYSQLI_ASSOC);
	$docType=$tmp['name'];
	$docTypeSql=str_replace(" ", "_",$docType);
	echo '<div id="page-inner">';
	echo '<h1 class="page-head-line">Results for Document Type: '.$docType.'</h1>';
	echo '<div class="panel-body">';
	$sql="Select `file_name`,`auto_id` from `documents` where `file_name` like '%$docTypeSql%'";
	echo '<table class="table table-hover">';
	echo '<tbody>';
	$result=$dblink->query($sql) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);
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








