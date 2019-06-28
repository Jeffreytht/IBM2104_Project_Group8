<?php

	#Define constant variable to store attribute of mysql server
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");
	session_start();

	#Check whether user is super admin and is post request
	if(!isset($_SESSION["superAdmin"]) || $_POST)
		header("Location:index.php");

	#Create a connection to database to update the user role
	$conn = new mysqli(SERVER,USER,PASS,DB);

	if($conn->connect_error)
		die("Conenction failed". $conn->connect_error);

	$id = $conn->real_escape_string($_POST["id"]);

	$sql = "UPDATE `user_role` SET role_id = 3 WHERE user_id = $id";

	#Check whether the query is valid
	if(!($conn->query($sql)))
		echo "Error. SQL execute failed.".$conn->error;

	$conn->close();

	#Redirect to previous page
	header("Location:maintenance.php");
?>