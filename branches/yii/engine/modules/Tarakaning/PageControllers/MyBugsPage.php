<?php

Loader::LoadPageController('BugsBasePage');

class MyBugsPage extends BugsBasePage 
{	
	/**
	 * 
	 * Список айтемов, назначенных пользователю
	 * @var array
	 */
	private $_assignedBugsData;
	
	private $_paginatorForAssigned;
	
	private $_ordererForAssigned;
	
	protected function onInit()
	{
		parent::onInit(true);
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->clearAssign('PROJECT_BUGS_PAGINATOR');

		$this->_smarty->assign("MY_ASSIGNED_BUGS_PAGINATOR",$this->_paginatorForAssigned!=null?$this->_paginatorForAssigned->getHTML():null);
		$this->_smarty->assign("MY_PROJECT_BUGS_PAGINATOR",$this->_paginator!=null?$this->_paginator->getHTML():null);
		
		$this->_smarty->assign("MY_ASSIGNED_BUGS_ORDERER",$this->_ordererForAssigned!=null?$this->_ordererForAssigned->getNewUrls():null);
		$this->_smarty->assign("MY_ASSIGNED_BUGS",$this->_assignedBugsData);
	}
	
	protected function initializeGeneralBugsData()
	{
		if ($this->request->isPost())
		{
            if ($this->request->getPost("del_assigned",null)==='')
			{
                $this->deleteSelectedItems();
                $this->navigate($this->_url);
			}
		}
				
		$count=$this->_bugsOperation->countReports($this->_itemKindENUM);
		$this->_paginator=new TarakaningULListPager($count);
		$this->_orderer=new Orderer(new ErrorFieldsENUM());
		$this->_bugsData=$this->_bugsOperation->getMyOrdered(
			$this->_itemKindENUM,
			new ErrorFieldsENUM($this->_orderer->getOrderField()),
			new MySQLOrderENUM($this->_orderer->getOrder()),
			$this->_paginator->getOffset(),
			$this->_paginator->getSize()
		);

		$assignedCount=$this->_bugsOperation->countAssignedReports($this->_itemKindENUM);
		$this->_paginatorForAssigned=new TarakaningULListPager($assignedCount,"assignedPage");
		$this->_ordererForAssigned=new Orderer(new ErrorFieldsENUM(),"assignedOrderBy");
		$this->_assignedBugsData=$this->_bugsOperation->getAssignedToMe(
			$this->_itemKindENUM,
			new ErrorFieldsENUM($this->_ordererForAssigned->getOrderField()),
			new MySQLOrderENUM($this->_ordererForAssigned->getOrder()),
			$this->_paginatorForAssigned->getOffset(),
			$this->_paginatorForAssigned->getSize()
		);
	}
}
?>