<?php
	class SuperAdmin extends user 
	{
		protected $institute;

		public function __construct()
		{
			$this->institute = new Institute();
		}

		public function getInstitute(){return $this->institute;}
		public function setInstitute($institute){ $this->institute = $institute;}
	}
?>