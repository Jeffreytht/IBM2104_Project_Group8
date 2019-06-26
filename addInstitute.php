<?php
	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");
	session_start();

	if(!(isset($_SESSION['superAdmin'])))
		header("Location: index.php");

	#Define constant variable to store attribute of mysql server
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	#Store the url of the page
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);
	$instituteName = "";
	$instituteAddress ="";
	$instituteAddressURL = "";
	$instituteIFrame = "";
	$stateID = 0;
	$admin = "";
	$stateSelection = "";
	$adminSelection = "";

	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "SELECT * FROM `state`";
	$result = $conn->query($sql);

	while($output = $result->fetch_assoc())
		$stateSelection.= "<option value='$output[state_id]'>$output[state_name]</option>";

	$conn->close();

	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "CALL SelectAllAdminWithoutInstitute()";
	$result = $conn->query($sql);

	while($output = $result->fetch_assoc())
		$adminSelection.= "<option value='$output[user_id]'>$output[user_name]</option>";
	$conn->close();

	if($_POST)
	{
		if(isset($_POST['instituteName']))
			$instituteName = $_POST['instituteName'];

		if(isset($_POST['instituteAddress']))
			$instituteAddress = $_POST['instituteAddress'];

		if(isset($_POST['instituteAddressURL']))
			$instituteAddressURL = $_POST['instituteAddressURL'];

		if(isset($_POST['iframeURL']))
			$instituteIFrame = $_POST['iframeURL'];

		if(isset($_POST['state']))
			$stateID = $_POST['state'];

		if(isset($_POST['admin']))
			$admin= $_POST['admin'];

		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "INSERT INTO `institute` (institute_name, address, address_url, iframe_url,state_id)
				VALUES(\"$instituteName\", \"$instituteAddress\", \"$instituteAddressURL\", \"$instituteIFrame\",$stateID)";
		$conn->query($sql);
		echo $conn->error;
		$conn->close();

		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "SELECT institute_id FROM `institute` WHERE address = \"$instituteAddress\"";
		$result = $conn->query($sql);
		echo $conn->error;
		$conn->close();

		$instituteID = $result->fetch_assoc()['institute_id'];

		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "INSERT INTO `institute_user`
				VALUES($instituteID, $admin)";
		$conn->query($sql);
		echo $conn->error;
		$conn->close();

		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "SELECT MAX(image_id) AS maxID
				FROM `gallery`";
		$result = $conn->query($sql);
		$maxID = $result->fetch_assoc()['maxID'];
		echo $conn->error;
		$conn->close();

		if(!empty($_FILES["profilePic"]["name"]))
		{
			$maxID++;
			echo $maxID;
			#Get the image extension
			$ext = pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);

			#Set the image destination and rename the image
			$imageDestination = "images/profile/".$maxID.".".$ext;

			#Upload the image to specific folder
			move_uploaded_file($_FILES['profilePic']['tmp_name'], $imageDestination);

			#Conenct to database to insert the image path and id
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);

			$sql = "CALL InsertProfilePic(\"$imageDestination\",$instituteID)" ;

			if(!($conn->query($sql)))
				echo "Error. SQL execute failed.".$conn->error; 
		echo $conn->error;
			
			$conn->close();
		}

		if(!empty($_FILES["coverPic"]["name"]))
		{
			$maxID++;
			#Get the image extension
			$ext = pathinfo($_FILES['coverPic']['name'], PATHINFO_EXTENSION);

			#Set the image destination and rename the image
			$imageDestination = "images/cover/".$maxID.".".$ext;

			#Upload the image to specific folder
			move_uploaded_file($_FILES['coverPic']['tmp_name'], $imageDestination);

			#Conenct to database to insert the image path and id
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);

			$sql = "CALL InsertCoverPhoto(\"$imageDestination\",$instituteID)" ;

			if(!($conn->query($sql)));
				echo "Error. SQL execute failed.".$conn->error; 
		echo $conn->error;
			
			$conn->close();
		}

		if(!empty($_FILES["logo"]["name"]))
		{
			$maxID++;
			#Get the image extension
			$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);

			#Set the image destination and rename the image
			$imageDestination = "images/logo/".$maxID.".".$ext;

			#Upload the image to specific folder
			move_uploaded_file($_FILES['logo']['tmp_name'], $imageDestination);

			#Conenct to database to insert the image path and id
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);
			echo $maxID;
			$sql = "CALL InsertInstituteLogo(\"$imageDestination\",$instituteID)" ;

			if(!($conn->query($sql)));
				echo "Error. SQL execute failed.".$conn->error; 
		echo $conn->error;
			
			$conn->close();
		}

		header("Location:$self");
	}
	echo "<html>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body class='bg-light'>";
		include ("nav.php");
			?>
			<main class='main'>
				<div class='container d-flex justify-content-center'>
					<div class='collegeDetail bg-white px-5 py-3'>	
						<form class="mt-5" method="post" action="<?php echo $self; ?>" enctype="multipart/form-data">
							<h2 class="text-center font-weight-bold purple-text">New Institute</h2>
							<div class="md-form">
								<i class="fas fa-graduation-cap prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="instituteName" placeholder="Enter institute name"/>
							</div>
							<div class="md-form">
								<i class="fas fa-map-marker-alt prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="instituteAddress" placeholder="Enter institute address"/>
							</div>
							<div class="md-form">
								<i class="fas fa-map-marker-alt prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="instituteAddressURL" placeholder="Enter institute address url"/>
							</div>
							<div class="md-form">
								<i class="fab fa-google prefix purple-text"></i>
								<input class="form-control ml-5" type="text" name="iframeURL" placeholder="Enter institute iframe url"/>
							</div>
							<div class="form-group">
							  <select class="form-control" name="state">
							  	<option selected disabled>Select State</option>
							    <?php
							    	echo $stateSelection;
							    ?>
							  </select>
							</div>
							<div class="form-group mb-4">
							  <select class="form-control" name="admin">
							  	<option selected disabled>Select admin</option>
							    <?php
							    	echo $adminSelection;
							    ?>
							  </select>
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
							<div class="mt-2">
								<ol class="pl-4" id="imageFiles">
								</ol>
							</div>
							
							<button type='submit' class='text-white btn blue-gradient w-100 my-4'>Add Institute</button>
						</form>
					</div>
				</div>
			</main>
			<?php
			include("footer.php");
		echo "</body>";
	echo "</html>";
?>