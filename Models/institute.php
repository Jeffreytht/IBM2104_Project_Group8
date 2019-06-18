<?php
	class Institute
	{
		private $instituteID;
		private $instituteName;
		private $instituteAddress;
		private $instituteAddressUrl;
		private $stateName;
		private $news;
	}

	public function getInstituteID(){return $this->instituteID;}
	public function getInstituteName() {return $this->instituteName;}
	public function getInstituteAddress(){return $this->instituteAddress;}
	public function getInstituteAddressUrl(){return $this->instituteAddressUrl;}
	public function getStateName(){return $this->$stateName;}
	public function setInstituteName($instituteName){ $this->instituteName = $instituteName;}
	public function setInstituteAddress($instituteAddress){$this->instituteAddress = $instituteAddress;}
	public function setInstituteAddressUrl($instituteAddressUrl){$this->instituteAddressUrl = $instituteAddressUrl;}
	public function setStateName($stateName){$this->stateName = $stateName;}

	public function __construct()
	{
		$this->instituteName = "";
		$this->instituteAddress = "";
		$this->instituteAddressUrl = "";
		$this->stateName = "";
		$this->news = array();
	}

?>