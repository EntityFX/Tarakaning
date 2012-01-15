<?php

Loader::LoadPageController('InfoBasePage','Tarakaning');  

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
			if ($this->request->getPost('save_project',null)!=null)
			{
				$this->_changeDefaultproject();
			}
			else if($this->request->getPost('save_profile',null)!=null)
			{
				$this->_changeProfile();
			}

		}
		$this->_userInfo=$this->_controller->auth->getName();
	}
	
	private function _changeProfile()
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
	
	private function _changeDefaultproject()
	{
		$projectID = $this->request->getPost('defaultProject');
		if ($projectID!=null)
		{
			try 
			{
				$this->_concreteUser->setDefaultProject($projectID);
				$this->_concreteUser->setCurrentProject($projectID);
			}
			catch(MySQLException $dbException)
			{
				
			}
		}
		$this->navigate('/profile/edit/#settings');
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