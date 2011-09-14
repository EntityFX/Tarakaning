<?php
require_once 'engine/modules/Tarakaning/PageControllers/InfoBasePage.php';
require_once 'engine/modules/Auth/Logic/UsersOperation.php';


	class ProfileShowPage extends InfoBasePage
	{
		private $_arUserInfo;
		private $_bIsMe;
		
		protected function onInit()
		{
			parent::onInit();
			$arCurrentUser = $this->_controller->auth->getName();
			if(isset($this->_parameters))
			{
				$iUserId = (int)$this->_parameters[0];
				
				$user = new UsersOperation();
				$this->_arUserInfo = $user->getById($iUserId);
				if($this->_arUserInfo == null)
				{
					$this->navigate("/profile/show/");
				}
				if ($arCurrentUser["UserID"]==$iUserId)
				{
					$this->_bIsMe = true;
				}
				else 
				{
					$this->_bIsMe = false;
				}
			}
			else 
			{
				$this->_arUserInfo = $this->_controller->auth->getName();
				$this->_bIsMe = true;
			}
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("AR_USER_INFO", $this->_arUserInfo);
			$this->_smarty->assign("B_IS_ME", $this->_bIsMe);
		}
	}
?>