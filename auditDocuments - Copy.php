<?php
include("functions.php");
include("parseErrorLog.php");
date_default_timezone_set('America/Chicago');
$dblink=db_connect("doc_management4743");
auditDocs($dblink);

function auditDocs($dblink){
	$docsRequired= [
		'Credit',
		'Closing',
		'Title',
		'Financial',
		'Personal',
		'Internal',
		'Legal',
		'MOU',
		'Disclosures',
		'Tax_Returns'
	];
	
	try{
		$now=date("Y-m-d H:i:s");
		$sql="Select `file_name` FROM `documents` where processed = 0";
		$result=$dblink->query($sql);
		
		if(!$result) {
			$errorMessage=addslashes("[".$now."] - audit - ".$dblink->errno." - ".$dblink->error);
			throw new Exception($errorMessage);
		}
	} catch(Exception $e) {
		$errorMessage=addslashes("[".$now."] - audit - ".$e->errno." - ".$e->error);
		log_errors($e);
		return;
	}
	
	$loanDocs = [];
	$count = [];
	while($row = $result->fetch_assoc()) {
		$fileName = addslashes($row['file_name']);
		
		$loanNum = substr($fileName, 0, strpos($fileName, '-'));
		$docType = substr($fileName, strpos($fileName, '-') + 1, strpos($fileName, '_') - strpos($fileName, '-') - 1);
		$docExtension = pathinfo($fileName, PATHINFO_EXTENSION);
		
		if($docExtension !== 'pdf') {
			echo "Non-PDF file detected: $fileName. \n";
			continue;
		}
		
		if(!isset($loanDocs[$loanNum])) {
			$loanDocs[$loanNum] = [];	
			$count[$loanNum] = 0;
		}
		
		$loanDocs[$loanNum][] = $docType;
		if($docType === 'Tax_Records') {
			$count[$loanNum]++;
		}
		
		$updateSQL = "Update `documents` SET processed = 1 WHERE file_name = '$fileName'";
		if(!$dblink->query($updateSQL)) {
			$errorMessage=addslashes("[".$now."] - Database - ".$dblink->errno." - ".$dblink->error);
			log_errors($errorMessage);
		}
	}
	
	foreach($loanDocs as $loanNum => $docs) {
		$missingDocs = array_diff($docsRequired, $docs);
		$missingTax = $count[$loanNum] < 2;
		
		$loanComplete=(empty($missingDocs) && !$missingTax) ? 1 : 0;
		
		$sql = "Insert into `documents_type` (`loan_number`,`loan_complete`) values ('$loanNum','$loanComplete')";
		
		if(!$dblink->query($sql)) {
			$errorMessage=addslashes("[".$now."] - Database - ".$dblink->errno." - ".$dblink->error);
			log_errors($errorMessage);
		}
		
		if(empty($missingDocs) && !$missingTax) {
			echo "Loan $loanNum is complete. \n";
		} else {
			echo "Loan $loanNum is incomplete. \n";
			
			if(!empty($missingDocs)) {
				echo " - Missing documents: ".implode(",",$missingDocs)."\n";
			}
			
			if($missingTax) {
				$missCount = 2 - $count[$loanNum];
				echo " - Missing $missCount Tax Records Document(s) \n";
			}
		}
		
	}
}



?>




	