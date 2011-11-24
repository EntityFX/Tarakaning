<?php
require_once SOURCE_PATH.'engine/kernel/HTMLPage.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ProjectsController.php';

	class InfoBasePage extends HTMLPage
	{
		protected $_userInfo;
		
		protected $_projectsController;
		
		protected $_projectsList;
	
		protected $_currentProjectID;
		
		protected $_projectSelectionFlag;
		
		protected function onInit()
		{
			$this->_userInfo=$this->_controller->auth->getName();
			
			$this->_projectsController=new ProjectsController();
			$this->_projectsList=$this->_projectsController->getUserProjects($this->_userInfo["UserID"]);
			
			$this->_projectSelectionFlag=false;
			
			if ($this->_projectsList!=null)
			{
				$this->_currentProjectID=$this->request->getParam("project_id",$this->_projectsList[0]["ProjectID"]);
			}

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
			
			$this->_smarty->assign("PROJECTS",array(
				"PROJECTS_LIST" => $this->normalizeProjectsList($this->_projectsList),
				"selected" => $this->_currentProjectID
			));
			
			$this->_smarty->assign("PROJECT_SELECTION_FLAG",$this->_projectSelectionFlag);
		}
		
		protected function normalizeProjectsList(&$projectList)
		{
			if ($projectList!=null)
			{
				foreach($projectList as $value)
				{
					$res[$value["ProjectID"]]=$value["Name"];
				}
			}
			return $res;
		}
	}
?>