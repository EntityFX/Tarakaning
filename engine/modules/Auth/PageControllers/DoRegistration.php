<?php
require_once 'engine/modules/Auth/Logic/UsersOperation.php';

	class DoRegistration extends SinglePage
	{
		protected function onInit()
		{
			$reg=new UsersOperation();
			if ($this->request->isPost())
			{
				try
				{
					$reg->createUser(
						$this->request->getPost("login"),
						$this->request->getPost("password"),
						0,
						$this->request->getPost("name"),
						$this->request->getPost("surname"),
						$this->request->getPost("secondName"),
						$this->request->getPost("eMail")
					);
				}
				catch(Exception $exception)
				{
					$error = array(
						"error" => $exception,
						"postData" => array(
							"login" => $this->request->getPost("login"),
							"name" => $this->request->getPost("name"),
							"surname" => $this->request->getPost("surname"),
							"secondName" => $this->request->getPost("secondName"),
							"eMail" => $this->request->getPost("eMail")
						)
					);
					$this->_controller->error->addError("registrationError",$error);
				}
				$this->_controller->error->addError("registrationOk",true);
			}
			$this->navigate(AuthController::LOGIN_URL);
		}
	}
?>