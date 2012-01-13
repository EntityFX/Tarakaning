<?php
require_once 'InfoBasePage.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ProjectsController.php';

	class MyProjectEditPage extends InfoBasePage
	{
		private $_projectData;
		
		protected function onInit()
		{
			parent::onInit();
			if ($this->request->isPost())
			{
				$projectsOperation=new ProjectsController();
				$postData=$this->request->getParams();
				$this->_projectData=$projectsOperation->getProjectById($postData['project_id']);
				if ($postData['save']!=null)
				{
					try
					{
						$projectsOperation->setProjectName($postData['project_id'],$this->_userInfo["USER_ID"], $postData['project_name'], $postData['description']);
					}
					catch (Exception $exception)
					{
						$error = array(
							"error" => $exception,
							"postData" => $postData
						);
						$this->_controller->error->addError("editProjectError",$error);
					}
					if ($exception==null)
					{
						$this->_controller->error->addError("editProjectError",true);
						$this->_projectData['Name']=$postData['project_name'];
						$this->_projectData['Description']=$postData['description']; 
					}
				}
				else 
				{
					if ($this->_projectData==null)
					{
						$this->navigate('/my/projects/');
					}
				}
			}
			else
			{
				$this->navigate('/my/projects/');
			}
		}
		
		protected function doAssign()
		{
			parent::doAssign();  
            $this->_projectData['Name']=htmlspecialchars($this->_projectData['Name']);   
            var_dump($this->_projectData) ;
			$this->_smarty->assign("PROJECT_DATA",$this->_projectData);
			$editProjectError=$this->_controller->error->getErrorByName("editProjectError");
			if ($editProjectError===true)
			{
				$this->_smarty->assign("GOOD",true);
			}
			else if ($editProjectError!=null)
			{
				$exception=$editProjectError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
			}
		}	
	}
?>