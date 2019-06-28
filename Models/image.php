<?php
	class Image
	{
		private $imageID;
		private $imagePath;

		#Getter and setter
		public function getImageID(){return htmlspecialchars($this->imageID);}
		public function getImagePath(){return htmlspecialchars($this->imagePath);}
		public function setImageID($imageID){$this->imageID = $imageID;}
		public function setImagePath($imagePath){$this->imagePath = $imagePath;}

		#Constuctor
		public function __construct()
		{
			$this->imageID = "";
			$this->imagePath = "";
		}

		public function assignImage($image)
		{
			$this->imageID = $image['image_id'];
			$this->imagePath = $image['image_path'];
		}
	}
?>