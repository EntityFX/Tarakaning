<?php
Loader::LoadModel('UserAuth');

class DoLogin extends SinglePageController
{
	const MY_PROJECTS_URL='/my/projects/';
	const LOGIN_URL='/login/';
	
	protected function onInit()
	{
		$auth=$this->_controller->auth;
		if ($this->_controller->auth->isEntered())
		{
			$this->navigate(AuthCheckerControllerAbstract::MY_PROJECTS_URL);
		}
		else 
		{
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
				$this->navigate(AuthCheckerControllerAbstract::MY_PROJECTS_URL);
			}
		}
	}
}
?>