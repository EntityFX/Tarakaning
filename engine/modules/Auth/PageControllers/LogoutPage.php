<?php
class LogoutPage extends SinglePage
{
	protected function onInit()
	{
		$this->_controller->auth->logOut();
		$this->navigate(AuthCheckerControllerAbstract::LOGIN_URL);
	}
}
?>