<?php

	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	session_start();
	if(!isset($_SESSION["superAdmin"]) || $_POST)
		header("Location:index.php");

	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "UPDATE `user_role` SET role_id = 3 WHERE user_id = $_POST[id]";
	$conn->query($sql);
	echo $conn->error;
	$conn->close();
	header("Location:maintenance.php");
?>