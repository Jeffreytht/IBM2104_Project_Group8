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

	$id = $conn->real_escape_string($_POST["id"]);
	
	#Stored procedure in database
	$sql = "CALL DeleteUser($id)";
	
	if(!($conn->query($sql)))
		echo "Error. SQL execute failed.".$conn->error;   
	
	$conn->close();
	header("Location:maintenance.php");
?>