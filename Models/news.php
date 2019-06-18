<?php
	class News
	{
		private $newsID;
		private $content;
		private $imagePath;
		private $timeStamp;

		public function getNewsID() {return $this->newsID;}
		public function getContent(){return $this->content;}
		public function getImagePath(){return $this->imagePath;}
		public function getTimeStamp(){return $this->timeStamp;}

		public function setContent($content){$this->content = $content;}
		public function setImagePath($imagePath){$this->imagePath = $imagePath;}

		public function __construct()
		{
			$this->content = "";
			$this->imagePath = array();
		}
	}
?>