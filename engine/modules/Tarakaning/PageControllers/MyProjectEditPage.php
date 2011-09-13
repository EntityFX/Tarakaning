<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

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
						$projectsOperation->setProjectName($this->_userInfo["UserID"], $postData['project_name'], $postData['project_id']);	
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