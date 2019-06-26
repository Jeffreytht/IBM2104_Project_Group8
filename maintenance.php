<?php
require("models/users.php");
require("models/superadmin.php");
session_start();

#Check whether the user is super admin
if(!isset($_SESSION['superAdmin']))
	header("Location:index.php");
	
	#Define constant variable to store attribute of mysql server
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	#Store the url of the page
	$self = htmlspecialchars($_SERVER['PHP_SELF']);

/*************************************** GENERATE VIEW *****************************************/
	#Store html table that contain all the user's information
	$userTable = "";

	#Store html table that contain all the institute's information
	$instituteTable = "";

	#Store the counter of the number of user as well as institute
	$count = 0;

	#Create a connection to database to select all the user
	$conn = new mysqli(SERVER, USER, PASS, DB);

	if($conn->connect_error)
		die("Connection error.".$conn->connect_error);

	#SQL command to select the user
	$sql = "SELECT user_name FROM `users`";

	#Check whether the query is valid
	#Return all users  and users' information
	if($result = $conn->query($sql))
	{
		$conn->close();

		#Select and print user information one by one
		while($output = $result->fetch_assoc()["user_name"])
		{
			#Increment the counter of user
			$count++;

			#Create a connection to database to get all users' detail
			$conn = new mysqli(SERVER, USER, PASS, DB);

			if($conn->connect_error)
				die("Connection error.".$conn->connect_error);

			$sql = "CALL SelectAllUserDetailsByUsername(\"$output\")";
			
			#Check whether the query is valid
			#Return all user's information
			if($output1 = $conn->query($sql))
			{
				$userDetail = $output1->fetch_assoc();

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
			}
			else
				echo "Error. SQL execute failed.".$conn->error;

			$conn->close();
		}
	}
	
	#Set the value of count back to zero
	$count = 0;

	#Create a connection to database to select all the institute's detail
	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "SELECT institute_id FROM `institute`";

	#Check whether the query is valid
	#Return all the institute information
	if($result = $conn->query($sql))
	{
		$conn->close();

		#Select and print the institute information one by one
		while($institute = $result ->fetch_assoc()['institute_id'])
		{
			#Increment the counter
			$count++;

			#Create a connection to database and select all the institute information
			$conn = new mysqli(SERVER, USER, PASS, DB);

			if($conn->connect_error)
				die("Connection error.".$conn->connect_error);

			$sql = "CALL SelectInstituteDetails($institute)";

			if($output = $conn->query($sql))
			{
				$instituteDetail = $output->fetch_assoc();
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
			else
				echo "Error. SQL execute failed.".$conn->error;
		}
	}
	else
	{
		echo "Error. SQL execute failed.".$conn->error;
	}

	$conn->close();

	$body = <<< BODY
	<main class='main'>
		<div class="container rounded pb-2 mb-5" style="margin-top:105px;">
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
	  				<tbody >
	  					<tr>
	  						<td colspan=6><a href="addInstitute.php">Add new institute</a></td>
	  					</tr>
	  				</tbody>
				</table>	
			</div>
		</div>
	</main>
BODY;

/*************************************** VIEW *****************************************/
	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
			echo "<script src='style/maintenance.js'></script>";
		echo "</head>";
		echo "<body class='bg-light'>";
			include("nav.php");
			echo $body;
			include("footer.php");		
		echo "</body>";
	echo "</html>";
?>
