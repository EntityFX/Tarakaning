<?php
require_once 'engine/system/search/SearchFactory.php';
require_once 'engine/modules/Tarakaning/Logic/ProjectsController.php';

class ProjectSearch extends SearchFactory
{
	protected $_arIndexFields = array("ProjectID","Name","Description","OwnerID");
	protected $_arTableIndexName = "project";
	protected $_sIndexIdField = "pk";
	
	public function Search($sSearch, $arFields = null) 
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