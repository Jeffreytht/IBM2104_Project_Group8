<?php

require("models/users.php");
session_start();
$errorMessage;
$errorMessage = array_fill(0,5,"");
$wtf = "";

if($_POST)
{
	$conn = mysqli_connect("localhost","root","","college_portal");
	$user = new User();
	
	$userID = $_SESSION['user']->getUserID();
	$is_valid = TRUE;

	$user->setDob($_POST['dob']);
	$user->validateDob($is_Valid);

	if(empty($_POST['pwd1']) && empty(($_POST['pwd2'])) && empty($_POST['pwd3']))
	{
		$wtf .= "1" .$_POST['pwd1'];
		$user->setPwd($_SESSION['user']->getPwd());
	}
	else
	{
		$wtf .= "2";

		if($_POST['pwd1'] != $_SESSION['user']->getPwd())
		{
			$errorMessage[3] = "Invalid password";
			$is_valid = FALSE;
		}

		else if($_POST['pwd1'] == $_POST['pwd2'])
		{
			$errorMessage[2] = "New password must be different from old password";
			$is_valid = FALSE;
		}

		else if($_POST['pwd2'] != $_POST['pwd3'])
		{
			$errorMessage[2] = "New password and retype new password must not be different";
			$is_valid = FALSE;
		}

		else
		{
			$user->setPwd($_POST['pwd2']);
			$user->validatePwd($is_valid,FALSE);
		}
	}

	if($is_valid)
	{
		$wtf .= "3";
		$sql = "CALL UpdateUser(\"$userID\", \"{$user->getDob()}\",\"{$user->getPwd()}\")";
		if($conn->query($sql)=== TRUE)
		{
			$_SESSION['user']->setPwd($user->getPwd());
			$_SESSION['user']->setDob($user->getDob());
		}
		header("Location:my_account.php");
	}
}


if(isset($_SESSION['user']))
{

$self = htmlspecialchars($_SERVER['PHP_SELF']);
echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
			echo "<script src='style/uptAcc.js'></script>";
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
					<hr class="col-md-9 my-5 bg-dark" style="height:2px" />
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<form action="$self" method='post'>
								<div>
									<h4>Change Date Of Birth</h4>
									<div class='md-form'>
										<i class='fas fa-calendar-day prefix purple-text'></i>
										<input type='text'class='form-control pl-2' name='dob' onfocus="(this.type='date')" onblur="(this.type='text')" autocomplete='off' id="dob" placeholder='Date Of Birth' value='{$_SESSION['user']->getDob()}'/>
											<div class='text-danger ml-5'>$errorMessage[4]</div>
									</div>
								</div>
								<div class="mt-5">
									<h4>Change Password</h4>
									<div class='md-form'>
										<i class='fas fa-key prefix purple-text'></i>
										<input mdbActive class='form-control pl-2 pwd' id ='oldPwd' type='password' name='pwd1' autocomplete='off' placeholder='Old Password' value='$_POST[pwd1]' />
											<div class='text-danger ml-5'>$errorMessage[3]</div>
									</div>
									<div class='md-form'>
										<i class='fas fa-key prefix purple-text'></i>
										<input mdbActive class='form-control pl-2 pwd' id ='newPwd' type='password' name='pwd2' autocomplete='off' placeholder='New Password' value='$_POST[pwd2]'/>
										<div class='text-danger ml-5'>$errorMessage[2]</div>
									</div>
									<div class='md-form'>
										<i class='fas fa-key prefix purple-text'></i>
										<input mdbActive class='form-control pl-2 pwd' id ='newRePwd'type='password' name='pwd3' autocomplete='off' placeholder='Retype New Password' />	
									</div>
									<center class="pt-4">
										<button class="btn purple-gradient text-white" id="scBtn" disabled>Save Changes</button> 
									</center>
								</div>
							</form>
						</div>
						<div class="col-md-1"></div>
				</div>
			</div>
		</div>
	</main>

BODY;
echo $wtf;
		include("footer.php");
		echo "</body>";
	echo "</html>";
}

else
	header("Location:index.php");
?>