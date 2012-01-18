<?php
require_once SOURCE_PATH.'engine/kernel/HTMLPageController.php';

Loader::LoadModel('Projects/ProjectsModel',"Tarakaning");
Loader::LoadModel('Projects/ProjectFieldsUsersInfoENUM',"Tarakaning");
Loader::LoadModel('Projects/MyProjectsFieldsENUM',"Tarakaning");  

Loader::LoadSystem('addons','Serialize'); 

class InfoBasePage extends HTMLPageController
{
	protected $_userInfo;
	/**
	 * Логика проектов
	 * 
	 * @var ProjectsController
	 */
	protected $_projectsController;
	
	/**
	 * Список проектов текущего пользователя
	 * 
	 * @var array
	 */
	protected $_projectsList;

	protected $_currentProjectID;
	
	protected $_projectSelectionFlag;
	
	/**
	* @var ConcreteUser
	*/
	protected $_concreteUser;
	
	protected function onInit()
	{
		$this->_userInfo=$this->_controller->auth->getName();
		$this->_concreteUser=$this->_controller->auth;
		
		$this->_projectsController=new ProjectsModel();
		$this->_projectsList=$this->_projectsController->getUserProjects($this->_userInfo["USER_ID"]);
		$this->_projectSelectionFlag=false;
		
		if ($this->_projectsList!=null)
		{
			$this->_currentProjectID=$this->request->getParam("project_id",$this->_concreteUser->getCurrentProject());
			if ($this->_currentProjectID!=null)
			{
				if ($this->checkIsProjectInList())
				{
					$this->_concreteUser->setCurrentProject($this->_currentProjectID);
				}
				else
				{
					$this->_concreteUser->setCurrentProject($this->_projectsList[0]['ProjectID']);
					$this->_currentProjectID=$this->_concreteUser->getCurrentProject();
				}
			}
            else
            {
                $this->_concreteUser->setCurrentProject($this->_projectsList[0]['ProjectID']);
            }
		}
	}
	
	private function checkIsProjectInList()
	{
		foreach($this->_projectsList as $projectItem)
		{
			if ((int)$projectItem['ProjectID']==$this->_currentProjectID)
			{
				return true;
			}
		}
		return false;
	}
	
	protected function doAssign()
	{
		$this->_smarty->assign("LOGIN",$this->_userInfo['NICK']);
		$this->_smarty->assign("TIME",$this->_userInfo['EnterTime']);
		$this->_smarty->assign("FULLNAME",
	 		$this->_userInfo['LAST_NM'].' '.
			$this->_userInfo['FRST_NM'].' '.
	  		$this->_userInfo['SECND_NM']
  		);
  		$this->_smarty->assign("MAIN_MENU",$this->_controller->menu);
		$this->_smarty->assign("PROJECTS",array(
			"PROJECTS_LIST" => $this->normalizeProjectsList($this->_projectsList),
			"selected" => $this->_currentProjectID
		));
		
		$this->_smarty->assign("PROJECT_SELECTION_FLAG",$this->_projectSelectionFlag);
	}
	
	protected function normalizeProjectsList(&$projectList)
	{
		if ($projectList!=null)
		{
			foreach($projectList as $value)
			{
				$res[$value["ProjectID"]]=$value["Name"];
			}
		}
		return $res;
	}
}
?>