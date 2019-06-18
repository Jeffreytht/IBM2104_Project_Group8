<?php
require("models/users.php");
require("models/normalUser.php");
require("models/admin.php");
require("models/superadmin.php");
session_start();
	echo "<!DOCTYPE html>";
	echo "<html lang='en'>";
		echo "<head>";
			include("header.html");
		echo "</head>";
		echo "<body class='bg-light' >";
			include("nav.php");
			echo "<main class='main'>";
				
				?>
				<div class='container-fluid d-flex justify-content-center'>

					<div class='collegeDetail'>
						<div class='bg-white mt-3 px-3 py-3'>
							ds
						</div>
					</div>
				</div>
<?php
			echo "</main>";
			include("footer.php");
			
		echo "</body>";
	echo "</html>";
?>
