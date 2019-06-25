<?php

	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");
	session_start();
	
	#Redirect user to homepage if user had sign in already
	if(isset($_SESSION['user']) || isset($_SESSION['admin'])|| isset($_SESSION['superAdmin']))
		header("Location: index.php");

	#Define constant variable to store attribute of mysql server
	define("SERVER", "localhost");
	define("USER","root");
	define("PASS","");
	define("DB","college_portal");

	#Error message of register input
	$errorMessage = array();
	$errorMessage = array_fill(0,5,"");

	#Create a temporary normal user object to store user's information
	$user = new NormalUser();

	#Store the url of the page
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);

	#Validate registration input 
	function validateData()
	{
		#Access global variable
		global $errorMessage;
		global $user;

		#Indicate whether all the input are valid
		$is_valid = TRUE;
		$errorMessage = array_fill(0,5,"");
		
		#Call the object method to validate input
		$user->validateName($is_valid, 0);
		$user->validateEmail($is_valid, 1);
		$user->validatePwd($is_valid, 2);
		$user->validateRetypePwd($is_valid,3);
		$user->validateDob($is_valid, 4);

		#Register successfully if all the input are valid
		if($is_valid)
			saveResult();
	}

	#Insert valid user input into database
	function saveResult()
	{
		#Access global variable
		global $user;

		#Create a connection to mysql database.
		$conn = new mysqli(SERVER,USER,PASS,DB);
		
		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		#SQL command to call the stored procedure in database.
		$sql = "CALL InsertUser(\"$_POST[username]\", \"$_POST[email]\", \"$_POST[pwd]\", \"$_POST[dob]\");";

		#Register successfully and insert user's information into database
		if($result = $conn->query($sql))
		{
			$conn->close();

			#Create connection with mysql database and get all the user information
			$conn = new mysqli(SERVER,USER,PASS,DB);
			$sql = "CALL SelectAllUserDetailsByUsername(\"$_POST[username]\")";

			#Select all user's information in database and store in session
		    if($result = $conn->query($sql))
		    {
		    	$output = $result->fetch_assoc();

		    	#Create base object base on the role id 
			    switch($output['role_id'])
			    {
			    	case 1:
			    		#Create a super admin object and assign user's information
			    		$superAdmin = new SuperAdmin();
			    		$SuperAdmin->assginUser($output);

			    		#Store super admin object in session
			    		$_SESSION['superAdmin'] = $superAdmin;
			    		$_SESSION['role'] = $output['role_id'];

			    	break;

			    	case 2:
			    		#Create an admin object and assign user's information
			    		$admin = new Admin();
			    		$admin->assginUser($output);

			    		#Store admin object in session
			    		$_SESSION['admin'] = $admin;
			    		$_SESSION['role'] = $output['role_id'];

			    	break;

			    	case 3:
			    		#Create an normal user object and assign user's information
			    		$normalUser = new NormalUser();
			   			$normalUser->assignUser($output);

			   			#Store normal user object in session
			    		$_SESSION['user'] = $normalUser;
			    		$_SESSION['role'] = $output['role_id'];
			    	break;
			    }

			    #Redirect to homepage
			    echo "<script>";
				echo "alert('Register Successfully');";
				echo "window.location.replace(\"index.php\");";
				echo "</script>";
		    }
		    else
		    {
		    	echo "Error. SQL execute failed.".$conn->error;   
				$conn->close();
		    }   
		}
		else
		{
			echo "Error. SQL execute failed.".$conn->error;   
			$conn->close();
		}
	}

	#Run if post request
	if($_POST)
	{
		#Assign user's input into temporary user object
		$user->setUsername(htmlspecialchars(strtolower($_POST['username'])));
		$user->setEmail(htmlspecialchars(strtolower($_POST['email'])));
		$user->setPwd(htmlspecialchars($_POST['pwd']));
		$user->setRetypePwd(htmlspecialchars($_POST['retypePwd']));
		$user->setDob(htmlspecialchars($_POST['dob']));

		#validate user's input
		validateData();
	}
	
/********************************* GENERATE VIEW **************************************/

$body =<<<BODY
		<body>
			<div id='intro' class='view'>
				<div class='mask rgba-black-strong'>
					<div class='container d-flex align-items-center justify-content-center h-100'>
						<div class='mt-3 pt-5 pb-5 col-md-5'>

<!--START REGISTER FORM HEADER-->
							<div class ='jumbotron card card-image signin-jumbotron my-0'>
								<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>
							</div>
<!--END REGISTER FORM HEADER-->

							<div class='scrollbar bg-light' id='style-1' style='height:50vh; max-height:450px'>

<!--START REGISTER FORM-->
								<form class='px-5' method='post' action = "$self">				
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
<!--END REGISTER FORM-->

							</div>
						</div>
					</div>
				</div>
			</div>
		</body>
BODY;

/***************************************************VIEW**********************************/
echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include('header.html');
		echo "</head>";
		echo $body;
	echo "</html>";
?>