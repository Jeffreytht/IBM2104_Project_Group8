<?php
	session_start();
	unset($_SESSION['user']);
	unset($_SESSION['admin']);
	unset($_SESSION['superAdmin']);
	header("Location:index.php");
?>