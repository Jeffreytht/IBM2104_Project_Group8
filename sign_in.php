<?php
	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	session_start();

	#Define constant variable to store attribute of mysql server
	define("SERVER", "localhost");
	define("USER","root");
	define("PASS","");
	define("DB","college_portal");

	#Redirect user to homepage if user had sign in already
	if(isset($_SESSION['user']) || isset($_SESSION['admin'])|| isset($_SESSION['superAdmin']))
		header("Location:index.php");

	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";

	#Error message of sign in input
	$errorMessage = array();
	$errorMessage = array_fill(0,2,"");

	#Create a temporary normal user object to store user's information
	$user = new NormalUser();

	if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
	{
		$user->setUsername($_COOKIE['username']);
		$user->setPwd($_COOKIE['password']);
	}

	#Store the url of the page
	$self = htmlspecialchars("$_SERVER[PHP_SELF]");
	
	#Run the code if post request
	if($_POST)
	{
		#Store user's input into temporary normal user object
		if(isset($_POST["username"]) && isset($_POST["username"]))
		{
			$user->setUsername(strtolower($_POST["username"]));
			$user->setPwd($_POST["pwd"]);
		}

		#Create a connection with mysql database
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die("Connection fail". $conn->connect_error);

		$username = $conn->real_escape_string($user->getUsername());
		
		#SQL command to call the stored procedure in database
		$sql = "CALL AuthenticateUser(\"$username\")";

		#Check whether the query is valid
		#Return username and password
		if($result = $conn->query($sql))
		{
			$conn->close();

			#Assign proper error message if username is not found
			if($result->num_rows == 0)
				$errorMessage[0] = "Invalid Username.";
			
			else 
			{
				#Fetch the sql result from database
				$output = $result->fetch_assoc();

				#Authenticate username and password
				if($output['user_name'] == $user->getUsername() && $output['pwd'] == $user->getPwd())
				{
					#Create connection with mysql database and get all the user information
					$conn = new mysqli(SERVER,USER,PASS,DB);

					#Close the page if unable to create connection
					if($conn->connect_error)
						die("Connection fail". $conn->connect_error);

					#Sql command to call stored procedure in mysql database
				    $sql = "CALL SelectAllUserDetailsByUsername(\"$username\")";

				    #Check whether the query is valid
				    if($result = $conn->query($sql))
				    {
				    	#Return user's personal information and role name
					    $output = $result->fetch_assoc();
					    $conn->close();

					    #Assign role id to session base on the user's role
					    switch($output['role_id'])
					    {
					    	case 1:
					    		#Create a super admin object and assign all user's information
					    		$superAdmin = new SuperAdmin();
					    		$superAdmin->assignUser($output);

					    		#Store the super admin object in session
					    		$_SESSION['superAdmin'] = $superAdmin;
					    		
					    	break;

					    	case 2:
					    		#Create a admin object and assign all user's information
					    		$admin = new Admin();
					    		$admin->assignUser($output);
					    		$admin->assignAdmin();

					    		#Store the admin object in session
					    		$_SESSION['admin'] = $admin;
					    	break;

					    	case 3:
					    		#Create a normal user object and assign all user's information
					    		$normalUser = new NormalUser();
					   			$normalUser->assignUser($output);

					   			#Store the user object in session
					    		$_SESSION['user'] = $normalUser;
					    	break;
					    }


					    #Store role id to session
					    $_SESSION['role'] = $output['role_id'];

					    if(isset($_POST['rmbMe']) && $_POST['rmbMe'] == "yes")
					    {
					    	setcookie("username", $output['user_name']);
					    	setcookie("password", $output['pwd']);
					    }
					    else
					    {
					    	setcookie('username', '');
					    	setcookie('password','');
					    }

					    #Log in successfully and redirect to homepage
						echo "<script>";
							echo "$('document').ready(function(){
								swal(
								  'Good job!',
								  'Sign In Successfully!',
								  'success'
								).then(function(){
									window.location.replace(\"index.php\");
									});
							});";
						echo "</script>";
				    }
				    else
				    {
				    	echo "Error. SQL execute failed.".$conn->error;   
				    	$conn->close();
				    }
				}
				else
					$errorMessage[1] = "Invalid password";
			}
		}
		else
		{
			echo "Error. SQL execute failed.".$conn->error;
			$conn->close();
		}	
	}

/********************************* GENERATE--VIEW ************************************/
$body = 
<<<BODY
		<body>
	<!--START SIGN IN PAGE-->
			<div id='intro' class='view' height=100% width='100%'>
				<div class='mask rgba-black-strong'>
					<div class='container d-flex align-items-center justify-content-center h-100'>
						<div class='col-md-5'>

	<!--SIGN IN FORM HEADER-->
							<div class ='jumbotron card card-image signin-jumbotron my-0'>
								<div class="w3-display-topright w3-padding-large">
								<a href='index.php'>
								<i class="fas fa-times fa-3x"></i>
								</a>
								</div>
								<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>
							</div>
	<!--END SIGN IN FORM HEADER-->

							<div class='bg-light scrollbar' id='style-1' style='height:50vh; max-height:350px'>

	<!--START SIGN IN FORM-->
								<form class='px-5 pb-3' action='$self' method='post'>
									<div class='md-form'>
										<i class='fas fa-user prefix purple-text'></i>
										<input mdbActive class='form-control pl-2' type='text' name='username' value='{$user->getUsername()}' required autocomplete='off' placeholder='Username'/>
										<div class="text-danger ml-5">$errorMessage[0]</div>
									</div>
									<div class='md-form'>
										<i class='fas fa-key prefix purple-text'></i>
										<input mdbActive class='form-control pl-2' type='password' name='pwd' value='{$user->getPwd()}' required autocomplete='off' placeholder='Password'/>
										<div class="text-danger ml-5">$errorMessage[1]</div>
									</div>
									<div class='d-flex justify-content-around'>
										<div>
											<div class='custom-control custom-checkbox'>
												<input type='checkbox' name="rmbMe" value="yes" class='custom-control-input' id='defaultLoginFormRemember'>
												<label class='custom-control-label' for='defaultLoginFormRemember'>Remember me</label>
											</div>
										</div>
										<div>
											<a href='forgetPassword.php'>Forgot password?</a>
										</div>
									</div>
									<div class='text-center'>
										<button type='submit' class='text-white btn blue-gradient col-md-6 my-4'>Sign In</button>
										<p>
											Not a member? 
											<a href='register.php'>Register</a>
										</p>
									</div>
								</form>
	<!--END SIGN IN FORM-->

							</div>
						</div>
					</div>
				</div>
			</div>
	<!--END SIGN IN PAGE-->
		</body>
BODY;
/************************************** VIEW *****************************************/

		echo $body;
	echo "</html>";
?>
