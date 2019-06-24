<?php
	if(!isset($_SESSION['superAdmin']) && $_POST)
		header("index.php");
	
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	$conn = new mysqli(SERVER, USER, PASS, DB);
	$sql = "CALL DeleteInstituteByID($_POST[id])";
	//$result = $conn->query($sql);
	$conn->close();
	header("Location:maintenance.php");
?>