<?php
require("models/users.php");
require("models/superadmin.php");
session_start();

if(!isset($_SESSION['superAdmin']))
	header("index.php");

	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	$self = htmlspecialchars($_SERVER['PHP_SELF']);
	$userTable = "";
	$instituteTable = "";
	$count = 0;


	$conn = new mysqli(SERVER, USER, PASS, DB);
	$sql = "SELECT user_name FROM `users`";
	$result = $conn->query($sql);
	$conn->close();

	while($username = $result->fetch_assoc()["user_name"])
	{
		$count++;
		$anotherConn = new mysqli(SERVER, USER, PASS, DB);
		$sql = "CALL SelectAllUserDetails(\"$username\")";
		$anotherResult = $anotherConn->query($sql);
		echo $anotherConn->error;
		$userDetail = $anotherResult->fetch_assoc();

		$userTable .= <<<TABLE
		<tr>	
			<td>$count</td>
			<td>$userDetail[user_name]</td>
			<td>$userDetail[pwd]</td>
			<td>$userDetail[email]</td>
			<td>$userDetail[dob]</td>
			<td>$userDetail[recent_changes]</td>
			<td>$userDetail[role_name]</td>
			<td>
				<form action="promoteUser.php" method="post">
					<input type="hidden" name="id" value="$userDetail[user_id]" />
					<span class="promote pointer" onclick="">Promote</span> | 
					<span class="demote pointer">Demote</span> | 
					<span class="delete pointer">Delete</span>
				</form>
			</td>
		</tr>
TABLE;

		$anotherConn->close();
	}

	$count = 0;

	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "SELECT institute_id FROM `institute`";
	$result = $conn->query($sql);
	$conn->close();

	while($institute = $result ->fetch_assoc()['institute_id'])
	{
		$count++;
		$anotherConn = new mysqli(SERVER, USER, PASS, DB);
		$sql = "CALL SelectInstituteDetails($institute)";
		$anotherResult = $anotherConn->query($sql);
		$instituteDetail = $anotherResult->fetch_assoc();

		$instituteTable .= <<< TABLE
		<tr>
			<td>$count</td>
			<td>$instituteDetail[institute_name]</td>
			<td>$instituteDetail[address]</td>
			<td>$instituteDetail[state_name]</td>
			<td>$instituteDetail[user_name]</td>
			<td>
				<form action="deleteInstitute.php" method="post">
					<input type="hidden" name="id" value="$instituteDetail[institute_id]">
					<span class="deleteInstitute pointer">Delete</span>
				</form>
			</td>
		</tr>
TABLE;
	}

	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
			echo "<script src='style/maintenance.js'></script>";
		echo "</head>";
		echo "<body class='bg-light'>";
			include("nav.php");
			echo "<main class='main'>";
			echo <<< BODY

		<div class="container rounded pb-2" style="margin-top:105px;">
  			<div class="bg-white pt-4 px-5 mb-3 border rounded">
				<h4>User Maintenance</h4>
				<table class="table table-hover">
	  				<thead>
	  					<tr>	
	  						<th>#</th>
	  						<th>Username</th>
	  						<th>Password</th>
	  						<th>Email</th>
	  						<th>Date Of Birth</th>
	  						<th>Recent Change</th>
	  						<th>Role</th>
	  						<th></th>
	  					</tr>
	  				</thead>
	  				<tbody >
	  					$userTable
	  				</tbody>
				</table>	
			</div>
			<div class="bg-white pt-4 px-5 border rounded">
				<h4>Institute Maintenance</h4>
				<table class="table table-hover">
	  				<thead>
	  					<tr>	
	  						<th>#</th>
	  						<th>Institute Name</th>
	  						<th>Address</th>
	  						<th>State</th>
	  						<th>Admin</th>
	  						<th></th>
	  					</tr>
	  				</thead>
	  				<tbody >
	  					$instituteTable
	  				</tbody>
				</table>	
			</div>
		</div>

BODY;
			echo "</main>";
			include("footer.php");
			
		echo "</body>";
	echo "</html>";
?>
