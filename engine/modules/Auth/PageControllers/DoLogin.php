<?php
require_once 'engine/modules/Auth/Logic/UserAuth.php';

class DoLogin extends SinglePage
{
	const MY_PROJECTS_URL='/my/projects/';
	const LOGIN_URL='/login/';
	
	protected function onInit()
	{
		$auth=$this->_controller->auth;
		if ($this->request->isPost())
		{
			try
			{
				$auth->logIn(
					$this->request->getPost("login"),
					$this->request->getPost("pswrd")
				);
			}
			catch(Exception $exception)
			{
				$this->_controller->error->addError("loginError",$exception);
				$this->navigate(self::LOGIN_URL);
			}
			$this->navigate("/my/projects/");
		}
	}
}
?>