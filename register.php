<?php

	require("models/users.php");
	require("models/normalUser.php");
	require("models/admin.php");
	require("models/superadmin.php");
	require("models/institute.php");
	require("models/news.php");
	session_start();
	startUser(5);

	if(isset($_SESSION['user']) || isset($_SESSION['admin'])|| isset($_SESSION['superAdmin']))
		header("Location: index.php");

	define("SERVER", "localhost");
	define("USER","root");
	define("PASS","");
	define("DB","college_portal");

	$user = new NormalUser();
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);

	function validateData()
	{
		global $errorMessage;
		global $user;

		$is_valid = TRUE;
		$errorMessage = array_fill(0,5,"");
		
		$user->validateName($is_valid, 0);
		$user->validateEmail($is_valid, 1);
		$user->validatePwd($is_valid, 2);
		$user->validateRetypePwd($is_valid,3);
		$user->validateDob($is_valid, 4);

		if($is_valid)
			saveResult();
	}

	function saveResult()
	{
		global $user;
		$conn = new mysqli(SERVER,USER,PASS,DB);
		
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$sql = "CALL InsertUser(\"$data[0]\", \"$data[1]\", \"$data[2]\", \"$data[3]\");";

		if($conn->query($sql)===TRUE)
		{
			$sql = "CALL SelectAllUserDetails(\"$data[0]\")";
		    $result = $conn->query($sql);
		    $userDetail = $result->fetch_assoc();

		    switch($userDetail['role_id'])
		    {
		    	case 1:
		    		$superAdmin = new SuperAdmin();
		    		$SuperAdmin->assginUser($userDetail);
		    		$_SESSION['superAdmin'] = $admin;
		    		$_SESSION['role'] = $userDetail['role_id'];
		    	break;

		    	case 2:
		    		$admin = new Admin();
		    		$admin->assginUser($userDetail);
		    		$_SESSION['admin'] = $admin;
		    		$_SESSION['role'] = $userDetail['role_id'];
		    	break;

		    	case 3:
		    		$normalUser = new NormalUser();
		   			$normalUser->assignUser($userDetail);
		    		$_SESSION['user'] = $normalUser;
		    		$_SESSION['role'] = $userDetail['role_id'];
		    	break;
		    }

		    echo "<script>";
				echo "alert('Register Successfully');";
				echo "window.location.replace(\"index.php\");";
			echo "</script>";
		}
		else
			echo "Error".$conn->error;

		$conn->close();
	}

	
	
	if($_POST)
	{
		global $user;
		$user->setUsername(htmlspecialchars(strtolower($_POST['username'])));
		$user->setEmail(htmlspecialchars(strtolower($_POST['email'])));
		$user->setPwd(htmlspecialchars($_POST['pwd']));
		$user->setRetypePwd(htmlspecialchars($_POST['retypePwd']));
		$user->setDob(htmlspecialchars($_POST['dob']));
		validateData();
	}
	

echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";

include('header.html');
echo "</head>";

echo <<<BODY
		<body>
		<div id='intro' class='view'>
			<div class='mask rgba-black-strong'>
				<div class='container d-flex align-items-center justify-content-center h-100'>
					<div class='mt-3 pt-5 pb-5 col-md-5'>
						<div class ='jumbotron card card-image signin-jumbotron my-0'>
							<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>
						</div>
						<div class='scrollbar bg-light' id='style-1' style='height:50vh; max-height:450px'>
							<form class='px-5' method='post' action = "$_SERVER[PHP_SELF]">				
								<div class='md-form'>
									<i class='fas fa-user prefix purple-text'></i>
									<input mdbActive class='form-control pl-2' type='text' name='username' required autocomplete='off' placeholder='Username' value ='{$user->getUsername()}'/>
									<div class='text-danger ml-5'>$errorMessage[0]</div>
								</div>
								<div class='md-form'>
									<i class='fa fa-envelope prefix purple-text'></i>
									<input mdbActive class='form-control pl-2' type='email' name='email' required autocomplete='off' placeholder='Email' value='{$user->getEmail()}'/>
									<div class='text-danger ml-5'>$errorMessage[1]</div>
								</div>
								<div class='md-form'>
									<i class='fas fa-key prefix purple-text'></i>
									<input mdbActive class='form-control pl-2' type='password' name='pwd' required autocomplete='off' placeholder='Password' value='{$user->getPwd()}'/>
									<div class='text-danger ml-5'>$errorMessage[2]</div>
								</div>
								<div class='md-form'>
									<i class='fas fa-key prefix purple-text'></i>
									<input mdbActive class='form-control pl-2' type='password' name='retypePwd' required autocomplete='off' placeholder='Retype Password' value='{$user->getRetypePwd()}'/>
									<div class='text-danger ml-5'>$errorMessage[3]</div>
								</div>
								<div class='md-form'>
									<i class='fas fa-calendar-day prefix purple-text'></i>
									<input type='text'class='form-control pl-2' name='dob' onfocus="(this.type='date')" onblur="(this.type='text')" required autocomplete='off' placeholder='Date Of Birth' value='{$user->getDob()}'/>
									<div class='text-danger ml-5'>$errorMessage[4]</div>
								</div>

								<div class='text-center'>
									<button type='submit' class='text-white btn blue-gradient col-md-6 my-4'>Register</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		</body>
	</html>
BODY;
?>