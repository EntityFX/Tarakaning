<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';
require_once 'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';

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
		
		/**
		 * 
		 * Member projects paginator control
		 * @var TarakaningULListPager
		 */
		private $_memberProjectsPaginator;
		
		protected function onInit()
		{
			parent::onInit();
			$projectsController=new ProjectsController();
			$userData=$this->_controller->auth->getName();
			$this->_myProjectsPaginator=new TarakaningULListPager($projectsController->countUserProjectsInfo($userData["UserID"]),'myPage');
			$this->_myProjectsPaginator->setIDTag('my_project');
			$this->_memberProjectsPaginator=new TarakaningULListPager($projectsController->countMemberProjects($userData["UserID"]),'memberPage');
			$this->_memberProjectsPaginator->setIDTag('all_projects');
			$this->_projectsData=$projectsController->getUserProjectsInfo(
				$userData["UserID"],
				$this->_myProjectsPaginator->getOffset(),
				$this->_myProjectsPaginator->getSize()
			);
			$this->_projectsWithoutMeData=$projectsController->getMemberProjects(
				$userData["UserID"],
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
			$this->_smarty->assign("MEMBER_PROJECTS_PAGINATOR",$this->_memberProjectsPaginator->getHTML());
			$newProjectOK=$this->_controller->error->getErrorByName("newProjectOK");
			if ($newProjectOK)
			{
				$this->_smarty->assign("GOOD",true);
			}
		}
	}
?>