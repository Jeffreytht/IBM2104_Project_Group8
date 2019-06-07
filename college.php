<?php
	
	$location = array(	"Johor", 
						"Kedah",
						"Kelantan",
						"Malacca",
					  	"Negeri Sembilan",
					  	"Pahang",
					  	"Penang",
					  	"Perak",
					  	"Perlis",	
					  	"Sabah",
					  	"Sarawak",
					  	"Selangor",
					  	"Terengganu");

	$course = array(	"Accounting",
						"Agricultural Science",
						"Aquaculture",
						"Architecture",
						"Animation",
						"Aerospace",
						"Aircraft Maintenance",
						"Automotive Engineering",
						"Anthropology",
						"Actuarial Science",
						"Advertising",
						"Animal Science",
						"Audiology",
						"ACCA",
						"A-Levels",
						"American Degree Programme",
						"Banking",
						"Biomedical Science",
						"Business Administration",
						"Biomedical Engineering",
						"Biology",
						"Commerce",
						"Computer Engineering",
						"Computer Science",
						"Chemical Engineering",
						"Civil Engineering",
						"Culinary Arts",
						"Communications",
						"Counselling",
						"Chemistry",
						"College Foundations",
						"Canandian Pre-University",
						"Chemical Engineering with Oil and Gas",
						"Construction project management",
						"Design",
						"Dentistry",
						"Drama, Theatre & Film",
						"Economics",
						"Early Childhood Education",
						"Electrical Engineering",
						"Environmental Engineering",
						"Environmental Health",
						"Environmental Science",
						"Events Management",
						"Engineering Technology",
						"Food Technology",
						"Forensic Science",
						"Fashion Design",
						"Food Science",
						"Finance Management",
						"Game Design",
						"Graphic Design",
						"Geology",
						"Human Resource Management",
						"Hospitality Management",
						"History",
						"Interior Design",
						"Information System",
						"Industrial and Manufacturing Engineering",
						"Islamic Finance",
						"Industrial Electronics",
						"ICAEW",
						"Jurisprudence",
						"Journalism Broadcasting",
						"Language Studies",
						"Law",
						"Medical Laboratory Technology",
						"Multimedia",
						"Marketing",
						"Mechanical Engineering",
						"Marine Biology",
						"Marine Engineering",
						"Marine Science",
						"Mathematics",
						"Medicine",
						"Music",
						"Mechatronic Engineering",
						"Nursing",
						"Nutrition and Dietetics",
						"Nanotechnology",
						"Optometry",
						"Physiotherapy",
						"Piloting",
						"Petroleum Engineering",
						"Political Science",
						"Public Relations",
						"Pharmacy",
						"Psychology",
						"Physics",
						"Port Management",
						"Patisserie & Gastronomic Cuisine",
						"Quantity Surveying",
						"Radiography and Medical Imaging",
						"Religious Studies",
						"Sports Science",
						"Sociology",
						"Shipping Management",
						"South Australian Matriculation / SACE International",
						"Traditional Finance",
						"TESOL & TESL",
						"Traditional Medicine",
						"Tourism Management",
						"Urban and Regional Planning",
						"Veterinary Medicine");

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
			echo "<body class='bg-light'>";
				include("nav.php");

				echo"<main class='mt-5 pt-5' id='wrap'>";
					echo"<div class='container'>";
						echo"<div class='row mt-5'>";
							echo"<div class='col-md-3 mx-3 px-4 py-4 bg-white' style='max-height:470px'>";
								echo"<h4>Search Institute</h4>";
								echo"<hr/>";
								echo"<form action='' method='get' class='pb-3'>";
									echo"<div class='md-form input-group mb-3'>";
										echo"<input type='text' class='form-control' placeholder='Institute'>";
										echo"<div class='input-group-append'>";
											echo"<button type='submit' class = 'btn btn-secondary btn-rounded mx-0 px-2 my-0 py-0'><i class='fas fa-search'></i></button>";
										echo"</div>";
									echo"</div>";
								echo"</form>";

								echo"<h4>Filter By</h4>";
								echo"<form action='' method='get' class='pb-3'>";
									echo"<div class='form-group'>";
										echo"<label for='location'>Location :</label>";
										echo"<select class='form-control' id='location' name='location'>";
											echo"<option value='' disabled selected>Select Location</option>";
											foreach($location as $state)	
												echo"<option value='$state'>$state</option>";
										echo"</select>";
									echo"</div>";
									echo"<div class='form-group'>";
										echo"<label for='course'>Course :</label>";
										echo"<select class='form-control' id='course' name='course'>";
											echo"<option value='' disabled selected>Select Course</option>";
											foreach($course as $name)	
												echo"<option value='$name'>$name</option>";
										echo"</select>";
									echo"</div>";

									echo "<button type='submit' class='btn btn-secondary btn-rounded mt-2 mx-0 col-md-12'>Search<i class='fas fa-search pl-3'></i></button>";
								echo"</form>";
	
							echo"</div>";
							echo"<div class='col-md-8 mx-3'>";
								echo"<div class='py-2 px-3 bg-white'>";
									echo"<h3 class='font-weight-bold'>Colleges In Malaysia</h3>";
								echo"</div>";
								echo"<div class='scrollbar mt-3' id='style-3' style='height:50vh;'>";
									echo"<div class='bg-white py-3 px-2 college-list'>";
										echo"<div class='row'>";
											echo"<div class='col-md-4 d-flex align-items-center justify-content-center'>";
												echo"<img src='images/logo/inti.png' class='img-fluid college-logo'>";
											echo"</div>";
											echo"<div class='col-md-8'>";
												echo"<h5 class='font-weight-bold'>INTI College Penang</h5>";
												echo"<div class='mb-2'>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
												echo"</div>";
												echo"<div class='row'>";
													echo"<div class='col-md-6'>";
														echo"<h6>Location: Penang</h6>";
														echo"<h6>Course Offer: 100</h6>";
													echo"</div>";
													echo"<div class='col-md-6'>";
														echo"<a href='' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>";
													echo"</div>";
												echo"</div>";
											echo"</div>";
										echo"</div>";
									echo"</div>";

									echo"<div class='bg-white mt-3 py-3 px-2 college-list'>";
										echo"<div class='row'>";
											echo"<div class='col-md-4 d-flex align-items-center justify-content-center'>";
												echo"<img src='images/logo/segi.png' class='img-fluid  college-logo'>";
											echo"</div>";
											echo"<div class='col-md-8'>";
												echo"<h5 class='font-weight-bold'>SEGI College Penang</h5>";
												echo"<div class='mb-2'>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star'></span>";
													echo"<span class='fa fa-star'></span>";
												echo"</div>";
												echo"<div class='row'>";
													echo"<div class='col-md-6'>";
														echo"<h6>Location: Penang</h6>";
														echo"<h6>Course Offer: 100</h6>";
													echo"</div>";
													echo"<div class='col-md-6'>";
														echo"<a href='' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>";
													echo"</div>";
												echo"</div>";
											echo"</div>";
										echo"</div>";
									echo"</div>";

									echo"<div class='bg-white mt-3 py-3 px-2 college-list'>";
										echo"<div class='row'>";
											echo"<div class='col-md-4 d-flex align-items-center justify-content-center'>";
												echo"<img src='images/logo/tarc.jpg' class='img-fluid college-logo'>";
											echo"</div>";
											echo"<div class='col-md-8'>";
												echo"<h5 class='font-weight-bold'>TARC College Penang</h5>";
												echo"<div class='mb-2'>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star'></span>";
													echo"<span class='fa fa-star'></span>";
												echo"</div>";
												echo"<div class='row'>";
													echo"<div class='col-md-6'>";
														echo"<h6>Location: Penang</h6>";
														echo"<h6>Course Offer: 100</h6>";
													echo"</div>";
													echo"<div class='col-md-6'>";
														echo"<a href='' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>";
													echo"</div>";
												echo"</div>";
											echo"</div>";
										echo"</div>";
									echo"</div>";

									echo"<div class='bg-white mt-3 py-3 px-2 college-list'>";
										echo"<div class='row'>";
											echo"<div class='col-md-4 d-flex align-items-center justify-content-center'>";
												echo"<img src='images/logo/kdu.png' class='img-fluid college-logo' >";
											echo"</div>";
											echo"<div class='col-md-8'>";
												echo"<h5 class='font-weight-bold'>KDU College Penang</h5>";
												echo"<div class='mb-2'>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star checked'></span>";
													echo"<span class='fa fa-star'></span>";
												echo"</div>";
												echo"<div class='row'>";
													echo"<div class='col-md-6'>";
														echo"<h6>Location: Penang</h6>";
														echo"<h6>Course Offer: 100</h6>";
													echo"</div>";
													echo"<div class='col-md-6'>";
														echo"<a href='' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>";
													echo"</div>";
												echo"</div>";
											echo"</div>";
										echo"</div>";
									echo"</div>";
								echo"</div>";
							echo"</div>";
						echo"</div>";
					echo"</div>";
				echo"</main>";
				include("footer.php");
		echo "</body>";
	echo "</html>";
?>