<?php
require("models/users.php");
	session_start();
	echo "<!DOCTYPE html>";
		echo "<html lang='en' class='h-100'>";
			echo "<head>";
				include("header.html");	
				echo "<script src='style/style.js'></script>";					
			echo "</head>";
			echo "<body class='bg-light h-100'>";
			include("nav.php");
$self = htmlspecialchars($_SERVER['PHP_SELF']);
echo <<<BODY
	<main class='main'>
	<div class='container d-flex justify-content-center'>
		<div class='collegeDetail'>	
			<div class="view border rounded" height=100%>
				<img src="//localhost/php_project/images/cover/inti.jpg" height=300px width=100% alt=""/>

				<div class='mask d-flex mr-auto'>		
					<img src="//localhost/php_project/images/logo/inti.jpg" class='circle-image ml-5 mb-3 mt-auto' style="z-index:1;"/>
				</div>
				<div class='mask d-flex'>
					<nav class="bg-white mt-auto col-md-12">
						<ul class="nav nav-tabs" style="padding-left:230px;">
						    <li class="nav-item">
						      	<a class="nav-link active" data-toggle="tab" href="#overview">Overview</a>
						    </li>
						    <li class="nav-item">
						      	<a class="nav-link" data-toggle="tab" href="#course">Course</a>
						    </li>
						    <li class="nav-item">
						      	<a class="nav-link" data-toggle="tab" href="#gallery">Gallery</a>
						    </li>
					  	</ul>
			  		</nav>	
		  		</div>
			</div>
			<div class="rounded border tab-content mt-2">
	  			<div class="tab-pane container active p-3" id="overview">
	  				<div class="row">
	  					<div class="col-md-6 pl-0">
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
			  						<span>Address:</span><p><a href="https://goo.gl/maps/kx4bFj8DGSf42p4f9">1-Z, Lebuh Bukit Jambul, Bukit Jambul, 11900 Bayan Lepas, Pulau Pinang</a></p>
			  						<iframe class="border rounded" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.491988724782!2d100.27968201549743!3d5.34160379612528!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ac048a161f277%3A0x881c46d428b3162c!2sINTI+International+College+Penang!5e0!3m2!1sen!2smy!4v1560696521934!5m2!1sen!2smy" frameborder="0" style="border:0" allowfullscreen></iframe>
		  						</div>
		  						<div class="mb-3">
		  							<span class="mr-2">State:</span><a href=https://goo.gl/maps/Ut1mvNnb3FFgnQv6A">Penang</a>
		  						</div>
		  						<h6>Course Offer: 100</h6>
	  						</div>
	  					</div>
  		
  						<div class="col-md-6 px-0">
		  					<div class="bg-white border rounded px-4 py-3" style="min-height:567px;">
		  						<h5><i class="far fa-newspaper pr-2"></i>News</h5>
		  						<hr/>
		  						Bla Bla Bla
  							</div>
						</div>
					</div>
	  			</div>
	  			<div class="tab-pane container fade p-3 bg-white border rounded" id="course">
	  				course
	  			</div>
	  			<div class="tab-pane container fade p-3 bg-white border rounded" id="gallery">
	  				gallery
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
