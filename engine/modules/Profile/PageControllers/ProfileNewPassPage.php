<?php
require_once 'engine/modules/Auth/Logic/UsersOperation.php';
require_once 'engine/modules/Auth/Logic/UserAuth.php';

class ProfileNewPassPage extends SinglePage
{	
	protected function onInit()
	{
		$postData=$this->request->getParams();
		if ($this->request->isPost() && $postData != null)
		{
			if ($postData["newPassword"] == $postData["newPasswordRepeat"])
			{
				try
				{
					$this->_controller->auth->changePassword($postData["oldPassword"],$postData["newPassword"]);
				}
				catch(Exception $exception)
				{
					$error = array("error" => $exception);
					$this->_controller->error->addError("editProfileError",$error);
				}
			}
			else 
			{
				$exception = new Exception("Поля для нового пароля не совпадают");
				$error = array("error" => $exception);
				$this->_controller->error->addError("editProfileError",$error);
			}
			if ($exception==null)
			{
				$this->_controller->error->addError("editProfileError",true);
			}
		}
		$this->navigate("/profile/edit/#pass_change");
	}
}
?>