<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Search Main</title>
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
echo '<h1 class="page-head-line">Select the search criteria:</h1>';
echo '<div class="panel-body">';

echo '<p><a class="btn btn-primary" href="search_loanID.php">By Loan ID</a></p>';
echo '<p><a class="btn btn-primary" href="search_docType.php">By Document Type</a>';	
echo '<p><a class="btn btn-primary" href="search_date.php">By Date</a></p>';
echo '<p><a class="btn btn-primary" href="search_docName.php">By Document Name</a></p>';
echo '</div>';
echo '</div>';
?>
</body>
</html>
