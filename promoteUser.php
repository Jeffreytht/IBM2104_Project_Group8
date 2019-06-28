<?php

	#Define constant variable to store attribute of mysql server
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");
	session_start();

	#Check whether the user is super admin and is post request
	if(!isset($_SESSION["superAdmin"]) || !$_POST)
		header("Location:index.php");

	

	#Create a connection to database to update user role
	$conn = new mysqli(SERVER,USER,PASS,DB);
	$id = $conn->real_escape_string($_POST['id']);
	$sql = "UPDATE `user_role` SET role_id = 2 WHERE user_id = $id";

	if(!($conn->query($sql)))
		echo "Error. SQL execute failed.".$conn->error;

	$conn->close();

	#Redirect to previous page
	header("Location:maintenance.php");
?>