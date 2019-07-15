<?php
	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");
	session_start();

	#Check whether the user is super admin
	if(!(isset($_SESSION['superAdmin'])))
		header("Location: index.php");

	#Define constant variable to store attribute of mysql server
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	#Store the url of the page
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);

	#Store the option value of select list
	$stateSelection = "";
	$adminSelection = "";

	$errorMessage = array();
	$errorMessage = array_fill(0,9," ");

	#Store the user's input
	$instituteID = 0;
	$instituteName = "";
	$instituteAddress ="";
	$instituteAddressURL = "";
	$instituteIFrame = "";
	$stateID = 0;
	$admin = "";

/******************************* GENERATE VIEW ********************************************/
	#Create a connection to database to get all the state from database
	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "SELECT * FROM `state`";
	$result = $conn->query($sql);

	while($output = $result->fetch_assoc())
		$stateSelection.= "<option value='$output[state_id]'>$output[state_name]</option>";

	$conn->close();

	#Create a connection to database and get the available admin
	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "CALL SelectAllAdminWithoutInstitute()";
	$result = $conn->query($sql);

	if($result->num_rows == 0)
	{
		echo "<script>alert('There are no available admin.')</script>";
		echo "<script>window.location.replace(\"maintenance.php\")</script>";
	}
	else
	{
		#Add available admin into select list
		while($output = $result->fetch_assoc())
			$adminSelection.= "<option value='$output[user_id]'>$output[user_name]</option>";

		$conn->close();
	}

	#Run if post request
	if($_POST)
	{
		$isValid = TRUE;

		if(!empty($_POST['instituteName']))
			$instituteName = $_POST['instituteName'];
		else
		{
			$isValid = FALSE;
			$errorMessage[0] = "Institute name cannot be empty";
		}

		if(!empty($_POST['instituteAddress']))
			$instituteAddress = $_POST['instituteAddress'];
		else
		{
			$isValid = FALSE;
			$errorMessage[1] = "Institute address cannot be empty";
		}

		if(!empty($_POST['instituteAddressURL']))
			$instituteAddressURL = $_POST['instituteAddressURL'];
		else
		{
			$isValid = FALSE;
			$errorMessage[2] = "Institute address url cannot be empty";
		}

		if(!empty($_POST['iframeURL']))
			$instituteIFrame = $_POST['iframeURL'];
		else
		{
			$isValid = FALSE;
			$errorMessage[3] = "IFrame url cannot be empty";
		}

		if(isset($_POST['state']))
			$stateID = $_POST['state'];
		else
		{
			$isValid = FALSE;
			$errorMessage[4] = "State is required";
		}

		if(isset($_POST['admin']))
			$admin= $_POST['admin'];
		else
		{
			$isValid = FALSE;
			$errorMessage[5] = "Admin is required";
		}

		if(empty($_FILES["profilePic"]["name"]))
		{
			$isValid = FALSE;
			$errorMessage[6] = "Please select a profile picture";
		}

		if(empty($_FILES["coverPic"]["name"]))
		{
			$isValid = FALSE;
			$errorMessage[7] = "Please select a cover picture";
		}

		if(empty($_FILES["logo"]["name"]))
		{
			$isValid = FALSE;
			$errorMessage[8] = "Please select a logo";
		}

		if($isValid)
		{
			#Create a connection to insert institute information into database
			$conn = new mysqli(SERVER,USER,PASS,DB);
			$instituteName = $conn->real_escape_string($instituteName);
			$instituteAddress = $conn->real_escape_string($instituteAddress);
			$instituteAddressURL = $conn->real_escape_string($instituteAddressURL);
			$instituteIFrame = $conn->real_escape_string($instituteIFrame);
			$stateID = $conn->real_escape_string($stateID);
			$admin = $conn->real_escape_string($admin);

			$sql = "INSERT INTO `institute` (institute_name, address, address_url, iframe_url,state_id)
					VALUES(\"$instituteName\", \"$instituteAddress\", \"$instituteAddressURL\", \"$instituteIFrame\",$stateID)";
			
			#Check whether the query is valid
			if(!($conn->query($sql)))
				die("Error.SQL execute failed". $conn->error);

			$conn->close();


			#Create a connection to database to get the institute id 
			$conn = new mysqli(SERVER,USER,PASS,DB);
			$sql = "SELECT institute_id FROM `institute` WHERE address = \"$instituteAddress\"";

			if($result = $conn->query($sql))
				$instituteID = $result->fetch_assoc()['institute_id'];
			else
				die("Error.SQL execute failed".$conn->error);

			$conn->close();

			$conn = new mysqli(SERVER,USER,PASS,DB);
			$sql = "INSERT INTO `institute_user`
					VALUES($instituteID, $admin)";
			if(!$conn->query($sql))
				die("Error. SQL execute failed". $conn->error);

			$conn->close();

			$imageID = 0;

			#Get the image extension
			$ext = pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
			$fileName = $conn->real_escape_string($_FILES['profilePic']['name']);

			#Create a connection to database to insert an image
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "INSERT INTO `gallery` (image_path)
					VALUES(\"$fileName\")";

			if(!($conn->query($sql)))
				die("Error. SQL execute failed". $conn->error);

			$conn->close();

			#Create a connection to database to get the image id
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "SELECT image_id 
					FROM `gallery`
					WHERE image_path = \"{$fileName}\"";

			if($result = $conn->query($sql))
				$imageID = $result->fetch_assoc()['image_id'];
			else
				die("Error. SQL execute failed". $conn->error);

			$conn->close();

			#Set the image destination and rename the image
			$imageDestination = "images/profile/".$imageID.".".$ext;

			#Create a connection to database to rename the image path
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "UPDATE `gallery`
					SET image_path = \"$imageDestination\"
					WHERE image_id = $imageID";

			if(!($conn->query($sql)))
				die("Error. SQL execute failed". $conn->error);

			$conn->close();	

			#Upload the image to specific folder
			move_uploaded_file($_FILES['profilePic']['tmp_name'], $imageDestination);


			#Conenct to database to insert the image path and institute id
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);

			$sql = "INSERT INTO `profile_pic`
	   				VALUES($instituteID, $imageID);" ;

			if(!($conn->query($sql)))
				die("Error. SQL execute failed.".$conn->error);
			
			$conn->close();


			#Get the image extension
			$ext = pathinfo($_FILES['coverPic']['name'], PATHINFO_EXTENSION);
			$fileName = $conn->real_escape_string($_FILES['coverPic']['name']);

			#Create a connection to database to insert an image
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "INSERT INTO `gallery` (image_path)
					VALUES(\"$fileName\")";

			if(!($conn->query($sql)))
				die("Error. SQL execute failed". $conn->error);

			$conn->close();

			#Create a connection to database to get the image id
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "SELECT image_id 
					FROM `gallery`
					WHERE image_path = \"$fileName\"";

			if($result = $conn->query($sql))
				$imageID = $result->fetch_assoc()['image_id'];
			else
				die("Error. SQL execute failed". $conn->error);

			$conn->close();

			#Set the image destination and rename the image
			$imageDestination = "images/cover/".$imageID.".".$ext;

			#Create a connection to database to rename the image path
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "UPDATE `gallery`
					SET image_path = \"$imageDestination\"
					WHERE image_id = $imageID";

			if(!($conn->query($sql)))
				die("Error. SQL execute failed". $conn->error);

			$conn->close();	

			#Upload the image to specific folder
			move_uploaded_file($_FILES['coverPic']['tmp_name'], $imageDestination);


			#Conenct to database to insert the image path and institute id
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);

			$sql = "INSERT INTO `cover_photo`
	   				VALUES($instituteID, $imageID);" ;

			if(!($conn->query($sql)))
				die("Error. SQL execute failed.".$conn->error);
			
			$conn->close();


			#Get the image extension
			$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
			$fileName = $conn->real_escape_string($_FILES['logo']['name']);
			#Create a connection to database to insert an image
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "INSERT INTO `gallery` (image_path)
					VALUES(\"$fileName\")";

			if(!($conn->query($sql)))
				die("Error. SQL execute failed". $conn->error);

			$conn->close();

			#Create a connection to database to get the image id
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "SELECT image_id 
					FROM `gallery`
					WHERE image_path = \"$fileName\"";

			if($result = $conn->query($sql))
				$imageID = $result->fetch_assoc()['image_id'];
			else
				die("Error. SQL execute failed". $conn->error);

			$conn->close();

			#Set the image destination and rename the image
			$imageDestination = "images/logo/".$imageID.".".$ext;

			#Create a connection to database to rename the image path
			$conn = new mysqli(SERVER, USER, PASS, DB);
			$sql = "UPDATE `gallery`
					SET image_path = \"$imageDestination\"
					WHERE image_id = $imageID";

			if(!($conn->query($sql)))
				die("Error. SQL execute failed". $conn->error);

			$conn->close();	

			#Upload the image to specific folder
			move_uploaded_file($_FILES['logo']['tmp_name'], $imageDestination);


			#Conenct to database to insert the image path and institute id
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);

			$sql = "INSERT INTO `institute_logo`
	   				VALUES($instituteID, $imageID);" ;

			if(!($conn->query($sql)))
				die("Error. SQL execute failed.".$conn->error);
			
			$conn->close();

			header("Location:maintenance.php");
		}
	}
	echo "<html>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body class='bg-light'>";
		include ("nav.php");
			echo <<<BODY
			<main class='main mb-5'>
				<div class='container d-flex justify-content-center'>
					<div class='collegeDetail bg-white px-5 py-3'>	
						<form class="mt-5" method="post" action="$self" enctype="multipart/form-data">
							<h2 class="text-center font-weight-bold purple-text">New Institute</h2>
							<div class="md-form">
								<i class="fas fa-graduation-cap prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="instituteName" placeholder="Enter institute name" value="$instituteName"/>
								<div class="text-danger">$errorMessage[0]</div>
							</div>
							<div class="md-form">
								<i class="fas fa-map-marker-alt prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="instituteAddress" placeholder="Enter institute address" value="$instituteAddress"/>
								<div class="text-danger">$errorMessage[1]</div>
							</div>
							<div class="md-form">
								<i class="fas fa-map-marker-alt prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="instituteAddressURL" placeholder="Enter institute address url" value="$instituteAddressURL"/>
								<div class="text-danger">$errorMessage[2]</div>
							</div>
							<div class="md-form">
								<i class="fab fa-google prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="iframeURL" placeholder="Enter institute iframe url" value="$instituteIFrame"/>
								<div class="text-danger">$errorMessage[3]</div>
							</div>
							<div class="form-group">
							  <select class="form-control" name="state">
							  	<option selected disabled>Select State</option>
							    $stateSelection
							  </select>
							  <div class="text-danger">$errorMessage[4]</div>
							</div>
							
							<div class="form-group mb-4">
							  <select class="form-control" name="admin">
							  	<option selected disabled>Select admin</option>
							    $adminSelection
							  </select>
							  <div class="text-danger">$errorMessage[5]</div>
							</div>

							<div class="input-group">
								<div class="input-group-prepend">
							    	<i class="fas fa-images input-group-text"></i>
							  	</div>
							  	<div class="custom-file">
								    <input type="file" name="profilePic" class="custom-file-input" id="inputGroupFile" accept="image/*">
								    <label class="custom-file-label" for="inputGroupFile">Choose Profile Picture</label>
							  	</div>
							</div>
							<div class="text-danger">$errorMessage[6]</div>
							<div class="mt-2">
								<ol class="pl-4" id="imageFiles">
								</ol>
							</div>
							<div class="input-group">
								<div class="input-group-prepend">
							    	<i class="fas fa-images input-group-text"></i>
							  	</div>
							  	<div class="custom-file">
								    <input type="file" name="coverPic" class="custom-file-input" id="inputGroupFile" accept="image/*">
								    <label class="custom-file-label" for="inputGroupFile">Choose Cover Picture</label>
							  	</div>
							</div>
							<div class="text-danger">$errorMessage[7]</div>
							<div class="mt-2">
								<ol class="pl-4" id="imageFiles">
								</ol>
							</div>
							<div class="input-group">
								<div class="input-group-prepend">
							    	<i class="fas fa-images input-group-text"></i>
							  	</div>
							  	<div class="custom-file">
								    <input type="file" name="logo" class="custom-file-input" id="inputGroupFile" accept="image/*">
								    <label class="custom-file-label" for="inputGroupFile">Choose Logo</label>
							  	</div>
							</div>
							<div class="text-danger">$errorMessage[8]</div>
							<div class="mt-2">
								<ol class="pl-4" id="imageFiles">
								</ol>
							</div>
							
							<button type='submit' class='text-white btn blue-gradient w-100 my-4'>Add Institute</button>
						</form>
					</div>
				</div>
			</main>
BODY;
			include("footer.php");
			echo '<script>
			// Add the following code if you want the name of the file appear on select
			$(".custom-file-input").on("change", function() {
			  var fileName = $(this).val().split("\\\\").pop();
			  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
			});
			</script>';
		echo "</body>";
	echo "</html>";
?>