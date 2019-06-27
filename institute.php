<?php

	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");
	session_start();

	#Define constant variable to store attribute of mysql server
	define("SERVER", "localhost");
	define("USER","root");
	define("PASS","");
	define("DB","college_portal");

	#Store the url of the page
	$self = htmlspecialchars($_SERVER['PHP_SELF']);

	#Declare two array to store location and course respectively 
	$location = $course = array();

	#Create a connection to database to get the state 
	$conn = new mysqli(SERVER, USER, PASS, DB);

	#Close the page if unable to create connection
	if($conn->connect_error)
		die ("Connection Failed".$conn->connect_error);

	#SQL command to get the state from database
	$sql = "Select * FROM state";

	#Check whether the query is valid.
	#Return state_id and state_name
	if($result = $conn->query($sql))
	{
		while($state = $result->fetch_assoc())
			array_push($location, $state['state_name']);
	}
	else
		echo "Error. SQL execute failed.".$conn->error;   

	$conn->close();

	#Create a connection to database to get course
	$conn = new mysqli(SERVER, USER, PASS, DB);

	#Close the page if unable to create connection
	if($conn->connect_error)
		die ("Connection Failed".$conn->connect_error);

	#SQL command to get the course information from database
	$sql = "Select * FROM course";

	#Check whether the query is valid.
	#Return course name, course id, fee and duration
	if($result = $conn->query($sql))
	{
		while($courseName = $result->fetch_assoc())
			array_push($course, $courseName['course_name']);
	}
	else
		echo "Error. SQL execute failed.".$conn->error;   

	$conn->close();

	if(isset($_GET['id'])|| isset($_POST['instituteID']))
		require('instituteDetail.php');
	else							
		require('instituteList.php');
?>