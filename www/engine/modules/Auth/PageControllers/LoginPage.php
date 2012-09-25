<?php
require_once SOURCE_PATH.'engine/kernel/HTMLPageController.php';

Loader::LoadModel("UserActivation");

class LoginPage extends HTMLPageController
{
	protected function onInit()
	{
		$userActiv = new UserActivation();
        $activationKey = $userActiv->createActivationKey(1);
        $userActiv->activateUserKey($activationKey);
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