<?php

	class Wrapper
	{
		public static function drawline($color)
		{
			return "<hr class='$color my-0' style=\"height:3px\"/>";
		}

		public static function breakLine($times)
		{
			$output = "";
			for($i = 0; $i < $times ; $i++)
				$outpout += "</br>"; 
			return $output;
		}
	}

	$wrapper = new Wrapper();

	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
		echo "</head>";


		echo "<body>";
		include("nav.php");

echo <<< BODY
			<header>		
			<div id='intro' class='view'>
				<div class='mask rgba-black-strong'>
					<div class='container-fluid d-flex align-items-center justify-content-center h-100'>
						<div class='row d-flex justify-content-center text-center'>
							<div class='col-md-10'>
								<h2 class='display-4 font-weight-bold text-light pt-5 mb-2'>LET US HELP YOU CRAFT YOUR FUTURE</h2>
								{$wrapper->drawline("bg-light")}
								<h4 class='text-light my-4'>Enabling our students to thrive in college life and BEYOND!</h4>
								<a class='btn btn-outline-white' href='#college_in_Malaysia'>Find Your Future<i class='fa fa-book ml-2'></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			</header>
			<main class='mt-5 main'>
				<div class='container' id='college_in_Malaysia'>
					<section id='examples' class='text-center'>
						<h2 class='mb-5 font-weight-bold'>Colleges in Malaysia</h2>
						<div class='row'>
							<div class='col-lg-4 col-md-12 mb-4'>
								<div class='view overlay z-depth-1-half'>
									<img src='images/home/college-inti.jpg' height=200px width=100% class='img-fluid' alt=''>
									<div class='mask rgba-white-slight'></div>
								</div>
								<h4 class='my-4 font-weight-bold'>INTI College Subang</h4>
							</div>

							<div class='col-lg-4 col-md-6 mb-4'>
								<div class='view overlay z-depth-1-half'>
									<img src='images/home/college-segi.jpg' height=200px width=100% alt=''>
									<div class='mask rgba-white-slight'></div>
								</div>
								<h4 class='my-4 font-weight-bold'>SEGI College Penang</h4>
							</div>

							<div class='col-lg-4 col-md-6 mb-4'>
								<div class='view overlay z-depth-1-half'>
									<img src='images/home/college-tarc.jpg' height=200px width=100% alt=''>
									<div class='mask rgba-white-slight'></div>
								</div>
								<h4 class='my-4 font-weight-bold'>Tunku Abdul Rahman College Kuala Lumpur</h4>
							</div>
						</div>

						<div class='row'>
							<div class='col-lg-4 col-md-12 mb-4'>
								<div class='view overlay z-depth-1-half'>
									<img src='images/home/college-kdu.jpg' height=200px width=100% alt=''>
									<div class='mask rgba-white-slight'></div>
								</div>
								<h4 class='my-4 font-weight-bold'>KDU College Penang</h4>
							</div>

							<div class='col-lg-4 col-md-6 mb-4'>
								<div class='view overlay z-depth-1-half'>
									<img src='images/home/college-equator.jpg' height=200px width=100% alt=''>
									<div class='mask rgba-white-slight'></div>
								</div>
								<h4 class='my-4 font-weight-bold'>Equator College Penang</h4>
							</div>

							<div class='col-lg-4 col-md-6 mb-4'>
								<div class='view overlay z-depth-1-half'>
									<img src='images/home/college-medical.jpg' height=200px width=100% alt=''>
									<div class='mask rgba-white-slight'></div>
								</div>
								<h4 class='my-4 font-weight-bold'>Penang Medical College</h4>
							</div>
						</div>						
						<a href='college.php' class='btn btn-outline-secondary btn-rounded waves-effect'>View More</a>				
					</section>
				</div>
			</main>
BODY;
			include("footer.php");
		echo "</body>";
	echo "</html>";
?>