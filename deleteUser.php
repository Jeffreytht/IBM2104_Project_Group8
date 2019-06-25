<?php

	#Define constant variable to store attribute of mysql server
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");
	session_start();

	#Check whether user is super admin and is post request
	if(!isset($_SESSION["superAdmin"]) || !$_POST)
		header("Location:index.php");

	#Create a connection to database to delete user
	$conn = new mysqli(SERVER,USER,PASS,DB);

	if($conn->connect_error)
		die ("Connection Failed".$conn->connect_error);

	#Stored procedure in database
	$sql = "CALL DeleteUser($_POST[id])";
	
	if(!($conn->query($sql)))
		echo "Error. SQL execute failed.".$conn->error;   
	
	$conn->close();
	header("Location:maintenance.php");
?>