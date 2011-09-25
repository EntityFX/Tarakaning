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
	
	protected function onInit()
	{
		parent::onInit();
		$projectsController=new ProjectsController();
		$userData=$this->_controller->auth->getName();
		$concreteUser=new ConcreteUser();
		$this->_projectsList=$projectsController->getUserProjects($userData["UserID"]);
		if ($this->_projectsList!=null)
		{
			$bugsOperation=new ErrorReportsController($userData["DefaultProjectID"] == null ? $this->_projectsList[0]['ProjectID'] : $userData["DefaultProjectID"]);
			$this->_myBugsPaginator=new TarakaningULListPager($bugsOperation->countReports(new ItemKindENUM(0)));
			$errorFields=new ErrorFieldsENUM();
			$orderer=new Orderer(new ErrorFieldsENUM());
			$this->_orderData=$orderer->getNewUrls();
			$this->_bugsData=$bugsOperation->getMyOrdered(
				new ItemKindENUM(0),
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
		$this->_smarty->assign("PROJECTS_LIST",$this->_projectsList);
		$this->_smarty->assign("MY_BUGS",$this->_bugsData);
		$this->_smarty->assign("MY_BUGS_PAGINATOR",$this->_myBugsPaginator->getHTML());
		$this->_smarty->assign("MY_BUGS_ORDERER",$this->_orderData);
	}
}
?>