<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ErrorReportsController.php';
require_once 'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once 'engine/libs/controls/Orderer/Orderer.php';

class ProjectBugsPage extends InfoBasePage 
{
	private $_bugsData;
	
	private $_projectsList;
	
	private $_projectBugsPaginator;
	
	private $_projectBugsOrderer;
	
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
			$count=$bugsOperation->countReportsByProject($defaultProject);
			if ($count!=null)
			{
				$this->_projectBugsPaginator=new TarakaningULListPager($count);
				$this->_projectBugsOrderer=new Orderer(new ErrorFieldsENUM());
				$this->_bugsData=$bugsOperation->getProjectOrdered(
					$defaultProject,
					new ErrorFieldsENUM($this->_projectBugsOrderer->getOrderField()),
					$this->_projectBugsOrderer->getMySQLOrderDirection(),
					$this->_projectBugsPaginator->getOffset(),
					$this->_projectBugsPaginator->getSize()
				);
			}

		}
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->assign("PROJECTS_LIST",$this->_projectsList);
		$this->_smarty->assign("MY_BUGS",$this->_bugsData);
		$this->_smarty->assign("PROJECT_BUGS_PAGINATOR",$this->_projectBugsPaginator!=null?$this->_projectBugsPaginator->getHTML():null);
		$this->_smarty->assign("MY_BUGS_ORDERER",$this->_projectBugsOrderer!=null?$this->_projectBugsOrderer->getNewUrls():null);
	}
}
?>