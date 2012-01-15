<?php
class ProjectsFacade
{
	private $_projectsController;
	private $_projectsSearch;
	private $_auth;
	private $_userInfo;
	private $_count=0;
	private $_paginator;
	
	public function __construct(ProjectsModel $projectsController, ProjectSearch $projectsSearch, UserAuth $auth)
	{
		$this->_projectsSearch=$projectsSearch;
		$this->_projectsController=$projectsController;
		$this->_auth=$auth;
		$this->_userInfo=$this->_auth->getName();
	}
	
	public function addProject($projectName, $description)
	{
		$addedProjectID=$this->_projectsController->addProject($this->_userInfo["USER_ID"], $projectName, $description);
		/*try
		{
			$this->_projectsSearch->addProjectToIndex(
				new ArrayObject(array(
					"ProjectID" => $addedProjectID,
					"Name" => $projectName,
					"Description" => $description,
					"OwnerID" => $this->_userInfo["UserID"]
				))
			);
		}
		catch(Exception $exception)
		{
			$this->_projectsController->deleteProject($this->_userInfo["UserID"], $addedProjectID);
		}*/
		return $addedProjectID;
	}
	
	public function deleteProject($projectID) 
	{
		/*$this->_projectsSearch->deleteFromIndex($projectID);*/
		$this->_projectsController->deleteProject($this->_userInfo["USER_ID"], $projectID);
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
		/*$this->_projectsSearch->
				new ArrayObject(array(
					"ProjectID" => $projectRecord['ProjectID'],
					"Name" => $projectNewName,
					"Description" => $newDescription,
					"OwnerID" => $this->_userInfo["UserID"]
				));
		*/
		$this->_projectsController->setProjectName($projectID, $this->_userInfo["USER_ID"], $projectNewName, $newDescription);
	}
	
	public function searchProject($query)
	{
		/*$searchNamespace = new Zend_Session_Namespace('SEARCH');
		if ($searchNamespace->query===$query)
		{
			$projectsFound=$searchNamespace->result;
		}
		else 
		{
			$projectsFound=$this->_projectsSearch->searchProjects($query);
			$searchNamespace->query=$query;
			$searchNamespace->result=$projectsFound;
		}
		$this->_count=count($projectsFound);
		if ($projectsFound!=null)
		{
			$this->_paginator = new TarakaningULListPager($this->_count);
			$projectsFoundSlice=array_slice($projectsFound, $this->_paginator->getOffset(),$this->_paginator->getSize());
			$projectsData=$this->_projectsController->getProjectsByListWithSubscribes($this->_userInfo["UserID"],$projectsFoundSlice);
			foreach ($projectsFound as $value)
			{
				foreach ($projectsData as $projValue)
				{
					if ($projValue['ProjectID']==$value)
					{
						$result[]=$projValue;	
						break;
					}
				}
			}
		}*/
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