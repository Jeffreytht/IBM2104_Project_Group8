<?php

require("models/users.php");
session_start();


if(isset($_SESSION['user']))
{

echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body>";
		include("nav.php");
		$maskPwd = "";
		for($i = 0 ; $i < strlen($_SESSION['user']->getPWD()); $i++)
			$maskPwd .= "&#8226;";

echo <<< BODY
	<main class="main bg-light">
		<div class='container d-flex justify-content-center'>
			<div class='userDetail col-md-6 bg-white pt-5'>
				<center>
					<div class="bg-white circle-icon d-flex justify-content-center align-items-center">
						<i class="fas fa-user-tie fa-9x"></i>
					</div>
					<h4 class="mt-3">{$_SESSION['user']->getUsername()}</h4>
				</center>
				<div class="container mb-4">
				<hr class="col-md-8 my-4 bg-dark" style="height:3px" />
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<h5 class="text-indigo py-2"><i class='fa fa-envelope prefix pr-3'></i>{$_SESSION['user']->getEmail()}</h5>
							<h5 class="text-indigo py-2"><i class='fas fa-calendar-day prefix pr-3'></i>{$_SESSION['user']->printDate()}</h5>
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