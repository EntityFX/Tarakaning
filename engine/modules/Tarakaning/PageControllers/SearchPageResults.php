<?php
require_once 'engine/modules/Tarakaning/PageControllers/InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsSearch.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsFacade.php';

	class SearchPageResults extends InfoBasePage
	{
		private $_arPks;
		private $getData;
		private $_searcher;
		private $_projectsOperation;
		
		protected function onInit()
		{
			parent::onInit();
			$this->getData = $this->request->getParams();
			
			if (isset($this->getData["by_proj"]) && $this->getData["by_proj"]!='')
			{
				$this->_projectsOperation=new ProjectsController();
				$this->_searcher = new ProjectSearch(self::getGlobalEncoding());
				
				$projectsFacadeOperation=new ProjectsFacade(
					$this->_projectsOperation, 
					$this->_searcher, 
					$this->_controller->auth
				);

				try 
				{
					$this->_arPks=$projectsFacadeOperation->searchProject($this->getData['by_proj']);
				} 
				catch (Exception $exception) 
				{
					$error = array("error" => $exception);
					$this->_controller->error->addError("searchError",$error);
				}
			}
		}
		
		protected function doAssign()
		{
			parent::doAssign();
			$this->_smarty->assign("SEARCH_QUERY", htmlspecialchars($this->getData["by_proj"]));
			$this->_smarty->assign("AR_SEARCH_ITEM", $this->_arPks);
			$searchError = $this->_controller->error->getErrorByName("searchError");
			$this->_smarty->assign("COUNT",count($this->_arPks));
			if ($searchError!=null)
			{
				$exception=$searchError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
			}
		}
	}
?>