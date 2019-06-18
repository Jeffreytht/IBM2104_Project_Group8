<?php
	require_once("models/image.php");
	class News
	{
		private $newsID;
		private $content;
		private $image;
		private $timeStamp;

		public function getNewsID() {return $this->newsID;}
		public function getContent(){return $this->content;}
		public function getImage(){return $this->image;}
		public function getTimeStamp(){return $this->timeStamp;}

		public function setContent($content){$this->content = $content;}
		public function setImagePath($image){$this->image = $image;}

		public function __construct()
		{
			$this->content = "";
			$this->image = array();
		}

		public function assignNews($news)
		{
			$this->newsID = $news['news_id'];
			$this->content = $news['content'];
			$this->timeStamp = $news['news_date'];

			$conn = new mysqli("localhost","root","","college_portal");
			$sql = "CALL SelectGalleryIDByNewsID($this->newsID)";

			$result = $conn->query($sql);

			while($image = $result->fetch_assoc())
			{
				$tempImage = new Image();
				$tempImage->assignImage($image);
				array_push($this->image,$tempImage);
			}
		}
	}
?>