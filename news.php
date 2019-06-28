<?php
require("models/users.php");
require("models/normalUser.php");
require("models/admin.php");
require("models/superadmin.php");
session_start();
	
			#Define constant variable to store attribute of mysql server
			define("SERVER", "localhost");
			define("USER","root");
			define("PASS","");
			define("DB","college_portal");
			
			$news = "";
			
			#Create a connection to database to get the news based on the searching criteria
			$conn = new mysqli(SERVER,USER,PASS,DB);
			
			if($conn->connect_error)
				die("Connection error.".$conn->connect_error);
			
			$sql = "CALL SelectAllNews()";
			
			#New
			
				if($result = $conn->query($sql)){
					while($output = $result->fetch_assoc()){
						global $news;
						$news.=<<<BODY
						<!--Logo-->
						<div class='mb-3 bg-white w-100 px-5 py-3'>
						
							<div class='row mb-3'>
								<div class='col-md-2 mr-0 pr-0 align-items-center'>
								<img src=$output[image_path] class="circle-news-image border" >
								</div>
								
								
								<!--Name & Time-->
								<div class='col-md-9 ml-0 pl-0 align-items-center'>
									<h4 class='font-weight-bold mb-0 pb-0'>$output[institute_name]</h4>
									<span class='mt-0 pt-0' style='font-size:10px'>$output[news_date]</span>
								</div>
							</div>
							
							<!--Content-->
							<h6>$output[content]</h6>
							<div class="w-100">

						
BODY;
							$conn2 = new mysqli(SERVER,USER,PASS,DB);
							
							$pic ="CALL GetNewsPic($output[news_id])";
										
							if($result1 = $conn2->query($pic)){
								if($result1->num_rows > 0)
								{
									
									while($output1 = $result1->fetch_assoc()){
										global $news;
										$news.=<<<BODY
										
										<center><img src='$output1[image_path]' class='img-fluid rounded'></center>
					
BODY;
									}
								
								}
								
								$news.="</div></div>";
							
							}
							else
								echo"Error.SQL execute failed.".$conn->error;
					}
			}
			else
				echo"Error.SQL execute failed.".$conn->error;
			

echo <<<BODY

				<style>
				.news {
					width: 1050px;
					margin-top: 98px;
				} 
				</style>
				
				<div class='container-fluid d-flex justify-content-center'>

					<div class='news'>
						<div class='bg-white mt-3 px-3 py-3 mb-3'>
							<h3 class='font-weight-bold'><i class='far fa-newspaper pr-2'></i>News</h3>
						</div>

									$news
						
					</div>
				</div>

BODY;

			echo "<!DOCTYPE html>";
				echo "<html lang='en'>";
					echo "<head>";
						include("header.html");
					echo "</head>";
					echo "<body class='bg-light' >";
						include("nav.php");
						echo "<main class='main'>";
						echo "</main>";
						include("footer.php");
						
					echo "</body>";
				echo "</html>";
?>
