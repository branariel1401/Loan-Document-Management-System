<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Document</title>
<link href="assets/css/bootstrap.css" rel="stylesheet"/>
<!-- BOOTSTRAP STYLES -->
<link href="assets/css/font-awesome.css" rel="stylesheet"/>
	
<link href="assets/css/basic.css" rel="stylesheet"/>
	
<link href="assets/css/custom.css" rel="stylesheet"/>
	
<link href="assets/css/bootstrap-fileupload.min.css" rel="stylesheet"/>
	
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-fileupload.js"></script>	
<script>
	window.onbeforeunload = function () {
		var fileName = "<?php echo isset($data['file_name']) ? addslashes($data['file_name']) : ''; ?>";
		if(fileName){
			navigator.sendBeacon('/purge_files.php', JSON.stringify({ file: '<?= $data['file_name'] ?>' }));
		}
		
	}	
</script>

</head>
<body>
<?php
	include("functions.php");
	include("otherFunctions.php");
	echo '<div id="page-inner">';
	echo '<h1 class="page-head-line">View Document</h1>';
	echo '<div class="panel-body">';
	$dblink=db_connect("doc_management4743");
	$docID=$_GET['docid'];
	$now = date("Y-m-d H:i:s");
	$updateSQL = "Update `documents` set `last_accessed`='$now' where `auto_id`='$docID'";
	$result=$dblink->query($updateSQL) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);
																 
	$sql="Select * from `documents` where `auto_id`='$docID'";
	$result=$dblink->query($sql) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);
	$data=$result->fetch_array(MYSQLI_ASSOC);
	
	echo '<h3>Document Name: '.$data['file_name'].'</h3';
	//Take content blob from db back to filesys to view by client
	$content=$data['content'];
	$fp=fopen("/var/www/html/view/$data[file_name]", "wb");
	fwrite($fp,$content);
	fclose($fp);
	echo '<br>';
	echo '<h3><a href="/view/'.$data['file_name'].'" target="_blank">View Document</a></h3>';
															 
	echo '<table class="table table-striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>File Size: </th>';
    echo '<th>Last Access: </th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr>';
    echo '<td>' . addslashes($data['file_size']) . '</td>';
    echo '<td>' . addslashes($data['last_accessed']) . '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
																 
	echo '</div>';//end panel
	echo '</div>';
	
?>
	
</body>
</html>