<?php
require_once 'engine/kernel/HTMLPage.php';

	class RegistrationPage extends HTMLPage
	{
		protected function onInit()
		{
			
		}
		
		protected function doAssign()
		{
			$registrationError=$this->_controller->error->getErrorByName("registrationError");
			if ($registrationError!=null)
			{
				$this->_smarty->assign("ERROR",$registrationError["error"]->getMessage());
				$this->_smarty->assign("DATA",$registrationError["postData"]);
			}
		}
	}
?>