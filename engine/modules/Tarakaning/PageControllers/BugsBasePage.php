<?php

Loader::LoadPageController('InfoBasePage');

Loader::LoadModel('Projects/ProjectsModel');  
Loader::LoadModel('Items/ItemsModel');
Loader::LoadModel('Items/ErrorStatusENUM');  
Loader::LoadModel('Items/ErrorPriorityENUM');  
Loader::LoadModel('Items/ErrorTypeENUM');  
Loader::LoadModel('Requests/RequestModel');  
Loader::LoadModel('Items/ItemAssignment');  
Loader::LoadModel('Items/ErrorFieldsENUM');  
Loader::LoadModel('Items/ItemKindENUM');  
Loader::LoadModel('Items/ItemDBKindENUM');  
Loader::LoadModel('ConcreteUser'); 

Loader::LoadControl('TarakaningULListPager');  
Loader::LoadSystem('controls','Orderer/Orderer');
Loader::LoadSystem('addons','Serialize');

abstract class BugsBasePage extends InfoBasePage
{
	protected $_bugsData;
	
	protected $_itemKindENUM;
	
	protected $_bugsOperation;
	
	protected $_paginator;
	
	protected $_orderer;
	
	protected function onInit($useInitialProject=false)
	{
		parent::onInit();
		
		$this->_projectSelectionFlag=true;
		
		$itemKindGet=$this->request->getParam("item_kind",ItemKindENUM::ALL);

		$this->_itemKindENUM=new ItemKindENUM($itemKindGet);
		if ($this->_projectsList!=null)
		{
			$projectExists=$this->_projectsController->isProjectExists($this->_currentProjectID);
			if ($projectExists)
			{
				$this->_bugsOperation=new ItemsModel($useInitialProject?$this->_currentProjectID:null);
	
				if ($this->request->isPost())
				{
					if ($this->request->getPost("del",null)!=null)
					{
						$this->deleteSelectedItems();
					}
				}
				
				$this->initializeGeneralBugsData();
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