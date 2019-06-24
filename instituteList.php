	<?php
	require("models/users.php");
	require("models/normalUser.php");
	require("models/admin.php");
	require("models/superadmin.php");
	require_once("models/institute.php");

	session_start();
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	$institute = array();
	$result;
	$list = "";
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);

	$valueOfInstitute ="";
	$valueOfCourse = "Select Course";
	$valueOfLocation = "Select Location";

	if(isset($_POST["searchInstitute"]))
	{
		$valueOfInstitute = $_POST["searchInstitute"];
		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "SELECT * FROM institute WHERE institute_name LIKE \"%$_POST[searchInstitute]%\"";
		$result = $conn->query($sql);
	}
	else if(isset($_POST["course"]) && isset($_POST["location"]))
	{
		$valueOfCourse = $_POST["course"];
		$valueOfLocation = $_POST["location"];
		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "SELECT i.*, s.state_name 
			FROM `institute` i, `state` s
			WHERE i.institute_id IN 
			(
				SELECT ic.institute_id 
				FROM `institute_course` ic, `course` c
				WHERE c.course_name = \"$_POST[course]\" 
				&& ic.course_id = c.course_id
			)
			&& i.state_id = s.state_id 
			&& s.state_name = \"$_POST[location]\"
			";
		$result = $conn->query($sql);
		echo $conn->error;
	}
	else if(isset($_POST["course"]))
	{
		$valueOfCourse = $_POST["course"];
		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "SELECT * 
		FROM `institute` 
		WHERE institute_id IN 
		(
			SELECT institute_id 
			FROM `institute_course` ic, `course` c 
			WHERE c.course_name = \"$_POST[course]\" && ic.course_id = c.course_id
		)";
		$result = $conn->query($sql);
		echo $conn->error;
	}
	else if(isset($_POST["location"]))
	{
		$valueOfLocation = $_POST["location"];
		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "SELECT i.*, s.state_name 
				FROM `institute` i, `state` s 
				WHERE i.state_id = s.state_id && s.state_name = \"$_POST[location]\"";
		$result = $conn->query($sql);
		
	}
	else
	{
		$conn = new mysqli(SERVER,USER,PASS,DB);
		$sql = "SELECT * FROM institute";
		$result = $conn->query($sql);
	}
	$conn->close();

	if($result->num_rows > 0)
	{
		while($selectedInstitute = $result->fetch_assoc())
		{
			$anotherConns = new mysqli(SERVER,USER,PASS,DB);
			$sql = "CALL SelectInstituteDetails($selectedInstitute[institute_id])";

			$anotherResult = $anotherConns->query($sql);
			$selectedResult = $anotherResult->fetch_assoc();
			$anotherConns->close();

			$tempInstitute = new Institute();
			$tempInstitute->assignInstitute($selectedResult);

			$anotherConns = new mysqli(SERVER,USER,PASS,DB);
			$sql = "SELECT g.image_path 
					FROM institute_logo il, gallery g 
					WHERE il.institute_id = $selectedInstitute[institute_id] && g.image_id = il.image_id";

			$anotherResult = $anotherConns->query($sql);
			$selectedLogo = $anotherResult->fetch_assoc();
			$anotherConns->close();

			$tempInstitute->setInstituteLogo($selectedLogo['image_path']);
			array_push($institute,$tempInstitute);
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
								<a href='instituteDetail.php?id={$row->getInstituteID()}' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
LIST;
		}
	}

	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";
		echo "<body class='bg-light'>";
			include("nav.php");

echo <<<BODY
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
											echo "<option value='$state'>$state</option>";
echo <<< BODY
									</select>
								</div>
								<div class='form-group'>
									<label for='course'>Course :</label>
									<select class='form-control' id='course' name='course'>
										<option value="" disabled selected>$valueOfCourse</option>
BODY;
										foreach($course as $name)	
											echo "<option value='$name'>$name</option>";
echo <<<BODY
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

			include("footer.php");
		echo "</body>";
	echo "</html>";

?>