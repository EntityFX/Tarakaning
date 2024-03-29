<?php

Loader::LoadPageController('InfoBasePage'); 

Loader::LoadModel('Projects/ProjectsFacade'); 
Loader::LoadModel('Items/ItemsFacade');    

	class MyNewProjectPage extends InfoBasePage
	{
		private $projectOperation;
		
		protected function onInit()
		{
			parent::onInit();
			if ($this->request->isPost())
			{
				$postData=$this->request->getParams();
				$projectsOperation=new ProjectsModel();
				/*$projectSearch=new ProjectSearch(self::getGlobalEncoding());*/
				
				$projectsFacadeOperation=new ProjectsFacade(
					$projectsOperation, 
					$this->_controller->auth
				);
				try 
				{
					$projectsID=$projectsFacadeOperation->addProject($postData["project_name"], $postData["description"]);
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
					$this->navigate("/my/project/show/$projectsID/");
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