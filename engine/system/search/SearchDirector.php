<?php
	require_once 'SearchBuilder.php';
	
	class SearchDirector
	{
		private $_searchBuilder;
		
		public function setSearchBuilder(SearchBuilder $searchBuilder)
		{
			$this->_searchBuilder=$searchBuilder;
		}
		
		public function constructSearch()
		{
			$this->_searchBuilder->createSearchHelper();
			$this->_searchBuilder->buildFields();
			$this->_searchBuilder->buildPath();
		}
	}