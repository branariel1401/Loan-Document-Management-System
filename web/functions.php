<?php
function db_connect($db){
	$hostname = "localhost";
	$username = "webuser";
	$password = "MlcHfBaRSsw-oNmR";
		
	$dblink = new mysqli($hostname, $username, $password, $db);
		
	if ($dblink->connect_error){
		throw new Exception("Error connecting to database". $dblink->connect_error);
	}
	
	return $dblink;
}

function redirect ($uri)
{
	?>
	<script type="text/javascript">
		document.location.href="<?php echo $uri; ?>";
	</script>
<?php die;
}
?>
?>