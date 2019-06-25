<?php
	class Admin extends user 
	{
		private $institute;

		#Constructor
		public function __construct()
		{
			$this->institute = new Institute();
		}

		#Getter and setter
		public function getInstitute(){return $this->institute;}
		public function setInstitute($institute){ $this->institute = $institute;}

		public function assignAdmin(){
			$conn = new mysqli("localhost","root","","college_portal");
			$sql = "CALL SelectUserInstitute(\"$this->userID\");";

			$result = $conn->query($sql);
			$tempInstitute = $result->fetch_assoc();
			$this->institute->assignInstitute($tempInstitute);
		}
	}
?>