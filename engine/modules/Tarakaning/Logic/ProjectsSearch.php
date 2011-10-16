<?php
require_once 'engine/system/search/SearchFactory.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

class ProjectSearch extends SearchFactory
{
	public function __construct() 
	{
		parent::__construct();
		$this->_arIndexFields = array("ProjectID","Name","Description","OwnerID");
		$this->_arTableIndexName = "project";
		$this->_sIndexIdField = "pk";
	}
	
	public function search($sSearch, $arFields = null) 
	{
		parent::Search($sSearch, $arFields);
		$project = new ProjectsController();
		
		foreach ($this->_arSearchResults as $hit)
		{
			$arPk[] = $project->getProjectById($hit);
		}
		return $arPk;
	}
}

?>