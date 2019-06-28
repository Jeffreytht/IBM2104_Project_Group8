<?php
	require_once("models/image.php");
	class News
	{
		private $newsID;
		private $content;
		private $image;
		private $timeStamp;

		#Getter and setter
		public function getNewsID() {return htmlspecialchars($this->newsID);}
		public function getContent(){return htmlspecialchars($this->content);}
		public function getImage(){return $this->image;}
		public function getTimeStamp(){return htmlspecialchars($this->timeStamp);}
		public function setContent($content){$this->content = $content;}
		public function setImage ($image){$this->image = $image;}

		#Constructor
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

			$sql = "CALL SelectImageIDByNewsID($this->newsID)";

			$result = $conn->query($sql);
			$conn ->close();

			while($imageID = $result->fetch_assoc()['image_id'])
			{
				$anotherConn = new mysqli("localhost","root","","college_portal");
				$sql = "SELECT * FROM gallery WHERE image_id = $imageID";
				$anotherResult = $anotherConn->query($sql);
				
				$image = $anotherResult->fetch_assoc();
				$tempImage = new Image();
				$tempImage->assignImage($image);
				array_push($this->image,$tempImage);

				$anotherConn ->close();
			}
		}
	}
?>