<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbName ="College_Portal";

	$conn = new mysqli($servername, $username, $password,$dbName);

	if($conn->connect_error)
	{
		die("Connection failed: ".$conn->connect_error);
	}

	echo "Connect Successfully!";
	echo "<br/>";

	$sql = "CREATE TABLE Users(
			user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user_name VARCHAR(30) NOT NULL,
			email VARCHAR(50) NOT NULL,
			pwd VARCHAR(20) NOT NULL,
			dob DATE NOT NULL,
			reg_date TIMESTAMP
		)";

	if($conn->query($sql) === TRUE)
		echo "User table created successfully!";
	else
		echo "Error creating user table".$conn->error;
	echo "<br/>";

	$sql = "CREATE TABLE Role(
			role_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			role_name varchar(20)
			)";

	if($conn->query($sql) === TRUE)
		echo "Role table created successfully!";
	else
		echo "Error creating role table".$conn->error;
	echo "<br/>";

	$sql = "CREATE TABLE User_Role(
			user_id INT(6) UNSIGNED NOT NULL,
			role_id INT(6) UNSIGNED NOT NULL,
			FOREIGN KEY (user_id) REFERENCES Users(user_id),
			FOREIGN KEY (role_id) REFERENCES Role(role_id)
			)";

	if($conn->query($sql) === TRUE)
		echo "User_Role table created successfully!";
	else
		echo "Error creating User_Role table ".$conn->error;
?>