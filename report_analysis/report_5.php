<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Report 5</title>
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
echo '<h1 class="page-head-line">Report 5</h1>';
echo '<div class="panel-body">';
include("functions.php");
include("errorLogging.php");
$now=date("Y-m-d H:i:s");
	
try{
	//credentials
	$username="xfo404"; 
	$password="KLDhxc*qkyztHvP";

	try {
		$dblink=db_connect("doc_management4743");
	} catch(Exception $e) {
		logging_err($now,'Database Connection',$dblink->errno,$dblink->error);
	}

	$sql = "Select * from `doc_required`";
	$result = $dblink->query($sql) or logging_err($now, 'Database', $dblink->errno, $dblink->error);
	while($row = $result->fetch_assoc()) {
		$requiredDocs[] = $row['name'];
	}
	
	$cinfo=create_session($username,$password);
	$sid=$cinfo[2];

	if($cinfo[0]=="Status: OK") {
		$cinfo=request_all_loans($username, $sid); //first request all loan IDs
		$temp=explode(":",$cinfo[1]);
		$loanIDs=json_decode($temp[1], true);
		
		$missingDocsLoans=[];
		$complete=[];
		$zeroDocuments=[];
		
		$cinfo=request_file_by_loan($sid,$username, $loanID);
		$loanDocs=json_decode($temp[1], true);
		
		foreach($loanIDs as $loanID) {
			
			
			//$temp=explode(":",$cinfo[1]);
			
			
			$sql = "Select substring_index(substring_index(`file_name`, '-', 1), '-', 1) as `loan_id`,
                substring_index(substring_index(`file_name`, '-', 2), '-', -1) as `doc_type`
            from `documents`
            where left(`file_name`, LOCATE('-', `file_name`) - 1) = '$loanID'";
        	$dbRes = $dblink->query($sql) or
				logging_err($now,'SQL',$dblink->errno,$dblink->error);
			
			
			$dbDocs = [];
			while($data=$dbRes->fetch_assoc()) {
				$dbDocs[] = $data['doc_type'];
			}
			
			/*
			echo '<pre>';
			echo 'Loan Docs: ';
			print_r($loanDocs);  // To display the contents of $loanDocs
			echo '<br>';

			echo 'DB Docs: ';
			print_r($dbDocs);    // To display the contents of $dbDocs
			echo '</pre>';
			*/
			
			$apiAndDB=array_unique(array_merge($loanDocs, $dbDocs));
			
			if(empty($apiAndDB)) {
				$zeroDocuments[] = $loanID;
			}else {
				$missingDocs = array_diff($requiredDocs, $apiAndDB);
				if(empty($missingDocs)) {
					$completeLoans[] = $loanID;
				} else {
					$missingDocsLoans[$loanID] = $missingDocs;
				}
			}
			
		}
		
		//1.
		echo '<h2>Loans With Missing Documents</h3>';
		foreach($missingDocsLoans as $loanID => $missingDocs) {
			echo '<br>';
			echo '<h4>Loan ID: '.$loanID.'</h4>';
			echo '<h4>Missing: '.implode(',', $missingDocs).'</h4>';
			echo "<h4>--------------------------------------------------------------------------</h4>";	
			echo '<br>';

		}
		//2.
		echo '<h2>Completed Loans</h2>';
		foreach($completeLoans as $loanID) {
			echo '<br>';
			echo '<h4>Loan ID: '.$loanID.'</h4>';
			echo "<h4>--------------------------------------------------------------------------</h4>";
			echo '<br>';
		}
		//3.
		echo '<h2>Loans with No Documents</h2>';
		foreach($zeroDocuments as $loanID) {
			echo '<h4>Loan ID: '.$loanID.'</h4>';
			echo "<h4>--------------------------------------------------------------------------</h4>";
			echo '<br>';
		}
		
	
		echo '</div></div>';
		$clearsesh = clear_session($username, $password);
		if ($clearsesh[0] != "Status: OK") {
			logging_err($now, 'clear_session', 0, $closeInfo[1]);
		}
	}
} catch (Exception $e) {
	$clearsesh = clear_session($username, $password);
	if ($clearsesh[0] != "Status: OK") {
			logging_err($now, 'clear_session', 0, $closeInfo[1]);
	}
	logging_err($now,'Error: ',$e->errno,$e->error);
}	


	
?>
</body>
</html>
