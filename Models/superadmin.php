<?php
	class SuperAdmin extends user 
	{
		protected $institute;

		#Constructor
		public function __construct()
		{
			$this->institute = new Institute();
		}

		#Getter and setter
		public function getInstitute(){return $this->institute;}
		public function setInstitute($institute){ $this->institute = $institute;}
	}
?>