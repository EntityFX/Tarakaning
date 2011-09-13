<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

	class MyProjectsDetailPage extends InfoBasePage
	{
		private $_projectData;
		
		protected function onInit()
		{
			parent::onInit();
			$projectsOperation=new ProjectsController();
			$this->_projectData=$projectsOperation->getProjectById($this->_parameters[0]);
			if ($this->_projectData==null)
			{
				$this->navigate('/my/projects/');
			}
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("Project",$this->_projectData);
		}	
	}
?>