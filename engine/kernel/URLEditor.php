<?php
require_once 'URLException.php';

require_once 'ModuleEditor.php';

class URLEditor extends DBConnector
{
	private $_urlInfo;

	public function __construct()
	{
		parent::__construct();
	}

	public function addUrl($nodeLink,$parentID,$moduleID,$title="",$titleTag="",$useParameters=false)
	{
		$parentID=(int)$parentID;
		$moduleID=(int)$moduleID;
		$title=htmlspecialchars($title);
		$titleTag=htmlspecialchars($titleTag);
		$nodeLink=urlencode($nodeLink);
		if (!$this->checkIfExist($parentID))
		{
			throw new URLException($nodeLink,"Parent with id=".$parentID." is not exsist");
		}
		if ($this->checkLinkIsExsist($nodeLink, $parentID))
		{
			throw new URLException($nodeLink,"Link $nodeLink with parent id ".$parentID." already exsist");
		}
		$moduleEditor=new ModuleEditor();
		var_dump($moduleEditor->checkIfExsist($moduleID));
		if (!$moduleEditor->checkIfExsist($moduleID))
		{
			throw new URLException($nodeLink,"Module for this url is not exsist");
		}
		if ($this->_urlInfo['use_parameters']==0)
		{
			$this->_sql->insert("URL",
				new ArrayObject(array(
					0,
					$nodeLink,
					$title,
					$titleTag,
					$moduleID,
					0,
					$parentID,
					$useParameters
				)),
				new ArrayObject(array(
					"id",
					"link",
					"title",
					"title_tag",
					"module",
					"position",
					"pid",
					"use_parameters"
				))
			);
		}
		else
		{
			throw new URLException($nodeLink,"Parent with id=".$parentID." has useParameter IS True");	
		}
	}

	public function getByID($urlId)
	{
		$id=(int)$urlId;
		$this->_sql->selAllWhere("URL", "id=$urlId");
		$arr=$this->_sql->getTable();
		return $arr[0];
	}

	public function deleteUrl($id,$deleteChildsRecurs)
	{
			
	}

	private function checkIfExist($urlId)
	{
		$arr=$this->getByID($urlId);
		if ($arr==null)
		{
			return false;
		}
		else
		{
			$this->_urlInfo=$arr;
			return true;
		}
	}

	private function checkLinkIsExsist($nodeLink,$pid)
	{
		$pid=(int)$pid;
		$nodeLink=mysql_escape_string($nodeLink);
		$countGroups=$this->_sql->countQuery("URL","pid=$pid AND link='$nodeLink'");
		return (Boolean)$countGroups;
	}
}