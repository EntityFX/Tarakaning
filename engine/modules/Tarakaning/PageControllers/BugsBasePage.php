<?php
require_once 'InfoBasePage.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ErrorReportsController.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once SOURCE_PATH.'engine/libs/controls/Orderer/Orderer.php';
require_once SOURCE_PATH.'engine/system/addons/Serialize.php';

abstract class BugsBasePage extends InfoBasePage
{
	protected $_bugsData;
	
	protected $_projectsList;
	
	protected $_itemKindENUM;
	
	protected $_currentProjectID;
	
	protected $_bugsOperation;
	
	protected $_projectController;
	
	protected $_paginator;
	
	protected $_orderer;
	
	protected function onInit($useInitialProject=false)
	{
		parent::onInit();
		$this->_projectsController=new ProjectsController();
		$userData=$this->_controller->auth->getName();
		
		$kind=$this->request->getParam("item_kind",ItemKindENUM::ALL);
		$this->_itemKindENUM=new ItemKindENUM($kind);
		
		$this->_projectsList=$this->_projectsController->getUserProjects($this->_userInfo["UserID"]);
		
		if ($this->_projectsList!=null)
		{
			$this->_currentProjectID=$this->request->getParam("project_id",$this->_projectsList[0]["ProjectID"]);
			if ($this->request->getParam("project_id",null)==null)
			{
				$this->_currentProjectID=$this->_userInfo["DefaultProjectID"] == null ? $this->_currentProjectID : $this->_userInfo["DefaultProjectID"];
			}	

			$projectExists=$this->_projectsController->isProjectExists($this->_currentProjectID);
			if ($projectExists)
			{
				$this->_bugsOperation=new ErrorReportsController($useInitialProject?$this->_currentProjectID:null);
	
				if ($this->request->isPost())
				{
					if ($this->request->getPost("del",null)!=null)
					{
						$this->deleteSelectedItems();
					}
				}
				
				$this->initializeGeneralBugsData();
			}
			else 
			{
				$this->navigate($this->getModuleURL());
			}
		}
	}
	
	protected function deleteSelectedItems()
	{
		$checkboxes=$this->request->getPost("del_i");
		$this->_bugsOperation->deleteReportsFromList(
			Serialize::SerializeForStoredProcedure($checkboxes),
			$this->_userInfo["UserID"],
			$this->_currentProjectID
		);
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
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->assign("ITEM_KIND",array(
			"values" => $this->_itemKindENUM->getArray(),
			"text" => array("Дефекты и задачи","Дефекты","Задачи"),
			"selected" => $this->_itemKindENUM->getValue()
		));
		$this->_smarty->assign("PROJECTS",array(
			"PROJECTS_LIST" => $this->normalizeProjectsList($this->_projectsList),
			"selected" => $this->_currentProjectID
		));
		$this->_smarty->assign("PROJECT_BUGS_PAGINATOR",$this->_paginator!=null?$this->_paginator->getHTML():null);
		$this->_smarty->assign("MY_BUGS_ORDERER",$this->_orderer!=null?$this->_orderer->getNewUrls():null);
		$this->_smarty->assign("MY_BUGS",$this->_bugsData);
	}
	
	/**
	 * 
	 *  Метод, в котором должны определяться специфические выборки айтемов для страниц
	 */
	abstract protected function initializeGeneralBugsData();

}