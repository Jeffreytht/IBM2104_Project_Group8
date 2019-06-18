<?php
	require_once("models/state.php");
	require_once("models/news.php");
	require_once("models/course.php");
	class Institute
	{
		private $instituteID;
		private $instituteName;
		private $instituteAddress;
		private $instituteAddressUrl;
		private $course;
		private $state;
		private $news;

		public function getInstituteID(){return $this->instituteID;}
		public function getInstituteName() {return $this->instituteName;}
		public function getInstituteAddress(){return $this->instituteAddress;}
		public function getInstituteAddressUrl(){return $this->instituteAddressUrl;}
		public function getCourse(){return $this->course;}
		public function getState(){return $this->state;}
		public function getNews(){return $this->news;}
		public function setInstituteName($instituteName){ $this->instituteName = $instituteName;}
		public function setInstituteAddress($instituteAddress){$this->instituteAddress = $instituteAddress;}
		public function setInstituteAddressUrl($instituteAddressUrl){$this->instituteAddressUrl = $instituteAddressUrl;}
		public function setState($state){$this->state = $state;}
		public function setNews($news){$this->news = $news;}
		public function setCourse($course){$this->course = $course;}

		public function __construct()
		{
			$this->instituteName = "";
			$this->instituteAddress = "";
			$this->instituteAddressUrl = "";
			$this->state = new State();
			$this->course = array();
			$this->news = array();
		}

		public function assignInstitute($institute)
		{
			$this->instituteID = $institute['institute_id'];
			$this->instituteName = $institute['institute_name'];
			$this->instituteAddress = $institute['address'];
			$this->instituteAddressUrl = $institute['address_url'];
			$this->state->assignState($institute['state_id'],$institute['state_name'], $institute['state_url']);

			$conn = new mysqli("localhost","root","","college_portal");
			$sql = "CALL SelectNewsByInstituteID($this->instituteID)";
			$result = $conn->query($sql);

			while($selectedNew = $result->fetch_assoc())
			{
				$tempNew = new News();
				$tempNew->assignNews($selectedNew);
				array_push($this->news, $tempNew);
			}

			$conn->close();

			$conn = new mysqli("localhost","root","","college_portal");
			$sql = "CALL SelectCourseByInstituteID($this->instituteID)";
			$result = $conn->query($sql);

			while($selectedCourse = $result->fetch_assoc())
			{
				$course = new Course();
				$course->assignCourse($selectedCourse);
				array_push($this->course,$course);
			}
		}
	}

?>