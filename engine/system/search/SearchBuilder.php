<?php
	require_once 'Zend/Search/Lucene.php';
	
	require_once 'SearchHelper.php';

	abstract class SearchBuilder
	{
		protected $_searchHelper;
		
		public function createSearchHelper()
		{
			$this->_searchHelper=new SearchHelper();
		}
		
		public function getSearchHelper()
		{
			if ($this->_searchHelper!=null)
			{
				$fields=$this->_searchHelper->getFields();
				if ($fields==null || $fields->count()==0)
				{
					throw new Exception("Set search fields first");
				}
				if ($this->_searchHelper->getIndexPath()==null)
				{
					throw new Exception("Set search index path first");
				}
				return $this->_searchHelper;
			}
			else
			{
				throw new Exception("Create SearchHelper first");
			}
		}
		
		abstract public function buildFields();
		
		abstract public function buildPath();
	}