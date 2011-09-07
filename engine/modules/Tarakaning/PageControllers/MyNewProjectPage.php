<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';
	class MyNewProjectPage extends InfoBasePage
	{
		private $projectOperation;
		
		protected function onInit()
		{
			parent::onInit();
			if ($this->request->isPost())
			{
				$postData=$this->request->getParams();
				$projectsOperation=new ProjectsController();
				$userData=$this->_controller->auth->getName();
				try 
				{
					$projectsOperation->addProject(
						$userData["UserID"],
						$postData["project_name"], 
						$postData["description"]
					);
				}
				catch (Exception $exception)
				{
					$error = array(
						"error" => $exception,
						"postData" => $postData
					);
					$this->_controller->error->addError("addProjectError",$error);
				}	
				if ($exception==null)
				{
					$this->_controller->error->addError("newProjectOK",true);
					$this->navigate("/my/projects/");
				}
			}
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$addProjectError=$this->_controller->error->getErrorByName("addProjectError");
			if ($addProjectError!=null)
			{
				$exception=$addProjectError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
				$this->_smarty->assign("DATA",$addProjectError["postData"]);
			}
		}
	}
?>