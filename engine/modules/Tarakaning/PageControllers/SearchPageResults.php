<?php

Loader::LoadPageController('InfoBasePage'); 

Loader::LoadModel('ProjectsSearch');
Loader::LoadModel('ProjectsModel');
Loader::LoadModel('ProjectsFacade'); 
Loader::LoadModel('SubscribesModel');  

Loader::LoadControl('TarakaningULListPager');  

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
			$this->_projectsOperation=new ProjectsModel();
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