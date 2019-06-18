<?php
	require("models/users.php");
	require("models/normalUser.php");
	require("models/admin.php");
	require("models/superadmin.php");
	session_start();
	echo "<!DOCTYPE html>";
		echo "<html lang='en' class='h-100'>";
			echo "<head>";
				include("header.html");	
				echo "<script src='style/style.js'></script>";					
			echo "</head>";
			echo "<body class='bg-light h-100'>";
				include("nav.php");
				$self = htmlspecialchars($_SERVER['PHP_SELF']);
				
echo <<<BODY
			<main class="main">


			</main>

BODY;

				include("footer.php");
			echo "</body>";
	echo "</html>";