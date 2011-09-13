<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ErrorReportsController.php';

class BugPage extends InfoBasePage 
{
	private $_bugData;
	
	private $_projectsList;
	
	protected function onInit()
	{
		parent::onInit();
		$userData=$this->_controller->auth->getName();
		$projectsController=new ProjectsController();
		$this->_projectsList=$projectsController->getUserProjects($userData["UserID"]);
		$bugsOperation=new ErrorReportsController(
			$userData["DefaultProjectID"] == null ? $this->_projectsList[0]['ProjectID'] : $userData["DefaultProjectID"],
			$userData["UserID"]
		);
		if (isset($this->_parameters[0]))
		{
			$this->_bugData=$bugsOperation->getReport($this->_parameters[0]);
		}
		else 
		{
			$this->navigate("/my/bugs/");
		}
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->assign("BUG",$this->_bugData);
	}
}
?>