<?php
	$errorMessage;
	

	function startUser($num)
	{
		global $errorMessage;
		$errorMessage = array_fill(0,$num,"");
	}

	class User
	{
		protected $userID;
		protected $username;
		protected $email;
		protected $pwd;
		protected $dob;	
		protected $roleID;
		const server = "localhost";
		const user = "root";
		const password="";
		const db = "college_portal";
		
		public function setUsername($username){$this->username = $username;}
		public function setEmail($email){$this->email = $email;}
		public function setPwd($pwd){$this->pwd = $pwd;}
		public function setRetypePwd($retypePwd){$this->retypePwd = $retypePwd;}
		public function setDob($dob){$this->dob = $dob;}
		public function getUserID(){return $this->userID;}
		public function getUsername(){return $this->username;}
		public function getEmail(){return $this->email;}
		public function getPwd(){return $this->pwd;}
		public function getRetypePwd(){return $this->retypePwd;}
		public function getdob(){return $this->dob;}
		public function printDate()
		{
			$month = array(
				"01" => "January",
				"02" =>"February",
				"03" =>"March",
				"04" =>"April",
				"05" =>"May",
				"06" =>"June",
				"07" =>"July",
				"08" =>"August",
				"09" =>"September",
				"10" =>"October",
				"11" =>"November",
				"12" =>"December"
				);

				$dob = explode("-", $_SESSION['user']->getDOB());
				$dob[1] = $month[$dob[1]];

				return $dob[2]." ".$dob[1]." ".$dob[0];
		}

		public function __construct()
		{			
			$this->username = "";
			$this->email = "";
			$this->pwd = "";
			$this->retypePwd = "";
			$this->dob = "";
			$this->roleID = NULL;		
		}

		public function assignUser($user)
		{	
			$this->userID = $user['user_id'];		
			$this->username = $user['user_name'];
			$this->email = $user['email'];
			$this->pwd = $user['pwd'];
			$this->dob = $user['dob'];
			$this->roleID = $user['role_id'];		
		}

		public function validateName(&$is_valid, $index)
		{
			if(empty($this->username))
			{
				global $errorMessage;
				$errorMessage[$index] = "Username cannot be empty";
				$is_valid = FALSE;
			}
			else if(strlen($this->username) > 20)
			{
				global $errorMessage;
				$errorMessage[$index] = "Username cannot exceed 20 characters";
				$is_valid = FALSE;
			}
			else if(preg_match("@[/ /]@", $this->username))
			{
				global $errorMessage;
				$errorMessage[$index] = "Username cannot contain whitespace";
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

		public function validateEmail(&$is_valid, $index)
		{
			if(empty($this->email))
			{
				global $errorMessage;
				$errorMessage[$index] = "Email cannot be empty";
				$is_valid = FALSE;
			}
			
			else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			{
				global $errorMessage;
				$errorMessage[$index] = "Email must be in this format E.g. abcdefgh@xxx.com";
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
					$errorMessage[$index] = "Email Address already exists <br/><a href='sign_in.php' class='text-primary'>Sign in now ?</a>";
					$is_valid = FALSE;
				}
				
				$conn->close();
			}
		}

		public function validatePwd(&$is_valid, $index)
		{
			if(empty($this->pwd))
			{
				global $errorMessage;
				$errorMessage[$index] = "Password cannot be empty";
				$is_valid = FALSE;
			}

			else if(strlen($this->pwd) < 8)
			{
				global $errorMessage;
				$errorMessage[$index] = "Password must contain at least 8 characters";
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
					$errorMessage[$index] = "Password must contain alphabet and number";
					$this->pwd ="";
					$this->retypePwd = "";
					$is_valid = FALSE;
				}
			}
		}

		public function validateRetypePwd(&$is_valid, $index)
		{
			if($this->pwd != $this->retypePwd)
			{
				$this->retypePwd = "";
				global $errorMessage;
				$errorMessage[$index] = "Password and Retype password must not be different";
				$is_valid = FALSE;
			}
		}

		public function validateDob(&$is_valid, $index)
		{
			if(empty($this->dob))
			{
				global $errorMessage;
				$errorMessage[$index] = "Date of birth cannot be empty";
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
					$errorMessage[$index] = "Date of birth cannot be in future";
					$is_valid = FALSE;
				}
			}
		}
	}
?>