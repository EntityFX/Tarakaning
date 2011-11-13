<?php
require_once SOURCE_PATH.'engine/kernel/HTMLPage.php';

	class LoginPage extends HTMLPage
	{
		protected function onInit()
		{
			if ($this->_controller->auth->isEntered())
			{
				$this->navigate(AuthCheckerControllerAbstract::MY_PROJECTS_URL);
			}
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