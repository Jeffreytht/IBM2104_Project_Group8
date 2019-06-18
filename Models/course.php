<?php
	class Course
	{
		private $courseID;
		private $courseName;
		private $courseFee;
		private $courseDuration;

		public function getCourseID(){return $this->courseID;}
		public function getCourseName(){return $this->courseName;}
		public function getCourseFee(){return $this->courseFee;}
		public function getCourseDuration(){return $this->courseDuration;}

		public function __construct()
		{
			$this->courseID = "";
			$this->courseName = "";
			$this->courseFee = "";
			$this->courseDuration = "";
		}

		public function assignCourse($course)
		{
			$this->courseID = $course['course_id'];
			$this->courseName = $course['course_name'];
			$this->courseFee = $course['fee'];
			$this->courseDuration = $course['duration'];
		}
	}

?>