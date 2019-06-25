<?php

	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");
	session_start();

	#Redirect to homepage if user haven't sign in 
	if(!isset($_SESSION['role']))
		header("Location:index.php");
	
	#Store object from session	
	$user;

	#Assign user information base on the role
	switch($_SESSION['role'])
	{
		case 1:			
			$user = $_SESSION['superAdmin'];
			break;

		case 2:
			$user = $_SESSION['admin'];
			break;

		case 3:
			$user = $_SESSION['user'];
			break;
	}

/******************************** GENERATE VIEW ************************************/

	#Number of DOT which indicate length of password
	$maskPwd = "";
	$lengthOfPwd = strlen($user->getPWD());
	for($i = 0 ; $i < $lengthOfPwd; $i++)
		$maskPwd .= "&#8226;";

	$body = <<< BODY
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

/************************************ VIEW ****************************************/
echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body>";
		include("nav.php");
		
		echo $body;

		include("footer.php");
		echo "</body>";
	echo "</html>";
?>