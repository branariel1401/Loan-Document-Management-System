<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Report Analysis</title>
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
echo '<h1 class="page-head-line">Select The Appropriate Report</h1>';
echo '<div class="panel-body">';

echo '<p><a class="btn btn-primary" href="report_1.php">Total Unique Loans</a></p>';
echo '<p><a class="btn btn-primary" href="report_2.php">Total Doc Size/Average</a></p>';	
echo '<p><a class="btn btn-primary" href="report_3.php">Total Doc Count</a></p>';
echo '<p><a class="btn btn-primary" href="report_4.php">Doc Number for Each Loan</a></p>';
echo '<p><a class="btn btn-primary" href="report_5.php">Complete/Uncomplete Loans</a></p>';
echo '<p><a class="btn btn-primary" href="report_6.php">Total Num of Each Doc Type</a></p>';
echo '<p><a class="btn btn-primary" href="report_7.php">Error Logging</a></p>';
echo '</div>';
echo '</div>';
?>
</body>
</html>
