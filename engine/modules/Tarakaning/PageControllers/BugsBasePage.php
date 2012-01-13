<?php
require_once 'InfoBasePage.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ErrorReportsController.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once SOURCE_PATH.'engine/libs/controls/Orderer/Orderer.php';
require_once SOURCE_PATH.'engine/system/addons/Serialize.php';

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
		
		$kind=$this->request->getParam("item_kind",ItemKindENUM::ALL);
		$this->_itemKindENUM=new ItemKindENUM($kind);
		
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
			"text" => array("������� � ������","�������","������"),
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
	 *  �����, � ������� ������ ������������ ������������� ������� ������� ��� �������
	 */
	abstract protected function initializeGeneralBugsData();

}