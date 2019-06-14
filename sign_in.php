<?php
	session_start();
	if(isset($_SESSION['user']))
	{
		unset($_SESSION['user']);
		header("Location:index.php");
	}

	$errorMessage;
	$errorMessage = array_fill(0,2,"");
	class User
	{
		private $username;
		private $password;
		const server = "localhost";
		const user = "root";
		const pwd = "";
		const db = "college_portal";

		public function getUsername(){return $this->username;}
		public function getPassword(){return $this->password;}
		public function setUsername($username){$this->username = $username;}
		public function setPassword($password){$this->password = $password;} 	
		public function __constructor()
		{
			$this->$username = "";
			$this->$password = "";
		}

		public function validateData()	
		{
			$conn = new mysqli(self::server, self::user, self::pwd, self::db);
			if($conn->connect_error)
			{
				die("Connection fail". $conn->connect_error);
			}
			
			$sql = "CALL AuthenticateUser(\"$this->username\")";
			$result = $conn->query($sql);

			if($result->num_rows == 0)
			{
				global $errorMessage;
				$errorMessage[0] = "Invalid Username.";
			}
			else 
			{
				$selectedUser = $result->fetch_assoc();
				global $errorMessage;

				if($selectedUser['user_name'] == $this->username && $selectedUser['pwd'] == $this->password)
				{
					$_SESSION['user'] = $this->username;
					echo "<script>";
						echo "alert('Login Successfully');";
						echo "window.location.replace(\"index.php\");";
					echo "</script>";
				}
				else
					$errorMessage[1] = "Invalid password";
			}
		}
	}

	$user = new User();
	$self = htmlspecialchars("$_SERVER[PHP_SELF]");
	
	if($_POST)
	{
		$user->setUsername(htmlspecialchars(strtolower($_POST["username"])));
		$user->setPassword(htmlspecialchars($_POST["pwd"]));
		$user->validateData();
	}

	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";
	echo <<< BODY
		<body>
			<div id='intro' class='view' height=100% width='100%'>
				<div class='mask rgba-black-strong'>
					<div class='container d-flex align-items-center justify-content-center h-100'>
						<div class='col-md-5'>
							<div class ='jumbotron card card-image signin-jumbotron my-0'>
								<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>
							</div>

							<div class='bg-light scrollbar' id='style-1' style='height:50vh; max-height:350px'>
								<form class='px-5 pb-3' action='$self' method='post'>
									<div class='md-form'>
										<i class='fa fa-envelope prefix purple-text'></i>
										<input mdbActive class='form-control pl-2' type='text' name='username' value='{$user->getUsername()}' required autocomplete='off' placeholder='Username'/>
										<div class="text-danger ml-5">$errorMessage[0]</div>
									</div>
									<div class='md-form'>
										<i class='fas fa-key prefix purple-text'></i>
										<input mdbActive class='form-control pl-2' type='password' name='pwd' value='{$user->getPassword()}' required autocomplete='off' placeholder='Password'/>
										<div class="text-danger ml-5">$errorMessage[1]</div>
									</div>
									<div class='d-flex justify-content-around'>
										<div>
											<div class='custom-control custom-checkbox'>
												<input type='checkbox' class='custom-control-input' id='defaultLoginFormRemember'>
												<label class='custom-control-label' for='defaultLoginFormRemember'>Remember me</label>
											</div>
										</div>
										<div>
											<a href=''>Forgot password?</a>
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>
BODY;
?>