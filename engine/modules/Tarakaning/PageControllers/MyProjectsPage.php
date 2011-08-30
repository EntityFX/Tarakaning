<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

	class MyProjectsPage extends InfoBasePage
	{
		protected function onInit()
		{
			parent::onInit();
			//$projectsController=new ProjectsController();
			var_dump($this->_controller->auth->getName());
			//$projectsController->getOwnerID(null);
		}
		
		protected function doAssign()
		{
			parent::doAssign();
		}
	}
?>