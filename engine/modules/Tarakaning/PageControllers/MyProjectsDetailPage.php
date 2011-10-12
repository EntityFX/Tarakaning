<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';
require_once 'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once 'engine/libs/controls/Orderer/Orderer.php';

	class MyProjectsDetailPage extends InfoBasePage
	{
		private $_projectData;
		
		private $_projectUsers;
		
		private $_projectDetailPage;
		
		private $_myProjectsInfoPaginator;
		
		private $_orderer;
		
		private $_orderData;
		
		protected function onInit()
		{
			parent::onInit();
			$projectsOperation=new ProjectsController();
			$this->_projectData=$projectsOperation->getProjectById($this->_parameters[0]);
			
			$this->_myProjectsInfoPaginator=new TarakaningULListPager($projectsOperation->getProjectUsersInfoCount($this->_parameters[0]));
			$this->_orderer=new Orderer(new ProjectFieldsUsersInfoENUM());
			$this->_orderData=$this->_orderer->getNewUrls();
			
			$this->_projectUsers=$projectsOperation->getProjectsUsersInfoPagOrd(
				$this->_parameters[0], 
				new ProjectFieldsUsersInfoENUM($this->_orderer->getOrderField()), 
				new MySQLOrderENUM($this->_orderer->getOrder()),
				$this->_myProjectsInfoPaginator->getOffset(),
				$this->_myProjectsInfoPaginator->getSize()
			);
			var_dump($this->_projectUsers);
			if ($this->_projectData==null)
			{
				$this->navigate('/my/projects/');
			}
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("PROJECT_USERS",$this->_projectUsers);
			$this->_smarty->assign("Project",$this->_projectData);
			$this->_smarty->assign("MY_PROJECT_DETAIL_PAGINATOR",$this->_myProjectsInfoPaginator->getHTML());
			$this->_smarty->assign("MY_PROJECT_ORDERER",$this->_orderData);
		}	
	}
?>