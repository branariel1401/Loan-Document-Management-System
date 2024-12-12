<?php
	$hostname="localhost";
	$username="webuser";
	$password="MlcHfBaRSsw-oNmR";
	$db="doc_management4743";

	$dblink=new mysqli($hostname,$username,$password,$db);
	if(!$dblink) 
	{
		echo "Connection lol";
	}
	$dblink->close();
?>