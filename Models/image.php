<?php
	class Image
	{
		private $imageID;
		private $imagePath;

		public function getImageID(){return $this->imageID;}
		public function getImagePath(){return $this->imagePath;}

		public function setImageID($imageID){$this->imageID = $imageID;}
		public function setImagePath($imagePath){$this->imagePath = $imagePath;}

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