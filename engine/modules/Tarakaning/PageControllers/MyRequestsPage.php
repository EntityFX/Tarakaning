<?php
	require_once SOURCE_PATH.'engine/modules/Tarakaning/PageControllers/InfoBasePage.php';
	require_once SOURCE_PATH.'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
	require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/SubscribesModel.php';
	require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/SubscribesDetailENUM.php';
	require_once SOURCE_PATH.'engine/libs/controls/Orderer/Orderer.php';
	
	class MyRequestsPage extends InfoBasePage
	{
		/**
		 * 
		 * Subscribes paginator control
		 * @var TarakaningULListPager
		 */
		private $_paginator;
		
		/**
		 * 
		 * Subscribes orderer control
		 * @var SubscribesDetailENUM
		 */
		private $_orderer;
		
		/**
		 * 
		 * Subscribes business logic
		 * @var Subscribes
		 */
		private $_subscribesOperation;
		
		/**
		 * 
		 * User's subscribes list
		 * @var array
		 */
		private $_mySubscribesList;
		
		protected function onInit()
		{
			parent::onInit();
			
			$this->_subscribesOperation=new SubscribesModel();
			
			if ($this->request->isPost())
			{
				if ($this->request->getPost("del",null)!=null)
				{
					$this->deleteSelectedItems();
				}
			}
			
			$count=$this->_subscribesOperation->getSubscribesCount($this->_userInfo["UserID"]);
			$this->_paginator=new TarakaningULListPager($count);
			$this->_orderer=new Orderer(new SubscribesDetailENUM());
			$this->_mySubscribesList=$this->_subscribesOperation->getUserSubscribes($this->_userInfo["UserID"],$this->_orderer,$this->_paginator);
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign('MY_SUBSCRIBES_PAGINATOR',$this->_paginator!=null?$this->_paginator->getHTML():null);
			$this->_smarty->assign('MY_SUBSCRIBES_ORDERER',$this->_orderer!=null?$this->_orderer->getNewUrls():null);
			$this->_smarty->assign('MY_SUBSCRIBES_LIST',$this->_mySubscribesList);
		}
		
		protected function deleteSelectedItems()
		{
			$checkboxes=$this->request->getPost("del_i");
			var_dump($checkboxes);
		}
	}