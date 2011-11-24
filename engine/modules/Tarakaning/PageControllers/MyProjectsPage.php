<?php
require_once 'InfoBasePage.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once SOURCE_PATH.'engine/libs/controls/Orderer/Orderer.php';
require_once SOURCE_PATH.'engine/system/addons/Serialize.php';

	class MyProjectsPage extends InfoBasePage
	{
		private $_projectsData;
		
		private $_projectsWithoutMeData;
		
		/**
		 * 
		 * My projects paginator control
		 * @var TarakaningULListPager
		 */
		private $_myProjectsPaginator;
		
		private $_myProjectsOrderer;
		
		private $_userData;
		
		/**
		 * 
		 * Member projects paginator control
		 * @var TarakaningULListPager
		 */
		private $_memberProjectsPaginator;
		
		private $_memberProjectsOrderer;
		
		protected function onInit()
		{
			parent::onInit();
			$this->_userData=$this->_controller->auth->getName();
			
			$this->_myProjectsPaginator=new TarakaningULListPager($this->_projectsController->countUserProjectsInfo($userData["UserID"]),'myPage');
			$this->_myProjectsPaginator->setIDTag('my_project');
			$this->_myProjectsOrderer=new Orderer(new MyProjectsFieldsENUM());
			
			$this->_memberProjectsPaginator=new TarakaningULListPager($this->_projectsController->countMemberProjects($userData["UserID"]),'memberPage');
			$this->_memberProjectsPaginator->setIDTag('all_projects');
			$this->_memberProjectsOrderer=new Orderer(new MyProjectsFieldsENUM());

			if ($this->request->isPost())
			{
				if ($this->request->getPost("del",null)!=null)
				{
					$this->deleteSelectedItems();
				}
			}
			
			$this->_projectsData=$this->_projectsController->getUserProjectsInfo(
				$this->_userData["UserID"],
				new MyProjectsFieldsENUM($this->_myProjectsOrderer->getOrderField()),
				$this->_myProjectsOrderer->getMySQLOrderDirection(),
				$this->_myProjectsPaginator->getOffset(),
				$this->_myProjectsPaginator->getSize()
			);
			
			$this->_projectsWithoutMeData=$this->_projectsController->getMemberProjects(
				$this->_userData["UserID"],
				new MyProjectsFieldsENUM($this->_memberProjectsOrderer->getOrderField()),
				$this->_memberProjectsOrderer->getMySQLOrderDirection(),
				$this->_memberProjectsPaginator->getOffset(),
				$this->_memberProjectsPaginator->getSize()
			);
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("MY_PROJECTS",$this->_projectsData);
			$this->_smarty->assign("PROJECTS_WITHOUT_ME",$this->_projectsWithoutMeData);
			
			$this->_smarty->assign("MY_PROJECTS_PAGINATOR",$this->_myProjectsPaginator->getHTML());
			$this->_smarty->assign("MY_PROJECTS_ORDERER",$this->_myProjectsOrderer->getNewUrls());
			
			$this->_smarty->assign("MEMBER_PROJECTS_PAGINATOR",$this->_memberProjectsPaginator->getHTML());
			$this->_smarty->assign("MEMBER_PROJECTS_ORDERER",$this->_memberProjectsOrderer->getNewUrls());
		}
		
		protected function deleteSelectedItems()
		{
			$checkboxes=$this->request->getPost("del_i");
			$this->_projectsController->deleteProjectsFromList(
				$this->_userData["UserID"],
				$checkboxes
			);
		}
	}
?>