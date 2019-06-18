<?php
	
	$location = $course = array();
	$conn = mysqli_connect("localhost","root","","college_portal");
	$sql = "Select * FROM state";
	$result = $conn->query($sql);
	while($state = $result->fetch_assoc())
	{
		array_push($location, $state['state_name']);
	}
	
	$sql = "Select * FROM course";
	$result = $conn->query($sql);
	while($courseName = $result->fetch_assoc())
	{
		array_push($course, $courseName['course_name']);
	}

	$conn->close();

	function drawline($color)
	{
		echo "<hr class='$color my-0' style=\"height:3px\"/>";
	}

	function breakLine($times)
	{
		for($i = 0; $i < $times ; $i++)
			echo "</br>";
	}

	if($_GET)
		require('instituteDetail.php');
	else							
		require('instituteList.php');
?>