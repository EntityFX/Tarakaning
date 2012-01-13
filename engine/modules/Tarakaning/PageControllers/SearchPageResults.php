<?php
require_once SOURCE_PATH.'engine/modules/Tarakaning/PageControllers/InfoBasePage.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ProjectsSearch.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ProjectsController.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ProjectsFacade.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/Subscribes.php';

	class SearchPageResults extends InfoBasePage
	{
		private $_arPks;
		private $getData;
		private $_searcher;
		private $_projectsOperation;
		private $_paginator;
		private $_count;
		
		protected function onInit()
		{
			parent::onInit();
			$this->getData = $this->request->getParams();
			
			if ($this->request->isPost() && $this->request->getPost("projectID")!=null)
			{
				$this->sendProjectRequest();
			}
			
			if (isset($this->getData["by_proj"]) && $this->getData["by_proj"]!='')
			{
				$this->_projectsOperation=new ProjectsController();
				$this->_searcher = new ProjectSearch(self::getGlobalEncoding());
				
				$projectsFacadeOperation=new ProjectsFacade(
					$this->_projectsOperation, 
					$this->_searcher, 
					$this->_controller->auth
				);
				
				$reset=isset($this->getData["search"]);		
				try 
				{
					$this->_arPks=$projectsFacadeOperation->searchProject($this->getData['by_proj'],$reset);
				} 
				catch (Exception $exception) 
				{
					$error = array("error" => $exception);
					$this->_controller->error->addError("searchError",$error);
				}
				
				$this->_paginator=$projectsFacadeOperation->getPaginator();
				$this->_count=$projectsFacadeOperation->getCountFound();
			}
			
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("SEARCH_QUERY", htmlspecialchars($this->getData["by_proj"]));
			$this->_smarty->assign("AR_SEARCH_ITEM", $this->_arPks);
			$this->_smarty->assign("PROJECT_SEARCH_PAGINATOR",$this->_paginator!=null?$this->_paginator->getHTML():null);
			$searchError = $this->_controller->error->getErrorByName("searchError");
			$this->_smarty->assign("COUNT",$this->_count);
			if ($searchError!=null)
			{
				$exception=$searchError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
			}
		}
		
		private function sendProjectRequest()
		{
			$subscribesOperation=new SubscribesModel();
			$subscribesOperation->sendRequest($this->_userInfo['UserID'], (int)$this->request->getPost("projectID"));
		}
	}
?>