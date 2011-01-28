<?php 
require_once 'engine/libs/mysql/MySQLConnector.php';

	/**
	 * ����� ���������� ���������.
	 * @author timur 27.01.2011
	 *
	 */

	class ProjectsController extends MySQLConnector
	{
		/*����� ���������� ��������� - ProjectsController: +

		���������� ������� +
		�������� ������� -+
		�������������� ������� +
			��������� ����� +
			��������� �������� + 
		�������� ������ ���� �������� +
		�������� ������ �� �������
		
		*/
		
		/**
		 * ���������� ������ �������. ������� 27.01.2011.
		 * @param $userId - id ������������, ���������� ������.
		 * @param $projectName - �������� �������.
		 * @param $description - �������� �������.
		 * @return bool ��������� ��������.
		 * 
		 * @todo 1) �������� ������������� ������������.<br />
		 * 2) ��� ���������� ������� ������ ����������� ���������� � ������� �������.
		 */
		public function addProject($userID, $projectName, $description)
		{
			$projectName = mysql_escape_string($projectName);
			$description = mysql_escape_string($description);
			$userID = (int)$userID;
			$r = $this->_sql->query("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`, `CreateDate`)
			VALUES ('', '$projectName', '$description', '$userID', '". date("c")."');");

			return $r;
		}
		
		/**
		 * ���������� ����� �������. �������� ��� ����� ������ ��������� �������. ������� 28.01.2011.
		 * @param int $userID - id ������������, ���������� ������.
		 * @param string $projectNewName - ����� �������� �������.
		 * @param int $projectID - id �������, ����������� ��������� ��������.
		 * @return bool - ��������� ����������.
		 * 
		 * @todo ��� ��������� �������� ������� ����������� ������: <br /> 
		 * ���������� � ������� ������� ������� (-) <br />
		 * � ���������� � ������� �������� (+) <br />
		 */
		public function setProjectName($userID, $projectNewName, $projectID)
		{
			$userID = (int)$userID;
			$projectID = (int)$projectID;
			$projectNewName = mysql_escape_string($projectNewName);
			if ($this->isOwner($userID, $projectID))  
			{
				return $this->_sql->query("UPDATE `Projects` SET `Name` = '$projectNewName'
				WHERE `ProjectID` = '$projectID' AND `OwnerID` = '$userID';");
			}
			else 
			{
				return FALSE;  
			}
		}
		
		/**
		 * ���������� �������� �������. �������� �������� ����� ������ ��������� �������. ������� 28.01.2011.
		 * @param int $userID - id ������������, ���������� ������.
		 * @param int $projectID - id �������, ����������� ��������� ��������.
		 * @param string $newDescription - ����� �������� �������.
		 *  
		 * @todo ��� ��������� �������� ������� ����������� ������: <br /> 
		 * ���������� � ������� ������� ������� (-) <br />
		 * � ���������� � ������� �������� (+) <br />
		 */
		public function setProjectDescription($userID, $projectID, $newDescription) 
		{
			$newDescription = mysql_escape_string($newDescription);
			$userID = (int)$userID;
			$projectID = (int)$projectID;
			if ($this->isOwner($userID, $projectID)) 
			{
				return $this->_sql->query("UPDATE `Projects` SET `Description` = '$newDescription'
				WHERE `ProjectID` = '$projectID' AND `OwnerID` = '$userID'"); 
			}
			else 
			{
				return FALSE; 
			}
		}
		
		public function isOwner($userID, $projectID) 
		{
			$userID = (int)$userID;
			$projectID = (int)$projectID;
			$res = $this->_sql->query("SELECT * FROM `Projects` WHERE `ProjectID` = '$projectID'");
			$tmp = $this->_sql->fetchArr($res);
			return ($userID != $tmp["OwnerID"]) ? FALSE : TRUE; 
			
		}
		/**
		 * �������� �������. ������� ������ ����� ������ ���������. ������� 28.01.2011.
		 * @param int $userID
		 * @param int $projectID
		 * 
		 * @return bool - ��������� ����������
		 * 
		 * @todo �������� �������� ������������ - �������� �� ���������������. ����� ����� ����� � ��� �������.
		 */
		public function deleteProject($userID, $projectID) 
		{
			$userID = (int)$userID;
			$projectID = (int)$projectID;
			return  $this->_sql->query("DELETE FROM `Projects` WHERE `ProjectID` = '$projectID' AND `OwnerID` = '$userID' LIMIT 1");
		}
		
		/**
		 * ��������� ������ ���� ��������. ������� 28.01.2011.
		 * @param int $startIndex - ������ � �������� ����� ���������� ���������� ������.
		 * @param int $maxCount - ������������ ���������� ����������� ������.
		 */
		public function getProjects($startIndex = 0, $maxCount = 20) 
		{
			$startIndex = (int)$startIndex;
			$maxCount = (int)$maxCount;
			$res = $this->_sql->query("SELECT * FROM `Projects` LIMIT $startIndex, $maxCount");
			while ($temp = $this->_sql->fetchArr($res)) 
			{
				$temp["Name"]=htmlspecialchars($temp["Name"]);
				$temp["Description"]=htmlspecialchars($temp["Description"]);
				$ret[] = $temp;
			}
			return $ret;
		}
		
		/**
		 * ��������� ������ �������� �� �������.
		 * @param string $sortType - ������ ������. "date", "name".
		 * @param bool $ask - �� ����������� ��� �� ��������.
		 * @param int $startIndex - ����� ���������� � ������ �������.
		 * @param int $maxCount - ���������� ����������� ������.
		 */
		public function getSortedProjects($sortType = "date", $ask = true, $startIndex = 0, $maxCount = 20) 
		{
			$startIndex = (int)$startIndex;
			$maxCount = (int)$maxCount;
			switch ($sortType) 
			{
				case "date":
					$sorting = "CreateDate";
					$type = $ask ? "ASC":"DESC";
					break;
				
				case "name":
					$sorting = "Name";
					$type = $ask ? "ASC":"DESC";
					break;
				
				default:
					return FALSE;
				break;
			}
			$q = "SELECT * FROM `Projects` ORDER BY `$sorting` $type LIMIT $startIndex, $maxCount";
			//die($q);
			$res = $this->_sql->query($q);
			while ($temp = $this->_sql->fetchArr($res)) 
			{
				$temp["Name"]=htmlspecialchars($temp["Name"]);
				$temp["Description"]=htmlspecialchars($temp["Description"]);
				$ret[] = $temp;
			}
			return $ret;
		}
		
		
		public function isProjectExists($projectID)
		{
			$projectID = (int)$projectID;
			$res = $this->_sql->query("SELECT * FROM `Projects` WHERE `ProjectID` = '$projectID'");
			$tmp = $this->_sql->fetchArr($res);
			return $tmp == null ? FALSE : TRUE;
		}
	}
?>