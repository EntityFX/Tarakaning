<?php

Loader::LoadPageController('InfoBasePage');

Loader::LoadModel('ItemsModel');
Loader::LoadModel('ItemsFacade');   
Loader::LoadModel('ItemsHistoryModel');   

class BugAddPage extends InfoBasePage
{

	protected function onInit()
	{
		parent::onInit();
		
		if ($this->_projectsList==null)
		{
			$this->navigate('/my/projects/');
		}
		
		if ($this->request->isPost())
		{
			if ($this->request->getParam("add_report")!=null)
			{
				$postData=$this->request->getParams();
				$projectID=$userData["DefaultProjectID"] == null ? $postData['bug_project_id'] : $userData["DefaultProjectID"];
				$bugsOperation=new ItemsModel($projectID);
				$itemsFacade=new ItemsFacade(
					$bugsOperation, 
					new ItemsHistoryModel(), 
					$this->_controller->auth,
					$projectID
				);
				try 
				{	
					$newItemID=$itemsFacade->addItem(
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
					$this->navigate("/bug/show/$newItemID/");
				}	
				
			}
		}
		$userData=$this->_controller->auth->getName();
		$concreteUser=new ConcreteUser();
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
		//$this->_smarty->assign("PROJECTS_LIST",$this->_projectsList);
	}
}
?>