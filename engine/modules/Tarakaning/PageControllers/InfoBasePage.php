<?php
require_once SOURCE_PATH.'engine/kernel/HTMLPage.php';

	class InfoBasePage extends HTMLPage
	{
		protected $_userInfo;
		
		protected function onInit()
		{
			$this->_userInfo=$this->_controller->auth->getName();
		}
		
		protected function doAssign()
		{
			$this->_smarty->assign("LOGIN",$this->_userInfo['NickName']);
			$this->_smarty->assign("TIME",$this->_userInfo['EnterTime']);
			$this->_smarty->assign("FULLNAME",
	 			$this->_userInfo['Surname'].' '.
				$this->_userInfo['Name'].' '.
	  			$this->_userInfo['SecondName']
  			);
  			$this->_smarty->assign("MAIN_MENU",$this->_controller->menu);
		}
	}
?>