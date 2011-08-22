<?php
require_once 'URLException.php';

require_once 'ModuleEditor.php';

class URLEditor extends DBConnector
{
	protected $_urlInfo;

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
		$nodeLink=$nodeLink;
		$this->checkURL($nodeLink);
		if (!$this->checkIfExist($parentID))
		{
			throw new URLException($nodeLink,"Parent with id=".$parentID." is not exsist");
		}
		if ($this->checkLinkIsExsist($nodeLink, $parentID))
		{
			throw new URLException($nodeLink,"Link $nodeLink with parent id ".$parentID." already exsist");
		}
		$moduleEditor=new ModuleEditor();
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
			$resource=$this->_sql->query("SELECT LAST_INSERT_ID() as ID ");
			$res=$this->_sql->GetRows($resource);
			return (int)$res[0]["ID"];
		}
		else
		{
			throw new URLException($nodeLink,"Parent with id=".$parentID." has useParameter IS True");	
		}
	}

	public function getByID($urlId)
	{
		$urlId=(int)$urlId;
		$this->_sql->selAllWhere("URL", "id=$urlId");
		$arr=$this->_sql->getTable();
		return $arr[0];
	}

	public function deleteUrl($id,$deleteChildsRecurs)
	{
		$id=(int)$id;
		if (!$this->checkIfExist($id))
		{
			throw new URLException($nodeLink,"Url with id=".$id." is not exsist");
		}
		if ($this->_urlInfo["pid"]==0)
		{
			throw new URLException($nodeLink, "Can't delete root");	
		}
		if ($deleteChildsRecurs)
		{
			$parentID=(int)$this->_urlInfo["pid"];
			$this->_sql->selAllWhere("URL", "pid=$id");
			$childNodes=$this->_sql->getTable();
			if ($childNodes!=null)
			{
				foreach($childNodes as $child)
				{
					$childID=$child["id"];
					$this->_sql->update("URL", "id=$childID", new ArrayObject(
						array(
							"pid" => $parentID
						)
					));				
				}
			}
			$this->_sql->delete("URL", "id=$id");
		}			
	}
	
	public function updateUrl($id, $link, $title)
	{
		$id=(int)$id;
		if (!$this->checkIfExist($id))
		{
			throw new URLException($link,"Url with id=".$id." is not exsist");
		}
		if ($this->_urlInfo["pid"]!=0)
		{
			$this->checkURL($link);
			try {
				$this->_sql->update("URL", "id=$id", new ArrayObject(array(
					"link" => $link,
					"title" => htmlspecialchars($title)
				)));
			}
			catch (Exception $ex)
			{
				throw new URLException($link, "This url is already exsist");
			}		
		}
		else
		{
			$this->_sql->update("URL", "id=$id", new ArrayObject(array(
				"title" => htmlspecialchars($title)
			)));	
		}		
	}

	protected function checkIfExist($urlId)
	{
		$arr=self::getByID($urlId);
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

	public function checkLinkIsExsist($nodeLink,$pid)
	{
		$pid=(int)$pid;
		$nodeLink=mysql_escape_string($nodeLink);
		$countGroups=$this->_sql->countQuery("URL","pid=$pid AND link='$nodeLink'");
		return (Boolean)$countGroups;
	}
	
	public function getParent()
	{
		$this->_sql->selAllWhere("URL", "pid=0 AND link='/'");
		$arr=$this->_sql->getTable();
		return $arr[0];
	}
	
	private function checkURL($link)
	{
		if (preg_match("/^[a-zA-Z0-9_-]+$/", $link)==0)
		{
			throw new URLException($link,"Url must contain only latin, number, _ and - charakters. Min length is 1 character.");
		}		
	}
}