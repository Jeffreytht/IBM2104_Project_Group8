<?php

require_once("models/users.php");
require_once("models/normalUser.php");
require_once("models/admin.php");
require_once("models/superadmin.php");
require_once("models/institute.php");
session_start();

if(isset($_SESSION['user'])|| isset($_SESSION['admin']) || isset($_SESSION['superAdmin']))
{

	$user;
	if(isset($_SESSION['user']))
		$user = $_SESSION['user'];

	else if(isset($_SESSION['admin']))
		$user = $_SESSION['admin'];

	else
		$user = $_SESSION['superAdmin'];

echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body>";
		include("nav.php");
		$maskPwd = "";
		for($i = 0 ; $i < strlen($user->getPWD()); $i++)
			$maskPwd .= "&#8226;";

echo <<< BODY
	<main class="main bg-light">
		<div class='container d-flex justify-content-center'>
			<div class='userDetail col-md-6 bg-white py-5 mb-5'>
				<center>
					<div class="bg-white circle-icon d-flex justify-content-center align-items-center">
						<i class="fas fa-user-tie fa-9x"></i>
					</div>
					<h4 class="mt-3">{$user->getUsername()}</h4>
				</center>
				<div class="container ">
					<hr class="col-md-8 my-4 bg-dark" style="height:3px" />
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<h5 class="text-indigo py-2"><i class='fa fa-envelope prefix pr-3'></i>{$user->getEmail()}</h5>
							<h5 class="text-indigo py-2"><i class='fas fa-calendar-day prefix pr-3'></i>{$user->printDate()}</h5>
							<h5 class="text-indigo py-2"><i class='fas fa-key pr-3'></i>$maskPwd</h5>
							<center>
								<a class="btn purple-gradient text-white" href="update_account.php">Update Profile</a> 
							</center>
						</div>
						<div class="col-md-2"></div>
					</div>
				</div>
			</div>
		</div>
	</main>

BODY;
		include("footer.php");
		echo "</body>";
	echo "</html>";
}

else
	header("Location:index.php");
?>