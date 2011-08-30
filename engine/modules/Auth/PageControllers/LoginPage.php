<?php
require_once 'engine/kernel/HTMLPage.php';

	class LoginPage extends HTMLPage
	{
		protected function onInit()
		{
			
		}
		
		protected function doAssign()
		{
			$loginError=$this->_controller->error->getErrorByName("loginError");
			if ($loginError!=null)
			{
				$this->_smarty->assign("ERROR",$loginError->getMessage());
			}
			$registrationOk=$this->_controller->error->getErrorByName("registrationOk");
			if ($registrationOk)
			{
				$this->_smarty->assign("GOOD",true);
			}
		}
	}
?>