<?php
	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";
		echo "<body>";
		echo "<div id='intro' class='view' width='100%'>";
			echo"<div class='mask rgba-black-strong'>";
				echo"<div class='container d-flex align-items-center justify-content-center'>";
					echo"<div class='mt-3 pt-5 col-md-5'>";
						echo"<div class ='jumbotron card card-image signin-jumbotron my-0'>";
							echo"<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>";
						echo"</div>";

						echo"<div class='bg-light scrollbar' id='style-1' style='height:50vh; max-height:350px'>";
							echo"<form class='px-5 pb-3'>";
								echo"<div class='md-form'>";
									echo"<i class='fa fa-envelope prefix purple-text'></i>";
									echo"<input mdbActive class='form-control pl-2' type='email' name='email' required autocomplete='off' placeholder='Email'/>";
								echo"</div>";
								echo"<div class='md-form'>";
									echo"<i class='fas fa-key prefix purple-text'></i>";
									echo"<input mdbActive class='form-control pl-2' type='password' name='pwd' required autocomplete='off' placeholder='Password'/>";
								echo"</div>";
								echo"<div class='d-flex justify-content-around'>";
									echo"<div>";
										echo"<div class='custom-control custom-checkbox'>";
											echo"<input type='checkbox' class='custom-control-input' id='defaultLoginFormRemember'>";
											echo"<label class='custom-control-label' for='defaultLoginFormRemember'>Remember me</label>";
										echo"</div>";
									echo"</div>";
									echo"<div>";
										echo"<a href=''>Forgot password?</a>";
									echo"</div>";
								echo"</div>";
								echo"<div class='text-center'>";
									echo"<button type='submit' class='text-white btn blue-gradient col-md-6 my-4'>Sign In</button>";
									echo"<p>";
										echo"Not a member?";
										echo"<a href='register.php'>Register</a>";
									echo"</p>";
								echo"</div>";
							echo"</form>";
						echo"</div>";
					echo"</div>";
				echo"</div>";
			echo"</div>";
		echo "</div>";
		echo "</body>";
	echo "</html>";
?>