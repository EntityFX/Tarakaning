<?php
	class ProjectsFacade
	{
		private $_projectsController;
		private $_projectsSearch;
		private $_auth;
		private $_userInfo;
		
		public function __construct(ProjectsController $projectsController, ProjectSearch $projectsSearch, UserAuth $auth)
		{
			$this->_projectsSearch=$projectsSearch;
			$this->_projectsController=$projectsController;
			$this->_auth=$auth;
			$this->_userInfo=$this->_auth->getName();
		}
		
		public function addProject($projectName, $description)
		{
			$addedProjectID=$this->_projectsController->addProject($this->_userInfo["UserID"], $projectName, $description);
			try
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
			}
		}
		
		public function deleteProject($projectID) 
		{
			$this->_projectsSearch->deleteFromIndex($projectID);
			$this->_projectsController->deleteProject($this->_userInfo["UserID"], $projectID);
		}
		
		public function deleteProjectsFromList($userID,$projectsList)
		{
			foreach ($projectsList as $key => $value)
			{
				$this->deleteProject((int)$key);
			}
			$this->_projectsController->deleteProjectsFromList($this->_userInfo["UserID"], $projectsList);
		}
		
		public function setProjectName($projectID,$projectNewName, $newDescription)
		{
			//$this->_projectsSearch->
					new ArrayObject(array(
						"ProjectID" => $projectRecord['ProjectID'],
						"Name" => $projectNewName,
						"Description" => $newDescription,
						"OwnerID" => $this->_userInfo["UserID"]
					));
			
			$this->_projectsController->setProjectName($projectID, $this->_userInfo["UserID"], $projectNewName, $newDescription);
		}
		
		public function searchProject($query)
		{
			$projectsFound=$this->_projectsSearch->searchProjects($query);
			$projectsData=$this->_projectsController->getProjectsByList($projectsFound);
			return $projectsData;
		}
	}