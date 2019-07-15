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

	$userID = 0;
	$institute_id = 0;

	#Store institute details
	$institute = new Institute();

	if(isset($_GET['id']))
		$institute_id = $_GET['id'];

	else if(isset($_POST['instituteID']))
		$institute_id = $_POST['instituteID'];

	else
		header("Location:institute.php");
	
	#Assign user information based on role id
	if(isset($_SESSION['role']))
	{
		switch($_SESSION['role'])
		{
			case 1:
		    		$userID = $_SESSION['superAdmin']->getUserID();
		    	break;

		    	case 2:
		    		$userID = $_SESSION['admin']->getUserID();
		    	break;

		    	case 3:
		    		$userID = $_SESSION['user']->getUserID();
		    	break;
		}
	}


	if($_POST)
	{	
		if(isset($_POST['rate']) && !empty($userID))
		{
			#Create a connection to database to insert user's rating
			$conn = new mysqli(SERVER,USER,PASS,DB);

			#Close the page if unable to create connection
			if($conn->connect_error)
				die ("Connection Failed".$conn->connect_error);

			$postRate = $conn->real_escape_string($_POST["rate"]);
			$userID = $conn->real_escape_string($userID);
			$institute_id = $conn->real_escape_string($institute_id);

			#SQL command to insert rating
			$sql = "INSERT INTO `rate` VALUES($userID , $institute_id, $postRate)";
			if(!$conn->query($sql))
				echo $conn->error;

			$conn->close();
			echo "<script>alert(\"$instituteID\")</script>";
			header("Location:$self?id=$institute_id");	
		}

		else
		{
			#Redirect to sign in page and ask user to sign in before rating
			echo '
			<script>
				alert("Please sign in to rate");
				window.location.replace("sign_in.php");
			</script>';
		}	
	}
	
	#Create a connection to database to get the institute details
	$conn = mysqli_connect(SERVER,USER,PASS,DB);

	#Close the page if unable to create connection
	if($conn->connect_error)
		die ("Connection Failed".$conn->connect_error);

	$institute_id = $conn->real_escape_string($institute_id);

	#SQL command to call the stored procedure in database 
	$sql = "CALL SelectInstituteDetails($institute_id)";

	#Check whether the query is valid.
	#Return all the institute details, state details and admin details
	if($result = $conn->query($sql))
		$institute->assignInstitute($result ->fetch_assoc());
	else
		die("Error.".$conn->error);
	$conn->close();

	#Create a new connection to database to get the institute logo image path
	$conn = new mysqli(SERVER,USER,PASS,DB);

	#Close the page if unable to create connection
	if($conn->connect_error)
		die ("Connection Failed".$conn->connect_error);

	$institute_id = $conn->real_escape_string($institute_id);

	$sql = "SELECT g.image_path 
			FROM institute_logo il, gallery g 
			WHERE il.institute_id = $institute_id && g.image_id = il.image_id";

	#Check whether the query is valid.
	#Return institute logo image path
	if($result = $conn->query($sql))
	{
		$output = $result->fetch_assoc();
		$institute->setInstituteLogo($output['image_path']);
	}
	else
		echo "Error. SQL execute failed.".$conn->error;

	$conn->close();

/******************************** GENERATE VIEW *******************************************/

	#Store the html element that contain the course information
	$course= "";

	#Store the number of course
	$numOfCourse = 0;

	#Create a connection to database to get institute information
	$conn = mysqli_connect(SERVER,USER,PASS,DB);

	#Close the page if unable to create connection
	if($conn->connect_error)
		die ("Connection Failed".$conn->connect_error);

	$institute_id = $conn->real_escape_string($institute_id);

	#SQL command that call the stored procedure in database
	$sql = "CALL SelectInstituteCourse($institute_id)";

	if($result = $conn->query($sql))
	{
		while($courseDet = $result -> fetch_assoc())
		{
			#increment the number of course
			$numOfCourse++;

			$course .= <<<COURSE
				<tr>
					<td>
						$numOfCourse
					</td>
					<td>
						<h6>$courseDet[course_name]</h6>
					</td>
					<td>
						$courseDet[duration]
					</td>
					<td>
						$courseDet[fee]
					</td>
				</tr>
COURSE;
		}
	}
	else
		echo "Error. SQL execute failed.".$conn->error;

	$conn->close();


$rateDivision =<<< RATE
		<div class="bg-white border rounded px-4 py-3 mb-3">
			<h5><i class="far fa-thumbs-up pr-2"></i>Rate Us</h5>
			<hr />
			<form id="starForm" action="$self" method="post">
				<div style="min-height:40px">
					<i class="far fa-star star checked" id="star1"></i>
					<i class="far fa-star star checked" id="star2"></i>
					<i class="far fa-star star checked" id="star3"></i>
					<i class="far fa-star star checked" id="star4"></i>
					<i class="far fa-star star checked" id="star5"></i>
					<input type="hidden" id="starValue" name="rate" value=""/>
					<input type="hidden" name="instituteID" value="$institute_id"/>
				</div>
			</form>
		</div>
RATE;

	
	#Store the html element that contain news
	$news = "";

	#Store the html element that contain image in the gallery
	$image = "";

	#Store the counter of the image in the gallery
	$count = 0;

	#indicate whether the row is end (NEWS IMAGE)
	$endRow = FALSE;
	

	#Check whether the user has already rate the institute.
	#If yes, disable rating division
	if(!empty($userID))
	{
		#Create a connection to  database to get the institute rating
		$conn = new mysqli(SERVER,USER,PASS,DB);

		#Close the page if unable to create connection
		if($conn->connect_error)
			die ("Connection Failed".$conn->connect_error);

		$userID = $conn->real_escape_string($userID);
		$institute_id = $conn->real_escape_string($institute_id);
	
		$sql = "SELECT * FROM `rate` WHERE user_id = $userID && institute_id = $institute_id";

		if($result = $conn->query($sql))
		{
			if($result->num_rows > 0)
			{
				$rateDivision ="";
			}
		}
		else
			echo "Error. SQL execute failed.".$conn->error;

		$conn->close();
	}

	#Get the number of news of the institute
	$sizeOfNews = sizeof($institute->getNews());

	#Loop all the news to the page
	for($i = 0 ; $i < $sizeOfNews ; $i++)
	{
		$news.= 
		<<< NEW
			<div class="border rounded bg-white px-4 py-3 mb-3 pb-4">
				<div class="row d-flex align-items-center mb-2" >
					<div class="col-md-2 mr-0 pr-0">
						<img src="{$institute->getProfile()}" class="circle-profile-image">
					</div>
					<div class="col-md-10 ml-0 pl-0">
						<h6 class="font-weight-bold mb-0 pb-0">{$institute->getInstituteName()}</h6>
						<span class="mt-0 pt-0" style="font-size:10px">{$institute->getNews()[$i]->getTimeStamp()}</span>
					</div>
				</div>

				<h6>{$institute->getNews()[$i]->getContent()}</h6>
NEW;
		#Get the number of image of a page
		$sizeOfImage = sizeof($institute->getNews()[$i]->getImage());

		#Loop to print all the image to the news
		for($j = 0 ; $j < $sizeOfImage ; $j++)
		{
			#Create a new row for every 3 lines
			if($count % 3 == 0)
			{
				#indicate whether the row is end
				$endRow = FALSE;
				$image.= "<div class='row mt-2 px-3'>";
			}

			$image.= 
			<<<IMAGE
				<div class="col-md-4 rounded border pl-0" id="galCol" style="overflow:hidden;">
					<img src="{$institute->getNews()[$i]->getImage()[$j]->getImagePath()}" class="galImage " style="min-width:100%" >
				</div>
IMAGE;
			#Check whether the row is end
			if(($count + 1) % 3 == 0)
			{
				$endRow = TRUE;
				$image.="</div>";
			}	

			$count++;

			#Print image of the news
			$news.= "<img class='img-fluid mb-3 border' src='{$institute->getNews()[$i]->getImage()[$j]->getImagePath()}' />";
		}

		$news.="</div>";
	}

	#If the gallery image row havent end, end it
	if(!$endRow)
		$image.="</div>";

$body = <<<BODY
	<main class='main'>
	<div class='container d-flex justify-content-center'>
		<div class='collegeDetail'>	
			<div class="view border rounded" height=100%>
				<img src="{$institute->getCover()}" height=300px width=100% alt=""/>

				<div class='mask d-flex mr-auto'>		
					<img src="{$institute->getProfile()}" class='circle-image ml-5 mb-3 mt-auto'  style="z-index:1;"/>
				</div>

				<div class='mask d-flex justify-content-center'>		
					<h1 class="font-weight-bold mt-auto mb-5 ml-auto mr-4 text-white" style="text-shadow: 1px 1px 2px black;">{$institute->getInstituteName()}</h1>
				</div>

				<div class='mask d-flex'>
					<nav class="bg-white mt-auto col-md-12">
						<ul class="nav nav-tabs" style="padding-left:230px;">
						    <li class="nav-item">
						      	<a class="nav-link active" data-toggle="tab" href="#overview">Overview</a>
						    </li>
						    <li class="nav-item">
						      	<a class="nav-link " data-toggle="tab" href="#course">Course</a>
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
	  					<div class="col-md-5 pl-0">
	  						<div class="bg-white border rounded px-4 py-3 mb-3">
	  							<h5><i class="far fa-thumbs-up pr-2"></i>Rating and Review</h5>
	  							<hr />
		  							<div style="min-height:40px">
			  							{$institute->printRate("star")}
		  							</div>
	  						</div>

	  						$rateDivision

	  						<div class="bg-white border rounded px-4 py-3 mb-3">
		  						<h5><i class="far fa-clipboard pr-2"></i>Details</h5>
		  						<hr/>
		  						<div class="mb-3">
			  						<span>Address:</span><p><a href="{$institute->getInstituteAddressURL()}">{$institute->getInstituteAddress()}</a></p>
			  						<iframe class="border rounded" src="{$institute->getInstituteIFrame()}" frameborder="0" style="border:0; width:100%" allowfullscreen></iframe>
		  						</div>
		  						<div class="mb-3">
		  							<span class="mr-2">State:</span><a href="{$institute->getState()->getStateURL()}">{$institute->getState()->getStateName()}</a>
		  						</div>
		  						<h6>Course Offer: $numOfCourse</h6>
	  						</div>
	  					</div>
  		
  						<div class="col-md-7 px-0">
		  					<div style="min-height:591px;">
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
	  				<table class="table table-hover">
		  				<thead>
		  					<tr>	
		  						<th>#</th>
		  						<th>Course Name</th>
		  						<th>Duration (Years)</th>
		  						<th>Fees (RM)</th>
		  					</tr>
		  				</thead>
		  				<tbody>
		  					$course
		  				</tbody>
	  				</table>	
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

/*************************************** VIEW **********************************************/


echo "<!DOCTYPE html>";
		echo "<html lang='en' class='h-100'>";
			echo "<head>";
				include("header.html");	
				echo "<script src='style/style.js'></script>";					
			echo "</head>";
			echo "<body class='bg-light h-100'>";
				include("nav.php");
				echo $body;
				include("footer.php");
			echo "</body>";
		echo "</html>";
?>
