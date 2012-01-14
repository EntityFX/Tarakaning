<?php

Loader::LoadModel('ProjectsSearchBuilder');     
Loader::LoadModel('ProjectsModel');   

Loader::LoadSystem('search','SearchDirector');

class ProjectSearch
{
	private $_director;
	private $_builder;
	private $_helper;
	private $_encoding="UTF-8";
	
	public function __construct($encoding)
	{
		$this->_builder=new ProjectsSearchBuilder();
		$this->_director=new SearchDirector();
		$this->_director->setSearchBuilder($this->_builder);
		$this->_director->constructSearch();
		$this->_helper=$this->_builder->getSearchHelper();
		$this->_encoding=$encoding;
	}
	
	public function addProjectToIndex(&$projectRecord) 
	{
		$this->_helper->addToIndex(				
			new ArrayObject(array(
				"ProjectID" => $projectRecord['ProjectID'],
				"Name" => mb_convert_encoding($projectRecord['Name'],"UTF8",$this->_encoding),
				"Description" => mb_convert_encoding($projectRecord['Description'],"UTF8",$this->_encoding),
				"OwnerID" => $projectRecord['OwnerID']				
			))
		);
	}
	
	public function updateProjectInIndex(&$projectRecord)
	{
		
	}
	
	public function deleteFromIndex($projectID)
	{
		$this->_helper->deleteFromIndex();
	}
	
	public function addAllProjectsToIndex($projectsList)
	{
		foreach ($projectsList as $project) 
		{
			$this->addProjectToIndex($project);
		}
	}
	
	public function searchProjects($query)
	{
		$projectController=new ProjectsModel();
		$queryString=mb_convert_encoding($query,"UTF8",$this->_encoding);
		$hits=$this->_helper->search($queryString,'Name');
		foreach ($hits as $hit)
		{
			$projectList[]=$hit->ProjectID;
		}
		return $projectList;
	}
}

?>