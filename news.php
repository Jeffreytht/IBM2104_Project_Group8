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
				
	$conn = new mysqli(SERVER,USER,PASS,DB);
				
	if($conn->connect_error)
		die("Connection error.".$conn->connect_error);

	$sql = "CALL SelectAllNews()";
			
	if($result = $conn->query($sql))
	{
		while($output = $result->fetch_assoc())
		{
			global $news;
			$output["content"] = htmlspecialchars($output["content"]);
			$output["image_path"] = htmlspecialchars($output["image_path"]);
			$output["institute_name"] = htmlspecialchars($output["institute_name"]);
			$output["news_date"] = htmlspecialchars($output["news_date"]);

			$news.=<<<BODY
<!--Logo-->
			<div class='mb-3 bg-white w-100 pl-5 pt-3 pb-4 rounded'>		
				<div class='row mb-3'>
					<div>
						<img src= $output[image_path] class="circle-news-image border" >
					</div>				
<!--Name & Time-->
					<div class="d-flex align-items-center col-md-9 ml-4 pl-0">
						<div>
							<a href="institute.php?id=$output[institute_id]" class="text-dark"><h3 class='font-weight-bold mb-1 pb-0'>$output[institute_name]</h3></a>
							<span class='mt-1 pt-0' style='font-size:13px'>$output[news_date]</span>
						</div>
					</div>
				</div>	
<!--Content-->
				<p style="font-size:18px">$output[content]</p>	
				<div class='row w-100'>
					<div class="col-md-4" id="loadGallery"></div>
				</div>			
BODY;

			$conn2 = new mysqli(SERVER,USER,PASS,DB);
			
			$pic ="CALL GetNewsPic($output[news_id])";
						
			if($result2 = $conn2->query($pic)){
				if($result2->num_rows > 0)
				{
					$count = 0;
					$size = $result2->num_rows;
					
					for($i = 0 ; $i < $size ; $i++)
					{
						global $news;

						while($output2 = $result2->fetch_assoc())
						{
							global $news;
							
							#Create a new row for every 3 lines
							if($count % 3 == 0)
							{
								#indicate whether the row is end
								$endRow = FALSE;
								$news.= "<div class='row mt-1 mb-2 px-3 w-100'>";
							}
							
							$news.=<<<BODY
							<div class='col-md-4 pl-0 border rounded' style='overflow:hidden'>
								<center><img src='$output2[image_path]' class='galImage	'></center>
							</div>
BODY;
							$count++;
											
							#Check whether the row is end
							if(($count) % 3 == 0)
							{
								
								$endRow = TRUE;
								$news.="</div>";
							}					
						}

						#If the gallery image row havent end, end it

					}	
					if(!$endRow)
						$news.="</div>";	
				}

				$news.="</div>";			
			}
			else
				echo"Error.SQL execute failed.".$conn->error;
		}
	}
	else
		echo"Error.SQL execute failed.".$conn->error;
			

$body =<<<BODY
	<div class='container-fluid d-flex justify-content-center mb-5'>
		<div class='news'>
			<div class='bg-white mt-3 px-3 py-3 mb-3 rounded'>
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
						echo $body;
						echo "</main>";
						include("footer.php");
					
					echo "<script src='style/style.js'></script>";	
					echo "</body>";

				echo "</html>";
?>
