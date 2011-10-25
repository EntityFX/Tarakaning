<?php
	class ProjectsFacade
	{
		private $_projectsController;
		private $_projectsSearch;
		private $_auth;
		
		public function __construct(ProjectsController $projectsController, ProjectSearch $projectsSearch, UserAuth $auth)
		{
			$this->_projectsSearch=$projectsSearch;
			$this->_projectsController=$projectsController;
			$this->_auth=$auth;
		}
		
		public function addProject($projectName, $description)
		{
			$userInfo=$this->_auth->getName();
			
			$addedProjectID=$this->_projectsController->addProject($userInfo["UserID"], $projectName, $description);
			try
			{
				$this->_projectsSearch->addProjectToIndex(
					new ArrayObject(array(
						"ProjectID" => $projectRecord['ProjectID'],
						"Name" => $projectName,
						"Description" => $description,
						"OwnerID" => $userInfo["UserID"]
					))
				);
			}
			catch(Exception $exception)
			{
				$this->_projectsController->deleteProject($userInfo["UserID"], $addedProjectID);
			}
		}
		
		public function deleteProject($userID, $projectID) 
		{
			
		}
		
		public function deleteProjectsFromList($userID,$projectsList)
		{
			
		}
		
		public function setProjectName($projectID,$userID, $projectNewName, $newDescription)
		{
		
		}
	}