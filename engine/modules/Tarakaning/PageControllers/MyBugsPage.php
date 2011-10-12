<?php
require_once 'BugsBasePage.php';

class MyBugsPage extends BugsBasePage 
{	
	protected $_paginator;
	
	protected $_orderer;
	
	protected function onInit()
	{
		parent::onInit(true);
	}
	
	protected function doAssign()
	{
		parent::doAssign();
	}
	
	protected function initializeGeneralBugsData()
	{
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
	}
}
?>