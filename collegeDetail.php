<?php
	echo "<!DOCTYPE html>";
		echo "<html lang='en' class='h-100'>";
			echo "<head>";
				include("header.html");						
			echo "</head>";
			echo "<body class='bg-light h-100'>";
			include("nav.php");
?>

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
					      	<a class="nav-link active" data-toggle="tab" href="#home">Home</a>
					    </li>
					    <li class="nav-item">
					      	<a class="nav-link" data-toggle="tab" href="#overview">Overview</a>
					    </li>
					    <li class="nav-item">
					      	<a class="nav-link" data-toggle="tab" href="#course">Course</a>
					    </li>
				  	</ul>
		  		</nav>	
	  		</div>
		</div>
		<div class="rounded border tab-content bg-white mt-2">
  			<div class="tab-pane container active p-3" id="home">home</div>
  			<div class="tab-pane container fade p-3" id="overview">overview</div>
  			<div class="tab-pane container fade p-3" id="course">course</div>
		</div>		
	</div>
</div>
</main>
<?php
		include("footer.php");
	echo "</body>";
	echo "</html>";
?>
