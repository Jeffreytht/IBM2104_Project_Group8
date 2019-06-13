<?php
	$errorMessage;
	$errorMessage = array_fill(0,2,"");
	class User
	{
		private $username;
		private $email;
		private $pwd;
		private $retypePwd;
		private $dob;	
		const server = "localhost";
		const user = "root";
		const password="";
		const db = "college_portal";
		
		public function setUsername($username){$this->username = $username;}
		public function setEmail($email){$this->email = $email;}
		public function setPwd($pwd){$this->pwd = $pwd;}
		public function setRetypePwd($retypePwd){$this->retypePwd = $retypePwd;}
		public function setDob($dob){$this->dob = $dob;}
		public function getUsername(){return $this->username;}
		public function getEmail(){return $this->email;}
		public function getPwd(){return $this->pwd;}
		public function getRetypePwd(){return $this->retypePwd;}
		public function getdob(){return $this->dob;}

		public function __construct()
		{			
			$this->username = "";
			$this->email = "";
			$this->pwd = "";
			$this->retypePwd = "";
			$this->dob = "";		
		}

		public function validateData()
		{
			$is_valid = TRUE;
			global $errorMessage;
			$errorMessage = array_fill(0,5,"");

			$this->validateEmail($is_valid);
			$this->validateName($is_valid);
			$this->validatePwd($is_valid);
			$this->validateDob($is_valid);

			if($is_valid)
				$this->saveResult();
		}

		private function validateName(&$is_valid)
		{
			if(empty($this->username))
			{
				global $errorMessage;
				$errorMessage[0] = "Username cannot be empty";
				$is_valid = FALSE;
			}
			else if(strlen($this->username) > 20)
			{
				global $errorMessage;
				$errorMessage[0] = "Username cannot exceed 20 characters";
				$is_valid = FALSE;
			}
			else if(!preg_match("[/ /]",$this->username))
			{
				global $errorMessage;
				$errorMessage[0] = "Username cannot contain whitespace";
				$is_valid = FALSE;
			}

			else
			{
				$conn = new mysqli(self::server, self::user, self::password, self::db);
				$name = $conn->real_escape_string($this->username);
				$sql = "CALL SelectUser(\"user_name\",\"$name\")";
				$result = $conn->query($sql);

				if($result->num_rows > 0 )
				{		
					global $errorMessage;
					$errorMessage[0] = "Username had been taken";
					$is_valid = FALSE;
				}

				$result->close();
				$conn->close();
			}
		}

		private function validateEmail(&$is_valid)
		{
			if(empty($this->email))
			{
				global $errorMessage;
				$errorMessage[1] = "Email cannot be empty";
				$is_valid = FALSE;
			}
			else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			{
				global $errorMessage;
				$errorMessage[1] = "Email must be in this format E.g. abcdefgh@xxx.com";
				$is_valid = FALSE;
			}
			else
			{
				$conn = new mysqli(self::server, self::user, self::password, self::db);
				$email = $conn->real_escape_string($this->email);
				$sql = "CALL SelectUser(\"email\",\"$email\")";
				$result = $conn->query($sql);

				if($result->num_rows > 0)
				{
					global $errorMessage;
					$errorMessage[1] = "Email Address already exists <br/><a href='sign_in.php' class='text-primary'>Sign in now ?</a>";
					$is_valid = FALSE;
				}
				
				$conn->close();
			}
		}

		private function validatePwd(&$is_valid)
		{
			if(empty($this->pwd))
			{
				global $errorMessage;
				$errorMessage[2] = "Password cannot be empty";
				$is_valid = FALSE;
			}

			else if(strlen($this->pwd) < 8)
			{
				global $errorMessage;
				$errorMessage[2] = "Password must contain at least 8 characters";
				$this->pwd ="";
				$this->retypePwd = "";
				$is_valid = FALSE;
			}

			else
			{
				$alphabetic = preg_match('@[A-Za-z]@',$this->pwd);
				$numeric = preg_match('@[0-9]@',$this->pwd);

				if(!$alphabetic || !$numeric) 
				{
					global $errorMessage;
					$errorMessage[2] = "Password must contain alphabet and number";
					$this->pwd ="";
					$this->retypePwd = "";
					$is_valid = FALSE;
				}

				else if($this->pwd != $this->retypePwd)
				{
					$this->retypePwd = "";
					global $errorMessage;
					$errorMessage[3] = "Password and Retype password cannot be different";
					$is_valid = FALSE;
				}
			}
		}

		private function validateDob(&$is_valid)
		{
			if(empty($this->dob))
			{
				global $errorMessage;
				$errorMessage[4] = "Date of birth cannot be empty";
				$is_valid = FALSE;
			}

			else
			{
				$dob = date_create($this->dob);
				$today = date_create(date("y-m-d"));
				if($dob > $today)
				{
					$this->dob = "";
					global $errorMessage;
					$errorMessage[4] = "Date of birth cannot be in future";
					$is_valid = FALSE;
				}
			}
		}

		private function saveResult()
		{
			$conn = new mysqli(self::server, self::user, self::password, self::db);
			if($conn->connect_error)
			{
				die ("Connection Failed".$conn->connect_error);
			}

			$data = array($this->username,$this->email,$this->pwd,$this->dob);
			foreach($value as $data)
				$value = $conn->real_escape_string($value);

			$sql = "CALL InsertUser(\"$data[0]\", \"$data[1]\", \"$data[2]\", \"$data[3]\");";
			if($conn->query($sql)===TRUE)
			{
				$is_register = TRUE;
				echo "<script>";
				echo "alert('Register Successfully');";
				echo "window.location.replace(\"index.php\");";
				echo "</script>";
			}
			else
				"Error".$conn->error;;

			$conn->close();
		}
	}

	$user = new User();
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);

	if($_POST)
	{
		$user->setUsername(htmlspecialchars(strtolower($_POST['username'])));
		$user->setEmail(htmlspecialchars(strtolower($_POST['email'])));
		$user->setPwd(htmlspecialchars($_POST['pwd']));
		$user->setRetypePwd(htmlspecialchars($_POST['retypePwd']));
		$user->setDob(htmlspecialchars($_POST['dob']));
		$user->validateData();
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