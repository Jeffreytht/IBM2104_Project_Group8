<?php
	class User
	{
		private $username;
		private $email;
		private $pwd;
		private $retypePwd;
		private $dob;
		public $errorMessage;

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
			$this->errorMessage = array_fill(0,5,"");
		}

		public function validateData()
		{
			$this->errorMessage = array_fill(0,5,"");
			$this->validateName();
			$this->validateEmail();
			$this->validatePwd();
			$this->validateDob();
		}

		private function validateName()
		{
			if(empty($this->username))
			{
				$this->errorMessage[0] = "Username cannot be empty";
			}
			else if(strlen($this->username) > 20)
			{
				$this->errorMessage[0] = "Username cannot exceed 20 characters";
			}
		}

		private function validateEmail()
		{
			if(empty($this->email))
			{
				$this->errorMessage[1] = "Email cannot be empty";
			}
			else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			{
				$this->errorMessage[1] = "Email must be in this format E.g. abcdefgh@xxx.com";
			}
		}

		private function validatePwd()
		{
			if(empty($this->pwd))
				$this->errorMessage[2] = "Password cannot be empty";

			else if(strlen($this->pwd) < 8)
			{
				$this->errorMessage[2] = "Password must contain at least 8 characters";
				$this->pwd ="";
				$this->retypePwd = "";
			}

			else
			{
				$alphabetic = preg_match('@[A-Za-z]@',$this->pwd);
				$numeric = preg_match('@[0-9]@',$this->pwd);

				if(!$alphabetic || !$numeric) 
				{
					$this->errorMessage[2] = "Password must contain alphabet and number";
					$this->pwd ="";
					$this->retypePwd = "";
				}

				else if($this->pwd != $this->retypePwd)
				{
					$this->retypePwd = "";
					$this->errorMessage[3] = "Password and Retype password cannot be different";
				}
			}
		}

		private function validateDob()
		{
			if(empty($this->dob))
			{
				$this->errorMessage[4] = "Date of birth cannot be empty";
			}

			else
			{
				$dob = date_create($this->dob);
				$today = date_create(date("y-m-d"));
				if($dob > $today)
				{
					$this->dob = "";
					$this->errorMessage[4] = "Date of birth cannot be in future";
				}
			}
		}
	}

	$user = new User();

	if($_POST)
	{
		$user->setUsername($_POST['username']);
		$user->setEmail($_POST['email']);
		$user->setPwd($_POST['pwd']);
		$user->setRetypePwd($_POST['retypePwd']);
		$user->setDob($_POST['dob']);
		$user->validateData();
	}



	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";
		echo "<body>";
		echo "<div id='intro' class='view''>";
			echo"<div class='mask rgba-black-strong'>";
				echo"<div class='container d-flex align-items-center justify-content-center h-100'>";
					echo"<div class='mt-3 pt-5 pb-5 col-md-5'>";
						echo"<div class ='jumbotron card card-image signin-jumbotron my-0'>";
							echo"<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>";
						echo"</div>";
						echo "<div class='scrollbar bg-light' id='style-1' style='height:50vh; max-height:450px'>";
							echo"<form class='px-5' method='post' action='",htmlspecialchars($_SERVER['PHP_SELF'])."'>";					
								echo"<div class='md-form'>";
									echo"<i class='fas fa-user prefix purple-text'></i>";
									echo"<input mdbActive class='form-control pl-2' type='text' name='username' required autocomplete='off' placeholder='Username' value ='".$user->getUsername()."'/>";
									echo "<div class='text-danger ml-5'>".$user->errorMessage[0]."</div>";
								echo"</div>";
								echo"<div class='md-form'>";
									echo"<i class='fa fa-envelope prefix purple-text'></i>";
									echo"<input mdbActive class='form-control pl-2' type='email' name='email' required autocomplete='off' placeholder='Email' value='".$user->getEmail()."'/>";
									echo "<div class='text-danger ml-5'>".$user->errorMessage[1]."</div>";
								echo"</div>";
								echo"<div class='md-form'>";
									echo"<i class='fas fa-key prefix purple-text'></i>";
									echo"<input mdbActive class='form-control pl-2' type='password' name='pwd' required autocomplete='off' placeholder='Password' value='".$user->getPwd()."'/>";
									echo "<div class='text-danger ml-5'>".$user->errorMessage[2]."</div>";
								echo"</div>";
								echo"<div class='md-form'>";
									echo"<i class='fas fa-key prefix purple-text'></i>";
									echo"<input mdbActive class='form-control pl-2' type='password' name='retypePwd' required autocomplete='off' placeholder='Retype Password' value='".$user->getRetypePwd()."'/>";
									echo "<div class='text-danger ml-5'>".$user->errorMessage[3]."</div>";
								echo"</div>";
								echo "<div class='md-form'>";
									echo "<i class='fas fa-calendar-day prefix purple-text'></i>";
									echo "<input type='text'class='form-control pl-2' name='dob' onfocus=\"(this.type='date')\" onblur=\"(this.type='text')\"required autocomplete='off' placeholder='Date Of Birth' value='".$user->getDob()."'/>";
									echo "<div class='text-danger ml-5'>".$user->errorMessage[4]."</div>";
								echo "</div>";

								echo"<div class='text-center'>";
									echo"<button type='submit' class='text-white btn blue-gradient col-md-6 my-4'>Register</button>";
								echo"</div>";
							echo"</form>";
	
						echo"</div>";
					echo"</div>";
				echo"</div>";
			echo"</div>";
		echo "</div>";
		echo "</body>";
	echo "</html>";
?>