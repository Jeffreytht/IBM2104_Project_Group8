<?php
	require("models/users.php");

	session_start();

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
							<form action='' method='get' class='pb-3'>
								<div class='md-form input-group mb-3'>
									<input type='text' class='form-control' placeholder='Institute'>
									<div class='input-group-append'>
										<button type='submit' class = 'btn btn-secondary btn-rounded mx-0 px-2 my-0 py-0'><i class='fas fa-search'></i></button>
									</div>
								</div>
							</form>

							<h4>Filter By</h4>
							<form action='' method='get' class='pb-3'>
								<div class='form-group'>
									<label for='location'>Location :</label>
									<select class='form-control' id='location' name='location'>
										<option value='' disabled selected>Select Location</option>
BODY;
										foreach($location as $state)	
											echo "<option value=$state>$state</option>";
echo <<< BODY
									</select>
								</div>
								<div class='form-group'>
									<label for='course'>Course :</label>
									<select class='form-control' id='course' name='course'>
										<option value='' disabled selected>Select Course</option>
BODY;
										foreach($course as $name)	
											echo "<option value=$name>$name</option>";
echo <<<BODY
									</select>
								</div>

								<button type='submit' class='btn btn-secondary btn-rounded mt-2 mx-0 col-md-12'>Search<i class='fas fa-search pl-3'></i></button>
							</form>

						</div>
						<div class='col-md-8 mx-3'>
							<div class='py-2 px-3 bg-white'>
								<h3 class='font-weight-bold'>Institute In Malaysia</h3>
							</div>
							<div class='scrollbar mt-3' id='style-3' style='height:65vh;'>
								<div class='bg-white py-3 px-2 college-list'>
									<div class='row'>
										<div class='col-md-4 d-flex align-items-center justify-content-center'>
											<img src='images/logo/inti.png' class='img-fluid college-logo'>
										</div>
										<div class='col-md-8'>
											<h5 class='font-weight-bold'>INTI College Penang</h5>
											<div class='mb-2'>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
											</div>
											<div class='row'>
												<div class='col-md-6'>
													<h6>Location: Penang</h6>
													<h6>Course Offer: 100</h6>
												</div>
												<div class='col-md-6'>
													<a href='institute.php?college=inti' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class='bg-white mt-3 py-3 px-2 college-list'>
									<div class='row'>
										<div class='col-md-4 d-flex align-items-center justify-content-center'>
											<img src='images/logo/segi.png' class='img-fluid  college-logo'>
										</div>
										<div class='col-md-8'>
											<h5 class='font-weight-bold'>SEGI College Penang</h5>
											<div class='mb-2'>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star'></span>
												<span class='fa fa-star'></span>
											</div>
											<div class='row'>
												<div class='col-md-6'>
													<h6>Location: Penang</h6>
													<h6>Course Offer: 100</h6>
												</div>
												<div class='col-md-6'>
													<a href='' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class='bg-white mt-3 py-3 px-2 college-list'>
									<div class='row'>
										<div class='col-md-4 d-flex align-items-center justify-content-center'>
											<img src='images/logo/tarc.jpg' class='img-fluid college-logo'>
										</div>
										<div class='col-md-8'>
											<h5 class='font-weight-bold'>TARC College Penang</h5>
											<div class='mb-2'>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star'></span>
												<span class='fa fa-star'></span>
											</div>
											<div class='row'>
												<div class='col-md-6'>
													<h6>Location: Penang</h6>
													<h6>Course Offer: 100</h6>
												</div>
												<div class='col-md-6'>
													<a href='' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class='bg-white mt-3 py-3 px-2 college-list'>
									<div class='row'>
										<div class='col-md-4 d-flex align-items-center justify-content-center'>
											<img src='images/logo/kdu.png' class='img-fluid college-logo' >
										</div>
										<div class='col-md-8'>
											<h5 class='font-weight-bold'>KDU College Penang</h5>
											<div class='mb-2'>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star checked'></span>
												<span class='fa fa-star'></span>
											</div>
											<div class='row'>
												<div class='col-md-6'>
													<h6>Location: Penang</h6>
													<h6>Course Offer: 100</h6>
												</div>
												<div class='col-md-6'>
													<a href='' class='btn btn-outline-secondary btn-rounded waves-effect'>View Detail<i class='fas fa-list pl-2'></i></a>
												</div>
											</div>
										</div>
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