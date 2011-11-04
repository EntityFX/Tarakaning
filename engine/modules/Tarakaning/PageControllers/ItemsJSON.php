<?php
	require_once SOURCE_PATH.'engine/kernel/JSONPage.php';
	require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ProjectsController.php';
	
	class ItemsJSON extends JSONPage
	{
		
		protected function onInit()
		{
			if ($this->request->getParam(project_id,null)!=null)
			{
				$projectId=$this->request->getParam("project_id");
				$projectsController=new ProjectsController();
				$usersList=$projectsController->getProjectUsers($projectId);
				$this->setJSONOutput($usersList);
			}
		}
			
	}