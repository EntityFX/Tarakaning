<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ErrorReportsController.php';

	class BugAddPage extends InfoBasePage
	{
		
		private $_projectsList;
	
		protected function onInit()
		{
			parent::onInit();
			if ($this->request->isPost())
			{
				if ($this->request->getParam("add_report")!=null)
				{
					$postData=$this->request->getParams();
					$bugsOperation=new ErrorReportsController($userData["DefaultProjectID"] == null ? $postData['project_id'] : $userData["DefaultProjectID"]);
					try 
					{	
						$newItemID=$bugsOperation->addReport(
							new ItemDBKindENUM($postData['item_type']),
							new ErrorPriorityENUM($postData['priority']),
							new ErrorStatusENUM(),
							new ErrorTypeEnum($postData['error_type']),
							$postData['title'],
							$postData['description'],
							$postData['steps'],
							$postData['assigned_to']
						);
					}
					catch (Exception $exception)
					{
						$error = array(
							"error" => $exception,
							"postData" => $postData
						);
						$this->_controller->error->addError("addBugError",$error);
					}	
					if ($exception==null)
					{
						$this->_controller->error->addError("addBugOK",true);
						$this->navigate("/my/bugs/");
					}	
					$this->navigate("/bug/show/$newItemID/");
				}
			}
			$userData=$this->_controller->auth->getName();
			$concreteUser=new ConcreteUser();
			$projectsController=new ProjectsController();
			$this->_projectsList=$projectsController->getUserProjects($userData["UserID"]);
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$addBugError=$this->_controller->error->getErrorByName("addBugError");
			if ($addBugError!=null)
			{
				$exception=$addBugError["error"];
				$this->_smarty->assign(
					"ERROR",
					$exception->getMessage()
				);
				$this->_smarty->assign("DATA",$addBugError["postData"]);
			}
			$this->_smarty->assign("PROJECTS_LIST",$this->_projectsList);
		}
	}
?>