<?php
	class State
	{
		private $stateID;
		private $stateName;
		private $stateURL;

		public function getStateID(){return htmlspecialchars($this->stateID);}
		public function getStateName(){return htmlspecialchars($this->stateName);}
		public function getStateURL(){return htmlspecialchars($this->stateURL);}
		public function setStateID($stateID){$this->stateID = $stateID;}
		public function setStateName($stateName){$this->stateName = $stateName;}
		public function setStateURL($stateURL){$this->stateURL = $stateURL;}

		#Constructor
		public function __construct()
		{
			$this->stateID = "";
			$this->name = "";
			$this->stateURL = "";
		}

		public function assignState($stateID, $stateName, $stateURL)
		{
			$this->stateID = $stateID;
			$this->stateName = $stateName;
			$this->stateURL = $stateURL;
		}
	}
?>