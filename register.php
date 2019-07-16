<?php
	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");

	#Import PHPMailer classes into the global namespace
	#These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	#Load Composer's autoloader
	require 'vendor/autoload.php';

	set_time_limit(0);

	#Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);
	session_start();

	#Redirect user to homepage if user had sign in already
	if(isset($_SESSION['user']) || isset($_SESSION['admin'])|| isset($_SESSION['superAdmin']))
		header("Location: index.php");

	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include('header.html');
		echo "</head>";

	#Define constant variable to store attribute of mysql server
	define("SERVER", "localhost");
	define("USER","root");
	define("PASS","");
	define("DB","college_portal");

	#Error message of register input
	$errorMessage = array();
	$errorMessage = array_fill(0,6,"");

	#Create a temporary normal user object to store user's information
	$user = new NormalUser();

	#Store the url of the page
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);

	$randomNum = "";
	$tacCode = "";
	$is_valid = TRUE;

	#Validate registration input 
	function validateData()
	{
		#Access global variable
		global $errorMessage;
		global $user;
		global $randomNum;
		global $is_valid;

		#Indicate whether all the input are valid
		$is_valid = TRUE;
		$errorMessage = array_fill(0,6,"");
		
		#Call the object method to validate input
		$user->validateName($is_valid, 0);
		$user->validateEmail($is_valid, 1);
		$user->validatePwd($is_valid, 2);
		$user->validateRetypePwd($is_valid,3);
		$user->validateDob($is_valid, 4);

		#Register successfully if all the input are valid
		if($is_valid)
		{
			$conn = new mysqli(SERVER,USER,PASS,DB);

			if($conn->connect_error)
				die("Connection error. ". $conn->connect_error);

			$sql = "SELECT * FROM `taccode` WHERE user_name = \"{$user->getUsername()}\"";
			$result = $conn->query($sql);
			$tacCode = $result->fetch_assoc()['code'];

			if(isset($_POST['tacCode']))
				$_POST['tacCode'] = $conn->real_escape_string($_POST['tacCode']);

			$conn->close();


			if(isset($_POST['tacCode']) && $_POST['tacCode'] == $tacCode)
			{
				$conn = new mysqli(SERVER, USER, PASS, DB);
				if($conn->connect_error)
					die("Connection Error. ".$conn->connect_error());
				
				$sql = "DELETE FROM `taccode` WHERE user_name = \"{$user->getUsername()}\"";
				$conn->query($sql);
				$conn->close();
				saveResult();
			}

			else if(isset($_POST['tacCode']))
			{
				$conn = new mysqli(SERVER, USER, PASS, DB);
				if($conn->connect_error)
					die("Connection Error. ".$conn->connect_error());
				$sql = "DELETE FROM `taccode` WHERE user_name = \"{$user->getUsername()}\"";
				$conn->query($sql);
				$conn->close();
				$tacCode = "";

				if(!isset($_POST['resend']))
					$errorMessage[5] = "Incorrect Tac Code! A new tac code has been send.";
			}

			if(empty($tacCode) && $_POST)
			{	
				for($i = 0 ; $i < 6 ; $i++)
					$randomNum .= rand(0,9);

				$conn = new mysqli(SERVER, USER, PASS, DB);
				if($conn->connect_error)
					die("Connection Error. ".$conn->connect_error());

				$sql = "INSERT INTO `taccode` VALUES(\"{$user->getUsername()}\", \"$randomNum\")";

				if(!($conn->query($sql)))
					die("Error. ".$conn->connect_error());

				$conn->close();

				try {
				    //Server settings
				    global $mail;
				    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
				    $mail->isSMTP();                                            // Set mailer to use SMTP
				    $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				    $mail->Username   = 'godsfuture99@gmail.com';                     // SMTP username
				    $mail->Password   = 'ixrkvxvogxmtkkts';                               // SMTP password
				    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
				    $mail->Port       = 465;                                    // TCP port to connect to

				    //Recipients
				    $mail->setFrom('godsfuture99@gmail.com', 'Gods');
				    $mail->addAddress($user->getEmail(), $user->getUsername());     // Add a recipient

				    // Content
				    $mail->isHTML(true);                                  // Set email format to HTML
				    $mail->Subject = 'Verification';
				    $mail->Body    = 'Your tac code is "'.$randomNum.'"';
				    $mail->send();
				} 
				catch (Exception $e) 
				{
				    die("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
				}
			}
			
		}
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

		$username = $conn->real_escape_string($_POST["username"]);
		$email = $conn->real_escape_string($_POST["email"]);
		$pwd = $conn->real_escape_string($_POST["pwd"]);
		$dob = $conn->real_escape_string($_POST["dob"]);

		#SQL command to call the stored procedure in database.
		$sql = "CALL InsertUser(\"$username\", \"$email\", \"$pwd\", \"$dob\");";

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
							echo "$('document').ready(function(){
								swal(
								  'Good job!',
								  'Register Successfully!',
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
		{
			echo "Error. SQL execute failed.".$conn->error;   
			$conn->close();
		}
	}

	#Run if post request
	if($_POST)
	{
		#Assign user's input into temporary user object
		$user->setUsername(strtolower($_POST['username']));
		$user->setEmail(strtolower($_POST['email']));
		$user->setPwd($_POST['pwd']);
		$user->setRetypePwd($_POST['retypePwd']);
		$user->setDob($_POST['dob']);

		#validate user's input
		validateData();
	}
	
/********************************* GENERATE VIEW **************************************/
if($_POST && $is_valid)
$tacCode = <<<TACCODE
	<div class='md-form'>
		<i class='fa fa-envelope prefix purple-text'></i>
		<input mdbActive class='form-control pl-2' type='text' name='tacCode' autocomplete='off' placeholder='TAC code' />
		<div class="ml-5">A tac code has been sent to {$user->getEmail()}</div>
		<button class="ml-5 btn btn-info py-1 px-2" id="resend" onclick="addInput()">Resend</button>
		<script>
			function addInput()
			{
				$("#resend").parent().append("<input type='text' hidden value='true' name='resend' />");
			}
		</script>
		<div class='text-danger ml-5'>$errorMessage[5]</div>
	</div>
TACCODE;

$body =<<<BODY
		<body>
			<div id='intro' class='view'>
				<div class='mask rgba-black-strong'>
					<div class='container d-flex align-items-center justify-content-center h-100'>
						<div class='mt-3 pt-5 pb-5 col-md-5'>

<!--START REGISTER FORM HEADER-->
							<div class ='jumbotron card card-image signin-jumbotron my-0'>
								<div class="w3-display-topright w3-padding-large">
								<a href='sign_in.php'>
								<i class="fas fa-arrow-left fa-3x"></i>
								</a>
								</div>
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
									$tacCode
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
		echo $body;
	echo "</html>";
?>
