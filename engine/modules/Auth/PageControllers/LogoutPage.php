<?php
class LogoutPage extends SinglePageController
{
	protected function onInit()
	{
		$this->_controller->auth->logOut();
		$this->navigate(AuthCheckerControllerAbstract::LOGIN_URL);
	}
}
?>