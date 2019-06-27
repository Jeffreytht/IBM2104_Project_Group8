<?php

	#Check whether user is super admin and is post request
	if(!isset($_SESSION['superAdmin']) && $_POST)
		header("index.php");
	
	#Define constant variable to store attribute of mysql server
	define("SERVER", "localhost");
	define("USER","root");
	define("PASS","");
	define("DB","college_portal");

	#Create a connection to database to delete the institute
	$conn = new mysqli(SERVER, USER, PASS, DB);

	if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

	#SQL command that store the stored procedure in database
	$sql = "CALL DeleteInstituteByID($_POST[id])";

	if(!($conn->query($sql)))
		echo "Error. SQL execute failed.".$conn->error;   
	
	$conn->close();

	#Redirect to previous page
	header("Location:maintenance.php");
?>