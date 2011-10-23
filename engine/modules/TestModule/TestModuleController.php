<?php
	class TestModuleController extends ModuleController
	{
		private $_helper;
		
		public function initializePages()
		{
			require_once 'engine/modules/Tarakaning/Logic/ProjectsSearch.php';
			require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';
			$projectController=new ProjectsController();
			
			$projSearch=new ProjectSearch(self::getGlobalEncoding());
			$projSearch->searchProjects("проект");
		}
	}