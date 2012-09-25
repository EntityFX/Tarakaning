<?php

Loader::LoadPageController('BugsBasePage');  

class ProjectBugsPage extends BugsBasePage 
{
	
	protected function onInit()
	{
		parent::onInit();
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->assign("PROJECT_OWNER",$this->_projectsController->getOwnerID($this->_currentProjectID));
        $this->_smarty->assign("USER_ID",(int)$this->_userInfo["USER_ID"]);
	}
	
	protected function initializeGeneralBugsData()
	{
        $count=$this->_bugsOperation->countReportsByProject($this->_currentProjectID,$this->_itemKindENUM);
        if ($count!=null)
		{
			$this->_paginator=new TarakaningULListPager($count);
			$this->_orderer=new Orderer(new ErrorFieldsENUM());
			$this->_bugsData=$this->_bugsOperation->getProjectOrdered(
				$this->_currentProjectID,
				$this->_itemKindENUM,
				new ErrorFieldsENUM($this->_orderer->getOrderField()),
				$this->_orderer->getMySQLOrderDirection(),
				$this->_paginator->getOffset(),
				$this->_paginator->getSize()
			);
		}
	}
}
?>