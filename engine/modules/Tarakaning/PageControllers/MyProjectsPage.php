<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

	class MyProjectsPage extends InfoBasePage
	{
		private $_projectsData;
		
		private $_projectsWithoutMeData;
		
		protected function onInit()
		{
			parent::onInit();
			$projectsController=new ProjectsController();
			$userData=$this->_controller->auth->getName();
			$this->_projectsData=$projectsController->getUserProjectsInfo($userData["UserID"]);
			$this->_projectsWithoutMeData=$projectsController->getMemberProjects($userData["UserID"]);
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("MY_PROJECTS",$this->_projectsData);
			$this->_smarty->assign("PROJECTS_WITHOUT_ME",$this->_projectsWithoutMeData);
			$newProjectOK=$this->_controller->error->getErrorByName("newProjectOK");
			if ($newProjectOK)
			{
				$this->_smarty->assign("GOOD",true);
			}
		}
	}
?>