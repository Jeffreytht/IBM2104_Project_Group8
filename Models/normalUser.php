<?php

	class NormalUser extends User
	{
		public function __construct()
		{		
			$this->username = "";
			$this->email = "";
			$this->pwd = "";
			$this->retypePwd = "";
			$this->dob = "";
			$this->roleID = NULL;	
		}
	}
?>