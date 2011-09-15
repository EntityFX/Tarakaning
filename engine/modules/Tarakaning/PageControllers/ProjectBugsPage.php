<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ErrorReportsController.php';

class ProjectBugsPage extends InfoBasePage 
{
	private $_bugsData;
	
	private $_projectsList;
	
	protected function onInit()
	{
		parent::onInit();
		$projectsController=new ProjectsController();
		$userData=$this->_controller->auth->getName();
		$concreteUser=new ConcreteUser();
		$this->_projectsList=$projectsController->getUserProjects($userData["UserID"]);
		if ($this->_projectsList!=null)
		{
			$defaultProject=$userData["DefaultProjectID"] == null ? $this->_projectsList[0]['ProjectID'] : $userData["DefaultProjectID"];
			$bugsOperation=new ErrorReportsController();
			$this->_bugsData=$bugsOperation->getReportsByProject($defaultProject);
		}
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->assign("PROJECTS_LIST",$this->_projectsList);
		$this->_smarty->assign("MY_BUGS",$this->_bugsData);
	}
}
?>