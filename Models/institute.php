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
		private $instituteProfile;
		private $instituteCover;
		private $instituteLogo;
		private $instituteIFrame;
		private $course;
		private $state;
		private $news;

		public function getInstituteID(){return $this->instituteID;}
		public function getInstituteName() {return $this->instituteName;}
		public function getInstituteAddress(){return $this->instituteAddress;}
		public function getInstituteAddressUrl(){return $this->instituteAddressUrl;}
		public function getProfile(){return $this->instituteProfile;}
		public function getCover(){return $this->instituteCover;}
		public function getCourse(){return $this->course;}
		public function getState(){return $this->state;}
		public function getNews(){return $this->news;}
		public function getLogo(){return $this->instituteLogo;}
		public function getInstituteIFrame(){return $this->instituteIFrame;}

		public function setInstituteName($instituteName){ $this->instituteName = $instituteName;}
		public function setInstituteAddress($instituteAddress){$this->instituteAddress = $instituteAddress;}
		public function setInstituteAddressUrl($instituteAddressUrl){$this->instituteAddressUrl = $instituteAddressUrl;}
		public function setInstituteProfile($instituteProfile){$this->instituteProfile = $instituteProfile;}
		public function setInstituteCover($instituteCover){$this->instituteCover = $instituteCover;}
		public function setState($state){$this->state = $state;}
		public function setNews($news){$this->news = $news;}
		public function setCourse($course){$this->course = $course;}
		public function setInstituteLogo($instituteLogo){$this->instituteLogo = $instituteLogo;}
		public function setInstituteIFrame($instituteIFrame){$this->instituteIFrame = $instituteIFrame;}

		public function __construct()
		{
			$this->instituteName = "";
			$this->instituteAddress = "";
			$this->instituteAddressUrl = "";
			$this->instituteProfile = "";
			$this->instituteCover = "";
			$this->instituteIFrame = "";
			$this->instituteLogo = "";
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
			$this->instituteIFrame = $institute['iframe_url'];
			$this->state->assignState($institute['state_id'],$institute['state_name'], $institute['state_url']);

			$conn = new mysqli("localhost","root","","college_portal");
			$sql = "SELECT image_path FROM gallery g, profile_pic pp WHERE pp.institute_id = \"$this->instituteID\" && pp.image_id = g.image_id";
			$result = $conn->query($sql);
			$this->instituteProfile = $result->fetch_assoc()['image_path'];
			$conn->close();

			$conn = new mysqli("localhost","root","","college_portal");
			$sql = "SELECT image_path FROM gallery g, cover_photo cp WHERE cp.institute_id = \"$this->instituteID\" && cp.image_id = g.image_id";
			$result = $conn->query($sql);
			$this->instituteCover = $result->fetch_assoc()['image_path'];
			$conn->close();


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