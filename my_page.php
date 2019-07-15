<?php

	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");
	session_start();

	if(!(isset($_SESSION['admin'])))
		header("Location: index.php");

	#Define constant variable to store attribute of mysql server
	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	#Store the url of the page
	$self = htmlspecialchars($_SERVER["PHP_SELF"]);

	#Store the admin object from the session and assign its value
	$admin = $_SESSION['admin'];
	$admin->assignAdmin();


	$newsID = "";

	if(isset($_POST['newsID']))
	{
		#Create a conenction to database to insert the news submitted
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$sql = "CALL DeleteNewsByNewsID($_POST[newsID])";

		if($result = $conn->query($sql))
		{
			while($output = $result->fetch_assoc())
			{
				unlink($output['path']);
			}
		}
		else
		{
			echo "Error.SQL execute failed. ".$conn->error;
		}

		$conn->close();
		header("Location: $self");
	}

	#If post request and news is submitted
	else if(!empty($_POST["content"]))
	{
		#Create a conenction to database to insert the news submitted
		$conn = new mysqli(SERVER,USER,PASS,DB);
		$content = $conn->real_escape_string($_POST["content"]);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$sql = "INSERT INTO news (content,institute_id) 
				VALUES(\"$content\",\"{$admin->getInstitute()->getInstituteID()}\")";

		if(!($conn->query($sql)))
			echo "Error. SQL execute failed.".$conn->error; 

		$conn->close();

		#Create a connection to database to select the news ID
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$sql = "SELECT MAX(news_id) AS news_ID 
				FROM news";

		#Check whether the query is valid
		#Return the current news ID
		if($result = $conn->query($sql))
		{
			#Store news id from database
			$newsID = $result->fetch_assoc()['news_ID'];

			#Check whether the image is uploaded
			if (!empty($_FILES["newsImage"]["name"][0])) 
			{
				$maxID = 0;

				#Create a connection to database to get the max Image ID
				$conn = new mysqli(SERVER,USER,PASS,DB);

				#Close the page if unable to create connection
				if($conn->connect_error)
					die ("Connection Failed".$conn->connect_error);

				$sql = "SELECT MAX(image_id) AS maxID 
						FROM gallery";

				#Check whether the query is valid
				#Return the max current image id
				if($result = $conn->query($sql))
				{
					#Store the image id into maxID
					$maxID = $result->fetch_assoc()['maxID'];
					$conn->close();

					#Count the number of image uploaded
					$sizeOfFile = count($_FILES['newsImage']['name']);

					#Insert all the uploaded image path into database
					for($i = 0; $i < $sizeOfFile; $i++)
					{
						#Increment the max id
						$maxID++;

						#Get the image extension
						$ext = pathinfo($_FILES['newsImage']['name'][$i], PATHINFO_EXTENSION);

						#Set the image destination and rename the image
						$imageDestination = "images/InstituteDetail/".$maxID.".".$ext;

						#Upload the image to specific folder
						move_uploaded_file($_FILES['newsImage']['tmp_name'][$i], $imageDestination);

						#Conenct to database to insert the image path and id
						$conn = new mysqli(SERVER,USER,PASS,DB);

						#Close the page if unable to create connection
						if($conn->connect_error)
							die ("Connection Failed".$conn->connect_error);

						$sql = "CALL InsertGalleryNews(\"$imageDestination\", $newsID)";

						if(!($conn->query($sql)))
							echo "Error. SQL execute failed.".$conn->error; 
						
						$conn->close();
					}	
				}	
			}
		}
		else
		{
			echo "Error. SQL execute failed.".$conn->error; 
			$conn->close();
		}
		
		header("location:$self");	
	}

	#if post request and new course is added
	else if(isset($_POST["newCourse"]))
	{
		#Check whether the input is valid
		if(isset($_POST["courseID"]) && isset($_POST["duration"])&& isset($_POST["fee"]))
		{
			#Create a connection to database to insert the new course
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);

			$courseID = $conn->real_escape_string($_POST['courseID']);
			$fee = $conn->real_escape_string($_POST['fee']);
			$duration = $conn->real_escape_string($_POST['duration']);

			$sql = "INSERT INTO `institute_course` 
					VALUES({$admin->getInstitute()->getINstituteID()}, $courseID, $fee, $duration)";

			#Check whether the query is valid
			if(!($conn->query($sql)))
				echo "Error. SQL execute failed.".$conn->error; 

			$conn->close();
			
			#Redirect to this page
			header("location:$self");			
		}
	}

	else if(isset($_POST["courseID"]))
	{
		#Create a connection to database to delete a course
		$conn = new mysqli(SERVER,USER,PASS,DB);

		$courseID = $conn->real_escape_string($_POST['courseID']);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$sql = "DELETE FROM `institute_course` 
				WHERE institute_id = {$admin->getInstitute()->getINstituteID()} && course_id = $courseID";

		#Check whether the query is valid
		if(!($conn->query($sql)))
			echo "Error. SQL execute failed.".$conn->error;

		$conn->close();

		#Redirect to this page
		header("location:$self");	
		exit();
	}


/***************************** GENERATE VIEW ******************************/
	#Table in the course tab
	$course = "";

	#Dropdown in course selection in course table 
	$courseAvailable = "";


	$news = "";

	#Store the html element that contain image (Gallery)
	$image = "";

	#Store the counter of number of image
	$count = 0;

	#Store whether the row is ended in gallery division 
	$endRow = FALSE;

	#Store the number of institute available course
	$numOfCourse = sizeof($admin->getInstitute()->getCourse());

	#Store the number of institute news
	$numOfNews = sizeof($admin->getInstitute()->getNews());
	
	#Create a connection to database to get all the course available
	$conn = new mysqli(SERVER,USER,PASS,DB);

	#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);
		
	$sql = "Select * FROM course";

	if($result = $conn->query($sql))
		while($courseName = $result->fetch_assoc())
			$courseAvailable .= "<option value='$courseName[course_id]'>$courseName[course_name]</option>";
	else
		echo "Error. SQL execute failed.".$conn->error;

	$conn->close();

	#Print all the institute available course
	for($i = 0; $i < $numOfCourse; $i++)
	{
		#Index of course
		$j = 1 + $i;

		$course .=			
		<<<COURSE
			<tr>
				<td>
					$j
				</td>
				<td>
					<h6>{$admin->getInstitute()->getCourse()[$i]->getCourseName()}</h6>
				</td>
				<td>
					{$admin->getInstitute()->getCourse()[$i]->getCourseDuration()}
				</td>
				<td>
					{$admin->getInstitute()->getCourse()[$i]->getCourseFee()}
				</td> 
				<td>
					<span class="pointer text-danger" onclick="deleteCourse(this)">Delete</span>
					<p hidden>{$admin->getInstitute()->getCourse()[$i]->getCourseID()}</p>
				</td>
			</tr>
COURSE;
	}

	#Print all the institute's news
	for($i = 0 ; $i < $numOfNews ; $i++)
	{
		$news.= 
		<<< NEW
			<div class="border rounded bg-white px-4 py-3 mb-3 pb-4">
				<div class="row d-flex  mb-2" >
					<div class="col-md-2 mr-0 pr-0 align-items-center">
						<img src="{$admin->getInstitute()->getProfile()}" class="circle-profile-image">
					</div>
					<div class="col-md-9 ml-0 pl-0 align-items-center">
						<h6 class="font-weight-bold mb-0 pb-0">{$admin->getInstitute()->getInstituteName()}</h6>
						<span class="mt-0 pt-0" style="font-size:10px">{$admin->getInstitute()->getNews()[$i]->getTimeStamp()}</span>
					</div>
					<div class="col-md-1">
						<form action="$self" method="post">
							<i class="far fa-trash-alt text-danger pointer" onclick="deleteNews(this)"></i>
							<input type="hidden" value={$admin->getInstitute()->getNews()[$i]->getNewsID()} name="newsID"/>
						</form>
					</div>
				</div>
				<h6>{$admin->getInstitute()->getNews()[$i]->getContent()}</h6>
NEW;
		#Store the number of image of a new
		$sizeOfImage = sizeof($admin->getInstitute()->getNews()[$i]->getImage());

		#Print all the image for each new
		for($j = 0 ; $j < $sizeOfImage ; ++$j)
		{
			#Insert a new row in gallery if the row has contain already 3 images
			if($count % 3 == 0)
			{
				$endRow = FALSE;
				$image.= "<div class='row mt-2'>";
			}

			#Store the html element that contain the image in gallery
			$image.= 
			<<<IMAGE
				<div class="col-md-4" id="galCol" style="overflow:hidden">
					<img src="{$admin->getInstitute()->getNews()[$i]->getImage()[$j]->getImagePath()}" class="galImage img-fluid rounded border" style="min-width:100%;" >
				</div>
IMAGE;
			
			#Check whether there are 3 images in a row
			#If yes, add a new row
			if(($count + 1) % 3 == 0)
			{
				$endRow = TRUE;
				$image.="</div>";
			}	

			#Increment the number of image
			$count++;

			#Append the new's image to news html element
			$news.= "<img class='img-fluid mb-3 border' src='{$admin->getInstitute()->getNews()[$i]->getImage()[$j]->getImagePath()}' />";
		}

		$news.="</div>";
	}

	if(!$endRow)
		$image.="</div>";

	$body = <<<BODY
			<main class="main">
			<div hidden id="courseSelection">
				<option value="" disabled selected>Select Course</option>
				$courseAvailable
			</div>
				<div class='container d-flex justify-content-center'>
		<div class='collegeDetail'>	
			<div class="view border rounded" height=100%>
				<img src="{$admin->getInstitute()->getCover()}" height=300px width=100% alt=""/>

				<div class='mask d-flex mr-auto'>		
					<img src="{$admin->getInstitute()->getProfile()}" class='circle-image ml-5 mb-3 mt-auto'  style="z-index:1;"/>
				</div>
				<div class='mask d-flex justify-content-center'>		
					<h1 class="font-weight-bold mt-auto mb-5 text-white" style="text-shadow: 1px 1px 2px black;">{$admin->getInstitute()->getInstituteName()}</h1>
				</div>

				<div class='mask d-flex'>
					<nav class="bg-white mt-auto col-md-12">
						<ul class="nav nav-tabs" style="padding-left:230px;">
						    <li class="nav-item">
						      	<a class="nav-link active" data-toggle="tab" href="#overview">Overview</a>
						    </li>
						    <li class="nav-item">
						      	<a class="nav-link" id="courseNav" data-toggle="tab" href="#course">Course</a>
						    </li>
						    <li class="nav-item">
						      	<a class="nav-link" data-toggle="tab" href="#gallery" >Gallery</a>
						    </li>
					  	</ul>
			  		</nav>	
		  		</div>
			</div>
			<div class="rounded border tab-content mt-2 mb-5">
	  			<div class="tab-pane active container p-3" id="overview">
	  			<div class="row"><div class="col-md-4" id="loadGallery"></div></div>
	  				<div class="row">
	  					<div class="col-md-5 pl-0" style="height:60vh;max-height:591px">
	  						<div class="bg-white border rounded px-4 py-3 mb-3">
	  							<h5><i class="far fa-thumbs-up pr-2"></i>Rating and Review</h5>
	  							<hr />
		  							<div style="min-height:40px">
			  							{$admin->getInstitute()->printRate("star")}
		  							</div>
	  						</div>

	  						<div class="bg-white border rounded px-4 py-3 mb-3">
		  						<h5><i class="far fa-clipboard pr-2"></i>Details</h5>
		  						<hr/>
		  						<div class="mb-3">
			  						<span>Address:</span><p><a href="{$admin->getInstitute()->getInstituteAddressURL()}">{$admin->getInstitute()->getInstituteAddress()}</a></p>
			  						<iframe class="border rounded" src="{$admin->getInstitute()->getInstituteIFrame()}" frameborder="0" style="border:0; width:100%" allowfullscreen></iframe>
		  						</div>
		  						<div class="mb-3">
		  							<span class="mr-2">State:</span><a href="{$admin->getInstitute()->getState()->getStateURL()}">{$admin->getInstitute()->getState()->getStateName()}</a>
		  						</div>
		  						<h6>Course Offer: $numOfCourse</h6>
	  						</div>
	  					</div>
  		
  						<div class="col-md-7 px-0">
		  					<div style="min-height:591px;">
		  						<div class="bg-white border rounded px-4 py-2 mb-3">
		  							<form action=$self method="post" enctype="multipart/form-data">
		  								<h5><i class="fas fa-pencil-alt pr-2"></i>New Post</h5>
	  									<div class="md-form mb-2 py-0">
										  	<textarea class="rounded form-control textareaPH px-3 py-2" placeholder="Write something" name="content" rows="3"></textarea>
										</div>
										<div class="mb-3">
											<div class="input-group">
											  	<div class="input-group-prepend">
											    	<span class="input-group-text">Upload</span>
											  	</div>
											  	<div class="custom-file">
												    <input type="file" name="newsImage[]" class="custom-file-input" id="inputGroupFile" accept="image/*" multiple>
												    <label class="custom-file-label" id="labelNumOfFile" for="inputGroupFile">Choose Image</label>
											  	</div>
											</div>
											<div class="mt-2">
												<ol class="pl-4" id="imageFiles">
												</ol>
											</div>
										</div>
										<div class="w-100 d-flex">		
											<input type="submit" class="btn btn-outline-secondary waves-effect ml-auto py-2 w-100 mb-4" value="Post">
										</div>
		  							</form>
		  						</div>
			  					<div class="bg-white border rounded px-4 py-2 mb-3">
		  							<h5><i class="far fa-newspaper pr-2"></i>News</h5>
		  						</div>
		  						$news
  							</div>
						</div>
					</div>
	  			</div>
	  			<div class="tab-pane container fade px-4 py-3 bg-white border rounded" id="course">
	  				<h5><i class="fas fa-book pr-2"></i>Course Available</h5>
	  				<hr/>
	  				<form action="$self" id="courseAppend" method="post">
	  					<table class="table table-hover">
			  				<thead>
			  					<tr>	
			  						<th>#</th>
			  						<th>Course Name</th>
			  						<th>Duration (Years)</th>
			  						<th>Fees (RM)</th>
			  					</tr>
			  				</thead>
			  				<tbody id="courseDetail">
			  					$course
			  				</tbody>
			  				<tbody>
			  					<tr>
			  						<td colspan="5" class="pointer" id="addInstitute"><i class="fas fa-plus pr-2"></i>Add course</td>
			  					</tr>
			  				</tbody>
	  				</table>	
	  				</form>
	  			</div>
	  			<div class="tab-pane container fade px-4 py-3 bg-white border rounded" id="gallery">
	  				<h5><i class="far fa-images pr-2"></i>Gallery</h5>
	  				<hr/>
	  				$image
	  			</div>
			</div>		
		</div>
	</div>
	</main>
BODY;

/************************************* VIEW *************************************/

	echo "<!DOCTYPE html>";
		echo "<html lang='en' class='h-100'>";
			echo "<head>";
				include("header.html");	
				echo "<script src='style/style.js'></script>";	
				echo "<script src='style/manageInstitute.js'></script>";				
			echo "</head>";

			echo "<body class='bg-light h-100'>";
				include("nav.php");
				echo $body;
				include("footer.php");
			echo "</body>";
	echo "</html>";
?>