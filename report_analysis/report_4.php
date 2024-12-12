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
/*
For each loan number from number 1,
	* the total number of documents received 
	* the average size of all documents for the given loan number and state if this average is above or 	below the global average size from question 2 
	* compare the total number of documents for each loan to the global average and state if it is above or below average from question 3.
*/
	include("functions.php");
	$now=date("Y-m-d H:i:s");

	try {
		try {
			$dblink=db_connect("doc_management4743");
		} catch(Exception $e) {
			logging_err($now,'Database Connection',$dblink->errno,$dblink->error);
		}

		//Complete AvgTotal Documents
		$sql1 = "Select avg(`doc_count`) as `global_avg_docs` 
						 from (Select left(`file_name`, LOCATE('-', `file_name`) - 1) as `loan_number`, COUNT(*) as `doc_count` 
							   from `documents` 
							   group by `loan_number`) as `loan_docs`";
		$result1=$dblink->query($sql1) or
			logging_err($now,'Database',$dblink->errno,$dblink->error);
		$data1 = $result1->fetch_assoc()['global_avg_docs'];

		//Complete AvgSize Documents
		$sql2 = "Select avg(`file_size`) as `global_avg_size` FROM `documents`";
		$result2 = $dblink->query($sql2) or
			logging_err($now,'Database',$dblink->errno,$dblink->error);
		$data2 = $result2->fetch_assoc()['global_avg_size'];

		echo '<div id="page-inner">';
		echo '<h1 class="page-head-line">Report 4</h1>';
		echo '<div class="panel-body">';

		$sql = "Select distinct left(`file_name`, LOCATE('-', `file_name`) - 1) as `loan_number` from `documents`";
		$result=$dblink->query($sql) or
			logging_err($now,'Database',$dblink->errno,$dblink->error);

		while($data=$result->fetch_assoc()) {
			$loanNum=$data['loan_number'];

			$sqldocsCount = "Select count(*) as `doc_count` from `documents` 
						 where left(`file_name`, locate('-', `file_name`) - 1) = '$loanNum'";
			$res1 = $dblink->query($sqldocsCount) or
				logging_err($now,'SQL',$dblink->errno,$dblink->error);
			$d1 = $res1->fetch_assoc()['doc_count'];

			// Step 5: Calculate the average size of documents for this loan
			$sqlAvgSize = "Select avg(`file_size`) as `avg_size` from `documents` 
						   where left(`file_name`, locate('-', `file_name`) - 1) = '$loanNum'";
			$res2 = $dblink->query($sqlAvgSize) or
				logging_err($now,'SQL',$dblink->errno,$dblink->error);
			$d2 = $res2->fetch_assoc()['avg_size'];

			// Step 6: Compare to global averages
			if ($d1 > $data1) {
				$docCompare = "Above Global Average";
			} else {
				$docCompare = "Below Global Average";
			}

			if ($d2 > $data2) {
				$sizeCompare = "Above Global Average";
			} else {
				$sizeCompare = "Below Global Average";
			}

			
			echo "<h3>Loan: $loanNum</h3>";
			//echo "<h2>Global Avg Docs: $globald1</h2>";
			echo "<h2>Total number of documents received: $d1</h2>";
			echo "<h2>($docCompare)</h2><br>";

			echo "<h2>$globalAvgSize</h2>";
			//echo "<h2>Global Avg Doc Size: $globalAvgSize</h2>";
			echo "<h2>Average Document Size of $loanNum: ".$d2.".</h2>";
			echo "<h2>($sizeCompare)</h2>";
			echo "<br>";
			echo "<h2>--------------------------------------------------------------------------</h2>";
		}
	} catch(Exception $e) {
		logging_err($now,'Exception',$dblink->errno,$dblink->error);
	}
	echo '</div>';
	echo '</div>';
?>
</body>
</html>
