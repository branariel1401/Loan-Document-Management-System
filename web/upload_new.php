<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload to New Loan</title>
<!-- BOOTSTRAP STYLES-->
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<!-- FONTAWESOME STYLES-->
<link href="assets/css/font-awesome.css" rel="stylesheet" />
   <!--CUSTOM BASIC STYLES-->
<link href="assets/css/basic.css" rel="stylesheet" />
<!--CUSTOM MAIN STYLES-->
<link href="assets/css/custom.css" rel="stylesheet" />
<!-- PAGE LEVEL STYLES -->
<link href="assets/css/bootstrap-fileupload.min.css" rel="stylesheet" />
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-fileupload.js"></script>
</head>
<body>
<?php
include("functions.php");
include("otherFunctions.php");
$dblink=db_connect("doc_management4743");
echo '<div id="page-inner">';
	echo '<h1 class="page-head-line">Upload a New File to Database</h1>';
	echo '<div class="panel-body">';
	if(isset($_GET['error']) && $_GET['error']=='FileMimeInvalid') {
		echo '<div class="alert alert-danger" role="alert">You must upload PDF ONLY!</div>';
	} elseif ($_GET['error']=='loanNumNull') {
		echo '<div class="alert alert-danger" role="alert">Loan Number cannot be blank.</div>';
	} elseif ($_GET['error']=='loanNumInvalid') {
		echo '<div class="alert alert-danger" role="alert">Loan Number is invalid.</div>';
	}
		echo '<form method="post" enctype="multipart/form-data" action="">';
			echo '<input type="hidden" name="MAX_FILE_SIZE" value="10000000">';
			echo '<div class="form-group">';
				echo '<label for="loanNum" class="control-label">Loan Number</label>';
				echo '<input type="text" name="loanNum" class="form-control">';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="docType" class="control-label">Document Type</label>';
				echo '<select class="form-control" name="docType">';
				$sql = "Select * from `doc_required`";
				$result=$dblink->query($sql) or
					die("Something went wrong with $sql <br>".$dblink->error);
					while($data=$result->fetch_array(MYSQLI_ASSOC))
					{
						echo '<option value="'.$data['auto_id'].'">'.$data['name'].'</option>';
					}
					
				echo '</select>';
				echo '<div class="form-group">';
					echo '<label class="control-label col-lg-4">File Upload</label>';
					echo '<div class="">';
						echo '<div class="fileupload fileupload-new" data-provides="fileupload">';
							echo '<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>';
							echo '<div class="row">';
								echo '<div class="col-md-2">';
									echo '<span class="btn btn-file btn-primary">';
									echo '<span class="fileupload-new">Select File</span>';
									echo '<span class="fileupload-exists">Change</span>';
									echo '<input name="userfile" type="file">';
									echo '</span>';
								echo '</div>';
								echo '<div class="col-md-2">';
									echo '<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<hr>';
				echo '<button type="submit" name="submit" value="submit" class="btn btn-lg btn-block btn-success">Upload File</button>';
			echo '</div>';	
		echo '</form>';
	echo '</div>';
echo '</div>';
if(isset($_POST['submit']) && $_POST['submit']=="submit") {
	$fileMime=$_FILES['userfile']['type'];
	if($fileMime!='application/pdf')
		redirect("upload_new.php?error=FileMimeInvalid");
	else {
		$now=date("Y-m-d H:i:s");
		$nowName=date("Ymd_H_i_s");
		$loanNumber=$_POST['loanNum'];
		if($loanNumber==NULL) {
			redirect("upload_new.php?error=loanNumNull");
		}
		elseif (!preg_match('/^[0-9]{9}$/',$loanNumber)) {
			redirect("upload_new.php?error=loanNumInvalid");
		}
		$docID=$_POST['docType'];
		$sql="Select `name` from `doc_required` where `auto_id`='$docID'";
		$result=$dblink->query($sql) or
			logging_err($now,'Database',$dblink->errno,$dblink->error);
		$tmp=$result->fetch_array(MYSQLI_ASSOC);
		$fileUpload=$_FILES['userfile']['name']; //variable for file upload name
		 //variable for file upload mime type
		$fileContent=$_FILES['userfile']['tmp_name']; //variable for holding file upload contents
		$fileSize=$_FILES['userfile']['size'];
		$fp=fopen($fileContent,"r");
		$contents=fread($fp,filesize($fileContent));
		fclose($fp);
		$contentClean=addslashes($contents);
		$filename=$loanNumber.'-'.str_replace(" ","_",$tmp['name']).'-'.$nowName.".pdf";
		$sql="Insert into `documents` (`file_name`,`file_size`,`date_upload`,`upload_type`,`content`) values ('$fileName','$fileSize','$now','manual','$contentClean')";
		$dblink->query($sql) or
			logging_err($now,'Database',$dblink->errno,$dblink->error);
		echo '<div class="alert alert-success">File successfully uploaded</div>';
		
	}
}

?>
</body>
</html>