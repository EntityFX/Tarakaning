<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

	class MyProjectsPage extends InfoBasePage
	{
		private $_projectsData;
		
		protected function onInit()
		{
			parent::onInit();
			$projectsController=new ProjectsController();
			$userData=$this->_controller->auth->getName();
			$this->_projectsData=$projectsController->getProjectsByUser($userData["UserID"]);
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("MY_PROJECTS",$this->_projectsData);
		}
	}
?>