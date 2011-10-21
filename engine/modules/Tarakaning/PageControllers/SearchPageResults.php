<?php
require_once 'engine/modules/Tarakaning/PageControllers/InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsSearch.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

	class SearchPageResults extends InfoBasePage
	{
		private $_arPks;
		private $getData;
		private $searcher;
		
		protected function onInit()
		{
			parent::onInit();
			$this->getData = $this->request->getParams();
			if ($this->request->isGet() && $this->getData)
			{
				try 
				{
					if ($this->getData["createIndex"]=="Y") 
					{
						$this->AllIntoIndex();
					}
					else 
					{
						$this->searcher = new ProjectSearch();
						$this->_arPks = $this->searcher->Search(mb_convert_encoding($this->getData["by_proj"],"utf-8"));
					}
				} 
				catch (Exception $exception) 
				{
					$error = array("error" => $exception);
					$this->_controller->error->addError("searchError",$error);
				}
			}
		}
		
		protected function AllIntoIndex() 
		{
			$p = new ProjectsController();
			$this->searcher = new ProjectSearch();
			$this->searcher->ServiceAddAll($p->getProjects());
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("AR_SEARCH_FIELD_by_proj", htmlspecialchars($this->getData["by_proj"]));
			$this->_smarty->assign("AR_SEARCH_ITEM", $this->_arPks);
			$searchError = $this->_controller->error->getErrorByName("searchError");
			if ($searchError!=null)
			{
				$exception=$searchError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
			}
			//$this->_smarty->assign("STATUS", $this->searcher->GetCountHits());
			//$this->_smarty->assign("STATUS", $this->AllIntoIndex());
		}
	}
?>