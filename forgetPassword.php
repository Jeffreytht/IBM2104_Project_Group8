<?php

	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	session_start();

	#Import PHPMailer classes into the global namespace
	#These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	#Load Composer's autoloader
	require 'vendor/autoload.php';

	#Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	#Define constant variable to store attribute of mysql server
	define("SERVER", "localhost");
	define("USER","root");
	define("PASS","");
	define("DB","college_portal");

	#Redirect user to homepage if user had sign in already
	if(isset($_SESSION['user']) || isset($_SESSION['admin'])|| isset($_SESSION['superAdmin']))
		header("Location:index.php");

	#Error message of sign in input
	$errorMessage = array();
	$errorMessage = array_fill(0,1,"");

	#Create a temporary normal user object to store user's information
	$user = new NormalUser();

	#Store the url of the page
	$self = htmlspecialchars("$_SERVER[PHP_SELF]");
	
	#Run the code if post request
	if($_POST)
	{
		#Store user's input into temporary normal user object
		if(isset($_POST["username"]))
		{
			$user->setUsername(strtolower($_POST["username"]));
		}

		#Create a connection with mysql database
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die("Connection fail". $conn->connect_error);

		$username = $conn->real_escape_string($user->getUsername());
		
		#SQL command to call the stored procedure in database
		$sql = "SELECT *
				FROM users
				WHERE user_name = \"$username\"";

		if($result = $conn->query($sql))
		{
			if($result->num_rows == 0)
			{
				$errorMessage[0] = "Invalid username";
			}
			else
			{
				$output = $result->fetch_assoc();
				$email = $output['email'];
				$password = $output['pwd'];
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
				    $mail->addAddress("$email", $user->getUsername());     // Add a recipient

				    // Content
				    $mail->isHTML(true);                                  // Set email format to HTML
				    $mail->Subject = 'Password Recovery';
				    $mail->Body    = "Your password: ".$password;
				    $mail->send();

				    echo "<script>alert('Recovery password is sent to $email')</script>";
				    echo "<script>window.location.replace(\"sign_in.php\")</script>";
				} 
				catch (Exception $e) 
				{
				    die("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
				}
			}
		}
		else
		{
			echo "Error. SQL execute failed. ".$conn->error;
		}

		$conn->close();
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
							<a href='sign_in.php'>
							<i class="fas fa-arrow-left fa-3x"></i>
							</a>
							</div>
								<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>
							</div>
	<!--END SIGN IN FORM HEADER-->

							<div class='bg-light scrollbar' id='style-1' style='max-height:350px'>

	<!--START SIGN IN FORM-->
								<form class='px-5 pb-3' action='$self' method='post'>
									<div class='md-form'>
										<i class='fas fa-envelope prefix purple-text'></i>
										<input mdbActive class='form-control pl-2' type='text' required name='username' value='{$user->getUsername()}' required autocomplete='off' placeholder='Username'/>
										<div class="text-danger ml-5">$errorMessage[0]</div>
									</div>
									<div class='text-center'>
										<button type='submit' class='text-white btn blue-gradient my-4'>Find Password</button>
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
	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";
		echo $body;
	echo "</html>";
?>
