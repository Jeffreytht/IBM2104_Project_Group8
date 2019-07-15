<?php

	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");

	#Store the url of the page
	$self = htmlspecialchars($_SERVER['PHP_SELF']);

	#Array to store all the institute information
	$institute = array();

	#Search result by database
	$result;

	$list = "";

	#Store the value of the search
	$valueOfInstitute ="";
	$valueOfCourse = "Select Course";
	$valueOfLocation = "Select Location";

	if(isset($_POST["searchInstitute"]))
	{
		$valueOfInstitute = $_POST["searchInstitute"];

		#Create a connection to database to get the institute detail based on the searching criteria
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);
		
		$searchInstitute = $_POST["searchInstitute"];
		$sql = "SELECT * FROM institute WHERE institute_name LIKE \"%$searchInstitute%\"";

		#Check whether the query is valid
		#Select institute details based on institute name
		if(!($result = $conn->query($sql)))
			echo "Error. SQL execute failed.".$conn->error;

		$conn->close();
	}

	else if(isset($_POST["course"]) && isset($_POST["location"]))
	{
		$valueOfCourse = $_POST["course"];
		$valueOfLocation = $_POST["location"];

		#Create a connection to database to get the institute detail based on the searching criteria
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$postCourse = $conn->real_escape_string($_POST["course"]);
		$postLocation = $conn->real_escape_string($_POST["location"]);

		$sql = "SELECT i.*, s.state_name 
				FROM `institute` i, `state` s
				WHERE i.institute_id IN 
				(
					SELECT ic.institute_id 
					FROM `institute_course` ic, `course` c
					WHERE c.course_name = \"$postCourse\" 
					&& ic.course_id = c.course_id
				)
				&& i.state_id = s.state_id 
				&& s.state_name = \"$postLocation\"
				";

		#Check whether the query is valid
		#Select institute details base on location and course		
		if(!($result = $conn->query($sql)))
			echo "Error. SQL execute failed.".$conn->error;

		$conn->close();
	}

	else if(isset($_POST["course"]))
	{
		$valueOfCourse = $_POST["course"];

		#Create a connection to database to get the institute detail based on the searching criteria
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$postCourse = $conn->real_escape_string($_POST["course"]);

		$sql = "SELECT * 
				FROM `institute` 
				WHERE institute_id IN 
				(
					SELECT institute_id 
					FROM `institute_course` ic, `course` c 
					WHERE c.course_name = \"$postCourse\" && ic.course_id = c.course_id
				)";

		#Check whether the query is valid.
		#Select institute details based on the course
		if(!($result = $conn->query($sql)))
			echo "Error. SQL execute failed.".$conn->error;

		$conn->close();
	}

	else if(isset($_POST["location"]))
	{
		$valueOfLocation = $_POST["location"];

		#Create a connection to database to get the institute detail based on the searching criteria
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$postLocation = $conn->real_escape_string($_POST["location"]);

		$sql = "SELECT i.*, s.state_name 
				FROM `institute` i, `state` s 
				WHERE i.state_id = s.state_id && s.state_name = \"$postLocation\"";
		
		#Check whether the query is valid.
		#Select institute details based on the location
		if(!($result = $conn->query($sql)))
			echo "Error. SQL execute failed.".$conn->error;

		$conn->close();
	}

	else
	{
		#Create a connection to database to get the institute detail based on the searching criteria
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$sql = "SELECT * FROM institute";

		#Check whether the query is valid
		#Select all institute details
		if(!($result = $conn->query($sql)))
			echo "Error. SQL execute failed.".$conn->error;

		$conn->close();
	}



/******************************* GENERATE VIEW *******************************/
	if($result->num_rows > 0)
	{
		#Loop and get the institute detail one by one
		while($output = $result->fetch_assoc())
		{
			#Create a connection to database to get the institute detail based on the search result
			$conns = new mysqli(SERVER,USER,PASS,DB);

			#SQL command that call the stored procedure in the database
			$sql = "CALL SelectInstituteDetails($output[institute_id])";

			if($result1 = $conns->query($sql))
			{
				$output1 = $result1->fetch_assoc();
				$conns->close();

				$tempInstitute = new Institute();
				$tempInstitute->assignInstitute($output1);

				#Create a connection to database to get the image path
				$conn = new mysqli(SERVER,USER,PASS,DB);

				#Close the page if unable to create connection
				if($conn->connect_error)
					die ("Connection Failed".$conn->connect_error);

				$sql = "SELECT g.image_path 
						FROM institute_logo il, gallery g 
						WHERE il.institute_id = $output1[institute_id] && g.image_id = il.image_id";

				#Check whether the query is valid
				#Return image path
				if($result2 = $conn->query($sql))
				{
					$output2 = $result2->fetch_assoc();
					$conn->close();

					$tempInstitute->setInstituteLogo($output2['image_path']);
					array_push($institute,$tempInstitute);
				}
				else
					echo "Error. SQL execute failed.".$conn->error;
			}
			else
				echo "Error. SQL execute failed.".$conn->error;	
		}

		foreach($institute as $row)
		{
			$numOfCourse = sizeof($row->getCourse());

			$list.= <<<LIST
			<div class='bg-white mb-3 py-3 px-2 college-list'>
				<div class='row'>
					<div class='col-md-4 d-flex align-items-center justify-content-center'>
						<img src='{$row->getLogo()}' class='img-fluid college-logo'>
					</div>

					<div class='col-md-8'>
						<h5 class='font-weight-bold'>{$row->getInstituteName()}</h5>
						<div class='mb-2'>
							{$row->printRate()}
						</div>
						<div class='row'>
							<div class='col-md-6'>
								<h6>Location: {$row->getState()->getStateName()}</h6>
								<h6>Course Offer: $numOfCourse</h6>
							</div>
							<div class='col-md-6'>
								<a href='institute.php?id={$row->getInstituteID()}' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
LIST;
		}
	}



$body = <<<BODY
			<main class='mt-5 pt-5 main' id='wrap'>
				<div class='container'>	
					<div class='row mt-5'>
						<div class='col-md-3 mx-3 px-4 py-4 bg-white' style='max-height:470px'>
							<h4>Search Institute</h4>
							<hr/>
							<form action='$self' method='post' class='pb-3'>
								<div class='md-form input-group mb-3'>
									<input type='text' value="$valueOfInstitute" name="searchInstitute" class='form-control' placeholder='Institute'>
									<div class='input-group-append'>
										<button type='submit' class = 'btn btn-secondary btn-rounded mx-0 px-2 my-0 py-0'><i class='fas fa-search'></i></button>
									</div>
								</div>
							</form>

							<h4>Filter By</h4>
							<form action='$self' method='post' class='pb-3'>
								<div class='form-group'>
									<label for='location'>Location :</label>
									<select class='form-control' id='location' name='location'>
										<option value="" disabled selected>$valueOfLocation</option>
BODY;
										foreach($location as $state)
										{	
											$body.="<option value='$state'";

											if(isset($_POST["location"]) && $_POST["location"] == $state)
												$body .= "selected";

											$body.=">$state</option>";
										}
$body.= <<< BODY
									</select>
								</div>
								<div class='form-group'>
									<label for='course'>Course :</label>
									<select class='form-control' id='course' name='course'>
										<option value="" disabled selected>$valueOfCourse</option>
BODY;
										foreach($course as $name)	
										{	
											$body.="<option value='$name'";

											if(isset($_POST["course"]) && $_POST["course"] == $name)
												$body .= "selected";

											$body.=">$name</option>";
										}
$body.= <<<BODY
									</select>
								</div>
								<button type='submit' class='btn btn-secondary btn-rounded mt-2 mx-0 col-md-12'>Search<i class='fas fa-search pl-3'></i></button>
							</form>

						</div>
						<div class='col-md-8 mx-3'>
							<div class='py-2 px-3 bg-white'>
								<h3 class='font-weight-bold'><i class="fas fa-university pr-2"></i>Institute In Malaysia</h3>
							</div>
							<div class='scrollbar mt-3' id='style-3' style='height:65vh;'>
								$list
							</div>
						</div>
					</div>	
				</div>
			</main>
BODY;

/***************************** VIEW **********************************/
	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";
		echo "<body class='bg-light'>";
			include("nav.php");
			echo $body;
			include("footer.php");
		echo "</body>";
	echo "</html>";

?>