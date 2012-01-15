<?php
	require_once SOURCE_PATH.'engine/kernel/JSONPage.php';

Loader::LoadModel('Projects/ProjectsModel');
Loader::LoadModel('Projects/ProjectFieldsUsersInfoENUM');
	
class ItemsJSON extends JSONPage
{
	
	protected function onInit()
	{
		if ($this->request->getParam(project_id,null)!=null)
		{
			$projectId=$this->request->getParam("project_id");
			$projectsController=new ProjectsModel();
			$usersList=$projectsController->getProjectUsers($projectId);
			$this->setJSONOutput($usersList);
		}
	}
		
}