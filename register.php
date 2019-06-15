<?php
	$errorMessage;
	$errorMessage = array_fill(0,5,"");

	require("models/users.php");
	class Users extends User
	{
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

	$user = new Users();
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