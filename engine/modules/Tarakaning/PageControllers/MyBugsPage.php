<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ErrorReportsController.php';
require_once 'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once 'engine/libs/controls/Orderer/Orderer.php';

class MyBugsPage extends InfoBasePage 
{
	private $_bugsData;
	
	private $_projectsList;
	
	private $_orderData;
	
	/**
	 * 
	 * My bugs paginator control
	 * @var TarakaningULListPager
	 */
	private $_myBugsPaginator;
	
	private $_itemKindENUM;
	
	private $_currentProjectID;
	
	protected function onInit()
	{
		parent::onInit();
		$projectsController=new ProjectsController();
		$userData=$this->_controller->auth->getName();
		$concreteUser=new ConcreteUser();
		
		$kind=$this->request->getParam("item_kind",ItemKindENUM::ALL);
		
		$this->_projectsList=$projectsController->getUserProjects($userData["UserID"]);
		if ($this->_projectsList!=null)
		{
			$this->_currentProjectID=$this->request->getParam("project_id",$this->_projectsList[0]["ProjectID"]);
			$this->_currentProjectID=$userData["DefaultProjectID"] == null ? $this->_currentProjectID : $userData["DefaultProjectID"];
						
			$bugsOperation=new ErrorReportsController($this->_currentProjectID);
			$this->_itemKindENUM=new ItemKindENUM($kind);
			$this->_myBugsPaginator=new TarakaningULListPager($bugsOperation->countReports($this->_itemKindENUM));
			$orderer=new Orderer(new ErrorFieldsENUM());
			$this->_orderData=$orderer->getNewUrls();
			$this->_bugsData=$bugsOperation->getMyOrdered(
				$this->_itemKindENUM,
				new ErrorFieldsENUM($orderer->getOrderField()),
				new MySQLOrderENUM($orderer->getOrder()),
				$this->_myBugsPaginator->getOffset(),
				$this->_myBugsPaginator->getSize()
			);
		}
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->assign("ITEM_KIND",array(
			"values" => $this->_itemKindENUM->getArray(),
			"text" => array("Дефекты и задачи","Дефекты","Задачи"),
			"selected" => $this->_itemKindENUM->getValue()
		));
		$this->_smarty->assign("PROJECTS",array(
			"PROJECTS_LIST" => $this->normalizeProjectsList($this->_projectsList),
			"selected" => $this->_currentProjectID
		));
		$this->_smarty->assign("MY_BUGS",$this->_bugsData);
		$this->_smarty->assign("MY_BUGS_PAGINATOR",$this->_myBugsPaginator->getHTML());
		$this->_smarty->assign("MY_BUGS_ORDERER",$this->_orderData);
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