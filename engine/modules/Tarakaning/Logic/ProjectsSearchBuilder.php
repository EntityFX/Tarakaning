<?php
	require_once 'engine/system/search/SearchBuilder.php';
	
	class ProjectsSearchBuilder extends SearchBuilder
	{
		public function buildFields()
		{
			$fields=new SearchFields();
			$fields["ProjectID"]=new SearchFieldTypeENUM(SearchFieldTypeENUM::UNINDEXED);
			$fields["Name"]=new SearchFieldTypeENUM(SearchFieldTypeENUM::TEXT);
			$fields["Description"]=new SearchFieldTypeENUM(SearchFieldTypeENUM::TEXT);
			$fields["OwnerID"]=new SearchFieldTypeENUM(SearchFieldTypeENUM::UNSTORED);
			$this->_searchHelper->setFields($fields);
		}
		
		public function buildPath()
		{
			$this->_searchHelper->setPath('engine/indexes/projects');
		}
	}