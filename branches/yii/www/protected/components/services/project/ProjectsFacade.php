<?php
class ProjectsFacade
{
	private $_projectsController;
	private $_auth;
	private $_userInfo;
	private $_count=0;
	private $_paginator;
	
	public function __construct(ProjectService $projectsController, UserAuth $auth)
	{
		$this->_projectsController=$projectsController;
		$this->_auth=$auth;
		$this->_userInfo=$this->_auth->getName();
	}
	
	public function addProject($projectName, $description)
	{
		$addedProjectID=$this->_projectsController->addProject($this->_userInfo["USER_ID"], $projectName, $description);
		return $addedProjectID;
	}
	
	public function deleteProject($projectID) 
	{
		$this->_projectsController->deleteById($this->_userInfo["USER_ID"], $projectID);
	}
	
	public function deleteProjectsFromList($userID,$projectsList)
	{
		foreach ($projectsList as $key => $value)
		{
			$this->deleteProject((int)$key);
		}
		$this->_projectsController->deleteProjectsFromList($this->_userInfo["USER_ID"], $projectsList);
	}
	
	public function setProjectName($projectID,$projectNewName, $newDescription)
	{
		$this->_projectsController->editById($projectID, $this->_userInfo["USER_ID"], $projectNewName, $newDescription);
	}
	
	public function searchProject($query)
	{
		$this->_paginator = new TarakaningULListPager($this->_projectsController->searchProjectsUsingLikeCount($this->_userInfo["USER_ID"],$query,$this->_paginator));
		$result=$this->_projectsController->searchProjectsUsingLike($this->_userInfo["USER_ID"],$query,$this->_paginator);
		return $result;
	}
	
	public function getPaginator()
	{
		return $this->_paginator;
	}
	
	public function getCountFound()
	{
		return $this->_count;
	}
}