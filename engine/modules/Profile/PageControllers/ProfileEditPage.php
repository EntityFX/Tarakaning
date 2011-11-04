<?php
require_once SOURCE_PATH.'engine/modules/Tarakaning/PageControllers/InfoBasePage.php';
require_once SOURCE_PATH.'engine/modules/Auth/Logic/UsersOperation.php';


	class ProfileEditPage extends InfoBasePage
	{
		private $_bIsMe;
		
		protected function onInit()
		{
			parent::onInit(); 
			$arCurrentUser = $this->_controller->auth->getName();
			$postData=$this->request->getParams();
			if ($this->request->isPost() && $postData)
			{
				$arPostParams = $this->request->getParams();
				try 
				{
					$this->_controller->auth->changeData($arPostParams["Name"],$arPostParams["Surname"],$arPostParams["SecondName"],$arPostParams["Email"]);
				} 
				catch (Exception $exception) 
				{
					$error = array("error" => $exception);
					$this->_controller->error->addError("editProfileError",$error);
				}				
				if ($exception==null)
				{
					$this->_controller->error->addError("editProfileError",true);
				}
			}
			$this->_userInfo=$this->_controller->auth->getName();
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("AR_USER_INFO", $this->_userInfo);
			$editProfileError=$this->_controller->error->getErrorByName("editProfileError");
			if ($editProfileError===true)
			{
				$this->_smarty->assign("GOOD",true);
			}
			else if ($editProfileError!=null)
			{
				$exception=$editProfileError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
			}
		}
	}
?>