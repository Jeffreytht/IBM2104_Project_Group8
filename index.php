<?php
	function drawline($color)
	{
		echo "<hr class='$color my-0' style=\"height:3px\"/>";
	}

	function breakLine($times)
	{
		for($i = 0; $i < $times ; $i++)
			echo "</br>";
	}

	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body>";
		include("nav.php");
		echo "<header>";		
		echo "<div id='intro' class='view'>";
			echo "<div class='mask rgba-black-strong'>";
				echo "<div class='container-fluid d-flex align-items-center justify-content-center h-100'>";
					echo "<div class='row d-flex justify-content-center text-center'>";
						echo "<div class='col-md-10'>";
							echo "<h2 class='display-4 font-weight-bold text-light pt-5 mb-2'>LET US HELP YOU CRAFT YOUR FUTURE</h2>";
							drawline("bg-light");
							echo "<h4 class='text-light my-4'>Enabling our students to thrive in college life and BEYOND!</h4>";
							echo "<a class='btn btn-outline-white' href='#college_in_Malaysia'>Find Your Future<i class='fa fa-book ml-2'></i></a>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</header>";
			echo "<main class='mt-5'>";
				echo "<div class='container' id='college_in_Malaysia'>";
					echo"<section id='examples' class='text-center'>";
						echo"<h2 class='mb-5 font-weight-bold'>Colleges in Malaysia</h2>";
						echo"<div class='row'>";
							echo"<div class='col-lg-4 col-md-12 mb-4'>";
								echo"<div class='view overlay z-depth-1-half'>";
									echo"<img src='images/home/college-inti.jpg' height=200px width=100% class='img-fluid' alt=''>";
									echo"<div class='mask rgba-white-slight'></div>";
								echo"</div>";
								echo"<h4 class='my-4 font-weight-bold'>INTI College Subang</h4>";
							echo"</div>";

							echo"<div class='col-lg-4 col-md-6 mb-4'>";
								echo"<div class='view overlay z-depth-1-half'>";
									echo"<img src='images/home/college-segi.jpg' height=200px width=100% alt=''>";
									echo"<div class='mask rgba-white-slight'></div>";
								echo"</div>";
								echo"<h4 class='my-4 font-weight-bold'>SEGI College Penang</h4>";
							echo"</div>";

							echo"<div class='col-lg-4 col-md-6 mb-4'>";
								echo"<div class='view overlay z-depth-1-half'>";
									echo"<img src='images/home/college-tarc.jpg' height=200px width=100% alt=''>";
									echo"<div class='mask rgba-white-slight'></div>";
								echo"</div>";
								echo"<h4 class='my-4 font-weight-bold'>Tunku Abdul Rahman College Kuala Lumpur</h4>";
							echo"</div>";
						echo"</div>";

						echo"<div class='row'>";
							echo"<div class='col-lg-4 col-md-12 mb-4'>";
								echo"<div class='view overlay z-depth-1-half'>";
									echo"<img src='images/home/college-kdu.jpg' height=200px width=100% alt=''>";
									echo"<div class='mask rgba-white-slight'></div>";
								echo"</div>";
								echo"<h4 class='my-4 font-weight-bold'>KDU College Penang</h4>";
							echo"</div>";

							echo"<div class='col-lg-4 col-md-6 mb-4'>";
								echo"<div class='view overlay z-depth-1-half'>";
									echo"<img src='images/home/college-equator.jpg' height=200px width=100% alt=''>";
									echo"<div class='mask rgba-white-slight'></div>";
								echo"</div>";
								echo"<h4 class='my-4 font-weight-bold'>Equator College Penang</h4>";
							echo"</div>";

							echo"<div class='col-lg-4 col-md-6 mb-4'>";
								echo"<div class='view overlay z-depth-1-half'>";
									echo"<img src='images/home/college-medical.jpg' height=200px width=100% alt=''>";
									echo"<div class='mask rgba-white-slight'></div>";
								echo"</div>";
								echo"<h4 class='my-4 font-weight-bold'>Penang Medical College</h4>";
							echo"</div>";
						echo"</div>";						
						echo "<a href='college.php' class='btn btn-outline-secondary btn-rounded waves-effect'>View More</a>";						
					echo"</section>";
				echo"</div>";
			echo"</main>";
			include("footer.php");

		echo "</body>";
	echo "</html>";
?>