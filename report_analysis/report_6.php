<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Report 6</title>
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
	echo '<div id="page-inner">';
	echo '<h1 class="page-head-line">Report 6</h1>';
	echo '<div class="panel-body">';
	include("functions.php");
	include("errorLogging.php");

	try {
		$dblink=db_connect("doc_management4743");
	} catch(Exception $e) {
		logging_err($now,'Database Connection',$dblink->errno,$dblink->error);
	}

	$sql = "Select * from `doc_required`";
	$result=$dblink->query($sql) or
		logging_err($now,'Database',$dblink->errno,$dblink->error);

	$documentTypes = [];
	while ($data=$result->fetch_assoc()) {
		$documentTypes[] = $data['name'];
	}

	$docCounts = [];
	foreach($documentTypes as $type) {
		 $docTypeSql = str_replace(" ", "_", $type);

		$sql2 = "SELECT COUNT(`auto_id`) AS `total_count` FROM `documents` WHERE `file_name` LIKE ?";
		$stmt2 = $dblink->prepare($sql2);
		$searchTerm = "%" . $docTypeSql . "%";  // Add the percentage symbols to let the sql query know the cutoff lol
		$stmt2->bind_param("s", $searchTerm);
		$stmt2->execute();
		$res = $stmt2->get_result();

		// Get the count of matching documents
		$data = $res->fetch_assoc();
		$docCounts[$type] = $data['total_count'];
	}

	echo "<table class='table table-bordered'>";
	echo "<tr><th>Document Type</th><th>Total Count</th></tr>";

	foreach ($documentTypes as $type) {
		// Get the count for the current document type (default to 0 if not found)
		$count = isset($docCounts[$type]) ? $docCounts[$type] : 0;
		echo "<tr><td>" . htmlspecialchars($type) . "</td><td>" . $count . "</td></tr>";
	}

	echo "</table>";
	echo '</div>';
	echo '</div>';
	
?>
	
	
	
	
	
	
	
	
	
	
	
	
	