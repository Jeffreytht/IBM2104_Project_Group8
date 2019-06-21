<?php
	
	if(!$_GET)
		header("Location:institute.php");

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

	$self = htmlspecialchars($_SERVER['PHP_SELF']);
	$course= "";
	$i = 0;

	$conn = mysqli_connect(SERVER,USER,PASS,DB);
	$sql = "CALL SelectInstituteCourse($_GET[id])";
	$result = $conn->query($sql);

	while($courseDet = $result -> fetch_assoc())
	{

		$i++;
		$course .= <<<COURSE
			<tr>
				<td>
					$i
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
	$conn->close();

	$conn = mysqli_connect("localhost","root","","college_portal");
	$sql = "CALL SelectInstituteDetails($_GET[id])";
	$result = $conn->query($sql);

	$institute = new Institute();
	$institute->assignInstitute($result ->fetch_assoc());

	$anotherConns = new mysqli(SERVER,USER,PASS,DB);
	$sql = "SELECT g.image_path 
			FROM institute_logo il, gallery g 
			WHERE il.institute_id = $_GET[id] && g.image_id = il.image_id";

	$anotherResult = $anotherConns->query($sql);
	$selectedLogo = $anotherResult->fetch_assoc();
	$anotherConns->close();

	$institute->setInstituteLogo($selectedLogo['image_path']);
	$conn->close();

echo "<!DOCTYPE html>";
		echo "<html lang='en' class='h-100'>";
			echo "<head>";
				include("header.html");	
				echo "<script src='style/style.js'></script>";					
			echo "</head>";
			echo "<body class='bg-light h-100'>";
			include("nav.php");

echo <<<BODY
	<main class='main'>
	<div class='container d-flex justify-content-center'>
		<div class='collegeDetail'>	
			<div class="view border rounded" height=100%>
				<img src="{$institute->getCover()}" height=300px width=100% alt=""/>

				<div class='mask d-flex mr-auto'>		
					<img src="{$institute->getProfile()}" class='circle-image ml-5 mb-3 mt-auto'  style="z-index:1;"/>

				</div>

				<div class='mask d-flex justify-content-center'>		
					<h1 class="font-weight-bold mt-auto mb-5 text-white" style="text-shadow: 1px 1px 2px black;">{$institute->getInstituteName()}</h1>
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
	  			<div class="row"><div class="col-md-4"  id="loadGallery"></div></div>
	  				<div class="row">
	  					<div class="col-md-5 pl-0">
	  						<div class="bg-white border rounded px-4 py-3 mb-3">
	  							<h5><i class="far fa-thumbs-up pr-2"></i>Rate Us</h5>
	  							<hr />
	  							<form id="starForm" action="$self" method="get">
		  							<div style="min-height:40px">
			  							<i class="far fa-star star checked" id="star1"></i>
			  							<i class="far fa-star star checked" id="star2"></i>
			  							<i class="far fa-star star checked" id="star3"></i>
			  							<i class="far fa-star star checked" id="star4"></i>
			  							<i class="far fa-star star checked" id="star5"></i>
			  							<input type="hidden" id="starValue" name="rate" value=""/>
		  							</div>
	  							</form>
	  						</div>

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
		  						<h6>Course Offer: $i</h6>
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
	  				<div class="row mt-2">
	  					<div class="col-md-4" id="galCol" style="overflow:hidden">
							<img src="images/instituteDetail/1.jpg" class="galImage rounded" style="min-width:100%;" >
	  					</div>
	  					<div class="col-md-4" id="galCol" style="overflow:hidden">
							<img src="images/instituteDetail/2.jpg" class="galImage rounded" style="min-width:100%;" >
	  					</div>
	  					<div class="col-md-4" id="galCol" style="overflow:hidden">
							<img src="images/instituteDetail/3.jpg" class="galImage rounded" style="min-width:100%;" >
	  					</div>

	  				</div>
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
