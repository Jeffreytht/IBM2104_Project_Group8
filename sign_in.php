<?php
	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
		include("header.html");
		echo "</head>";
		echo "<body>";
		echo "<div id='intro' class='view' width='100%'>";
?>
		<div class='mask rgba-black-strong'>
			<div class='mt-5 container d-flex align-items-center justify-content-center'>
				<div class='col-md-5'>
					
					<div class ='jumbotron card card-image signin-jumbotron my-0'>
						<h2 class='display-4 text-white text-center'><strong>GODs</strong></h2>
					</div>
					<div class='bg-light'>
						<form class='px-5 py-5'>
							<div class="md-form">
								<i class="fa fa-envelope prefix indigo-text"></i>
								<input mdbActive class='form-control pl-2' type="email" name='email' required autocomplete="off" placeholder='Email'/>
							</div>


							<div class="md-form">
								<i class="fas fa-key prefix indigo-text"></i>
								<input mdbActive class='form-control pl-2' type="password" name='pwd' required autocomplete="off" placeholder='Password'/>
							</div>

							<div class="d-flex justify-content-around">
        						<div>
            						<div class="custom-control custom-checkbox">
                						<input type="checkbox" class="custom-control-input" id="defaultLoginFormRemember">
                						<label class="custom-control-label" for="defaultLoginFormRemember">Remember me</label>
        							</div>
        						</div>
	        					<div>
	            					<a href="">Forgot password?</a>
	        					</div>
    						</div>

    						<div class="text-center">
								<button type="submit" class="text-white btn blue-gradient col-md-6 my-4">Sign In</button>
								<p>
									Not a member?
	        						<a href="">Register</a>
	    						</p>
	    					</div>
						</form>
					</div>
					
				</div>
			</div>
		</div>
<?php
		echo "</div>";
		echo "</body>";
	echo "</html>";
?>