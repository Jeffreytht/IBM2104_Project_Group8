<?php
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");

	session_start();

	if(!(isset($_SESSION['admin'])))
		header("Location: index.php");

	define("SERVER","localhost");
	define("USER", "root");
	define("PASS","");
	define("DB","college_portal");

	$self = htmlspecialchars($_SERVER['PHP_SELF']);
	$admin = $_SESSION['admin'];
	$admin->assignAdmin();
	$newsID = "";
	
	if(isset($_POST["content"]))
	{
		$conn = mysqli_connect(SERVER,USER,PASS,DB);
		$sql = "INSERT INTO news (content,institute_id) VALUES(\"$_POST[content]\",\"{$admin->getInstitute()->getInstituteID()}\")";
		$conn->query($sql);
		$conn->close();

		$conn = mysqli_connect(SERVER,USER,PASS,DB);
		$sql = "SELECT MAX(news_id) as news_ID FROM news";
		$result = $conn->query($sql);
		$newsID = $result->fetch_assoc()['news_ID'];
		$conn->close();

		if (isset($_FILES["newsImage"])) 
		{
			$maxID = 0;
			$conn = mysqli_connect(SERVER,USER,PASS,DB);
			$sql = "SELECT MAX(image_id) as maxID FROM gallery";
			$result = $conn->query($sql);
			$maxID = $result->fetch_assoc()['maxID'];
			$conn->close();

			$sizeOfFile = sizeof($_FILES['newsImage']['name']);

			for($i = 0; $i < $sizeOfFile; $i++)
			{
				$maxID++;
				$ext = pathinfo($_FILES['newsImage']['name'][$i], PATHINFO_EXTENSION);
				$imageDestination = "images/InstituteDetail/".$maxID.".".$ext;

				move_uploaded_file($_FILES['newsImage']['tmp_name'][$i], $imageDestination);

				$conn = mysqli_connect(SERVER,USER,PASS,DB);
				$sql = "CALL InsertGalleryNews(\"$imageDestination\", $newsID)";

				$conn->query($sql);
				$conn->close();
			}	
		}
	}

	else if(isset($_POST["newCourse"]))
	{
		if(isset($_POST["courseID"]) && isset($_POST["duration"])&& isset($_POST["fee"]))
		{
			$conn = mysqli_connect(SERVER,USER,PASS,DB);
			$sql = "INSERT INTO `institute_course` VALUES({$admin->getInstitute()->getINstituteID()}, $_POST[courseID], $_POST[fee], $_POST[duration])";
			$conn->query($sql);
			$conn->close();
			header("location:$self");			
		}
	}

	else if(isset($_POST["courseID"]))
	{
		$conn = mysqli_connect(SERVER,USER,PASS,DB);
		$sql = "DELETE FROM `institute_course` WHERE institute_id = {$admin->getInstitute()->getINstituteID()} && course_id = $_POST[courseID]";
		$conn->query($sql);
		$conn->close();
		header("location:$self");	
	}

	$course = "";
	$courseSelection = "";
	$news = "";
	$image = "";
	$count = 0;
	$endRow = FALSE;
	$numOfCourse = sizeof($admin->getInstitute()->getCourse());
	$sizeOfNews = sizeof($admin->getInstitute()->getNews());
	
	$conn = new mysqli(SERVER,USER,PASS,DB);
	$sql = "Select * FROM course";
	$result = $conn->query($sql);
	while($courseName = $result->fetch_assoc())
	{
		$courseSelection .= "<option value='$courseName[course_id]'>$courseName[course_name]</option>";
	}


	for($i = 0; $i < $numOfCourse; $i++)
	{
		// Index of course
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

	for($i = 0 ; $i < $sizeOfNews ; $i++)
	{
		
		$news.= 
		<<< NEW
			<div class="border rounded bg-white px-4 py-3 mb-3 pb-4">
				<div class="row d-flex align-items-center mb-2" >
					<div class="col-md-2 mr-0 pr-0">
						<img src="{$admin->getInstitute()->getProfile()}" class="circle-profile-image">
					</div>
					<div class="col-md-10 ml-0 pl-0">
						<h6 class="font-weight-bold mb-0 pb-0">{$admin->getInstitute()->getInstituteName()}</h6>
						<span class="mt-0 pt-0" style="font-size:10px">{$admin->getInstitute()->getNews()[$i]->getTimeStamp()}</span>
					</div>
				</div>

				<h6>{$admin->getInstitute()->getNews()[$i]->getContent()}</h6>
NEW;

		$sizeOfImage = sizeof($admin->getInstitute()->getNews()[$i]->getImage());
		for($j = 0 ; $j < $sizeOfImage ; ++$j)
		{
			if($count % 3 == 0)
			{
				$endRow = FALSE;
				$image.= "<div class='row mt-2'>";
			}

			$image.= 
			<<<IMAGE
				<div class="col-md-4" id="galCol" style="overflow:hidden">
					<img src="{$admin->getInstitute()->getNews()[$i]->getImage()[$j]->getImagePath()}" class="galImage rounded border" style="min-width:100%;" >
				</div>
IMAGE;

			if(($count + 1) % 3 == 0)
			{
				$endRow = TRUE;
				$image.="</div>";
			}	

			$count++;

			$news.= "<img class='img-fluid mb-3 border' src='{$admin->getInstitute()->getNews()[$i]->getImage()[$j]->getImagePath()}' />";
		}

		$news.="</div>";
	}

	if(!$endRow)
		$image.="</div>";

	echo "<!DOCTYPE html>";
		echo "<html lang='en' class='h-100'>";
			echo "<head>";
				include("header.html");	
				echo "<script src='style/style.js'></script>";	
				echo "<script src='style/manageInstitute.js'></script>";				
			echo "</head>";

			echo "<body class='bg-light h-100'>";
				include("nav.php");

	echo <<<BODY
			<main class="main">
			<div hidden id="courseSelection">
				<option value="" disabled selected>Select Course</option>
				$courseSelection
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
			  						<td colspan="5" class="pointer" id="addInstitute"><i class="fas fa-plus pr-2"></i>Add institute</td>
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
			include("footer.php");
		echo "</body>";
	echo "</html>";
?>