<?php

require_once 'Zend/Captcha/Image.php';

Loader::LoadModel('UsersOperation','Auth');

	class DoRegistration extends SinglePageController
	{
		protected function onInit()
		{
			if ($this->request->isPost())
			{

				$reg=new UsersOperation();
				try
				{
					$this->validateForm();
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
					$this->navigate('/registration/');
				}
				if ($exception==null)
				{
					$this->_controller->error->addError("registrationOk",true);
					$this->navigate(AuthController::LOGIN_URL);
				}
			}
			else 
			{
				$this->navigate(AuthController::LOGIN_URL);
			}
		}
		
		private function validateForm()
		{
			$captcha=new Zend_Captcha_Image();
			$captchaValue=array(
				"id" => $this->request->getPost("captchaId"),
				"input" => $this->request->getPost("captcha")
			);
			if (!$captcha->isValid($captchaValue))
			{
				throw new Exception('Код на картинке неверный');
			}
			$password=$this->request->getPost("password");
			$commitPassword=$this->request->getPost("commitPass");
			if ($password!==$commitPassword)
			{
				throw new Exception('Подтверждение пароля не совпадает');
			}
		}
	}
?>