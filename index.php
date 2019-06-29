<?php
	
	#Import all the model class required
	require_once("models/users.php");
	require_once("models/normalUser.php");
	require_once("models/admin.php");
	require_once("models/superadmin.php");
	require_once("models/institute.php");
	require_once("models/news.php");
	session_start();

	#A wrapper class to allow using static method in heredoc
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

	#Create a wrapper object
	$wrapper = new Wrapper();


/*******************************GENERATE VIEW*********************************/


	#<!--GET INSTITUTE-->
		
		#Define constant variable to store attribute of mysql server
		define("SERVER", "localhost");
		define("USER","root");
		define("PASS","");
		define("DB","college_portal");
		
		$institute = "";
			
		#Create a connection to database to get the institute based on the searching criteria
		$conn = new mysqli(SERVER,USER,PASS,DB);
		
		#Close the page if unable to create connection
		if($conn->connect_error)
			die("Connection error.".$conn->connect_error);
		
		$sql = "CALL GetAllCollege()";
		
		#Check whether the query is valid.
		if($result = $conn->query($sql)){
			while($output = $result->fetch_assoc()){
				global $institute;
				$institute.=<<<BODY
				
					<div class='col-lg-4 col-md-12 mb-4'>
						<div class='view overlay z-depth-1-half'>
							<img src='$output[image_path]' height=200px width=100%>
							<div class='mask rgba-white-slight'></div>
						</div>
						<a href='institute.php?id=$output[institute_id]'>
							<h4 class='my-4 font-weight-bold' style='color:#000000'>$output[institute_name]</h4>
						</a>
					</div>			
					
BODY;
							
			}
		}
		else
			echo"Error.SQL execute failed.".$conn->error;

$body = <<< BODY

<!--OVERLAY BACKGROUND-->	
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
<!--END OVERLAY BACKGROUND-->

<main class='mt-5 mb-5 main'>
	<div class='container' id='college_in_Malaysia'>
		<section id='examples' class='text-center'>
			<h2 class='mb-5 font-weight-bold'>Institute in Malaysia</h2>
			<div class='row'>
				$institute
			</div>						
			<a href='institute.php' class='btn btn-outline-secondary btn-rounded waves-effect'>View More</a>				
		</section>
	</div>
</main>

BODY;

/******************************************VIEW*******************************************/
	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body>";
		include("nav.php");

			echo $body;

			include("footer.php");
		echo "</body>";
	echo "</html>";
?>