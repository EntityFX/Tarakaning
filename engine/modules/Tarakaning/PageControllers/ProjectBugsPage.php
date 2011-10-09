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
	
	private $_itemKindENUM;
	
	private $_currentProjectID;
	
	protected function onInit()
	{
		parent::onInit();
		$projectsController=new ProjectsController();
		$userData=$this->_controller->auth->getName();
		$concreteUser=new ConcreteUser();
		
		$kind=$this->request->getParam("item_kind",ItemKindENUM::ALL);
		$this->_itemKindENUM=new ItemKindENUM($kind);
		
		$this->_projectsList=$projectsController->getUserProjects($userData["UserID"]);
		if ($this->_projectsList!=null)
		{
			$this->_currentProjectID=$this->request->getParam("project_id",$this->_projectsList[0]["ProjectID"]);
			if ($this->request->getParam("project_id",null)==null)
			{
				$this->_currentProjectID=$userData["DefaultProjectID"] == null ? $this->_currentProjectID : $userData["DefaultProjectID"];
			}
			
			$bugsOperation=new ErrorReportsController();
			$count=$bugsOperation->countReportsByProject($this->_currentProjectID,$this->_itemKindENUM);
			if ($count!=null)
			{
				$this->_projectBugsPaginator=new TarakaningULListPager($count);
				$this->_projectBugsOrderer=new Orderer(new ErrorFieldsENUM());
				$this->_bugsData=$bugsOperation->getProjectOrdered(
					$this->_currentProjectID,
					$this->_itemKindENUM,
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
		$this->_smarty->assign("PROJECTS",array(
			"PROJECTS_LIST" => $this->normalizeProjectsList($this->_projectsList),
			"selected" => $this->_currentProjectID
		));
		$this->_smarty->assign("ITEM_KIND",array(
			"values" => $this->_itemKindENUM->getArray(),
			"text" => array("Дефекты и задачи","Дефекты","Задачи"),
			"selected" => $this->_itemKindENUM->getValue()
		));
		$this->_smarty->assign("MY_BUGS",$this->_bugsData);
		$this->_smarty->assign("PROJECT_BUGS_PAGINATOR",$this->_projectBugsPaginator!=null?$this->_projectBugsPaginator->getHTML():null);
		$this->_smarty->assign("MY_BUGS_ORDERER",$this->_projectBugsOrderer!=null?$this->_projectBugsOrderer->getNewUrls():null);
	}
	
	private function normalizeProjectsList(&$projectList)
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