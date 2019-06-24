<?php

	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	session_start();
	if(!isset($_SESSION["superAdmin"]) || !$_POST)
		header("Location:index.php");

	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "CALL DeleteUser($_POST[id])";
	$conn->query($sql);
	$conn->close();
	header("Location:maintenance.php");
?>