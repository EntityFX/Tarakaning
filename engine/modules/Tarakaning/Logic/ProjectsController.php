<?php 

	require_once 'engine/modules/Tarakaning/Logic/ProjectFieldsUsersInfoENUM.php';
	require_once 'engine/modules/Tarakaning/Logic/MyProjectsFieldsENUM.php';
	
	/**
	 * ����� ���������� ���������.
	 * @author timur 27.01.2011
	 *
	 */

	class ProjectsController extends DBConnector
	{
		/*����� ���������� ��������� - ProjectsController: +

		���������� ������� +
		�������� ������� -+
		�������������� ������� +
			��������� ����� +
			��������� �������� + 
		�������� ������ ���� �������� +
		�������� ������ �� ������� +
		
		*/
		
		public function __construct()
		{
			parent::__construct();
		}
		
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
			$projectName = htmlspecialchars($projectName);
			$description = htmlspecialchars($description);
			$projectName = mysql_escape_string($projectName);
			$description = mysql_escape_string($description);
			$userID = (int)$userID;
			if ($this->isExistThisProjectName($projectName))
			{
				throw new Exception(sprintf("������ � ������ %s ��� ����������.",$projectName), 103); ;
			}
			else
			{
				$this->_sql->insert(
					"Projects", 
					new ArrayObject(array(
						$projectName,
						$description,
						$userID,
						date("c")
					)),
					new ArrayObject(array(
						'Name',
						'Description',
						'OwnerID',
						'CreateDate'
					))
				);
			}
		}
		
		/**
		 * ��������� ���������� �� ������ � ������������ ���������.
		 * @param unknown_type $description
		 */
		public function isExistThisProjectName($description) 
		{
			$res = $this->_sql->query("SELECT * FROM `Projects` WHERE `Name`='$description'");
			$ret = $this->_sql->fetchArr($res);
			if ($ret != null) return TRUE;//throw new Exception("������ � ����� ������ ��� ����������.", 103); 
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
		public function setProjectName($projectID,$userID, $projectNewName, $newDescription)
		{
			$userID = (int)$userID;
			$projectID = (int)$projectID;
			if ($this->isProjectExists($projectID))
			{
				$projectNewName = htmlspecialchars($projectNewName);
				$newDescription = htmlspecialchars($newDescription);
				if ($projectNewName=='')
				{
					throw new Exception("��� ������� �� ������ ���� ������");
				}
				if ($this->isOwner($userID, $projectID))  
				{
					
					$this->_sql->update(
						'Projects', 
						"ProjectID = $projectID AND OwnerID = $userID",
					 	new ArrayObject(array(
					 		'Name' => $projectNewName,
					 		'Description' => $newDescription
					 	))
					);
				}
				else 
				{
					throw new Exception("�� �� ��������� ���������� �������.",102);  
				}
			}
			else 
			{
				throw new Exception("������ �� ����������.",101);
			}
		}
		
		/**
		 * ����������� �������� �� ������������ ������� �������.
		 * @param int $userID - id ������������, ���������� ������.
		 * @param int $projectID - id �������, ����������� ��������� ��������.
		 */
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
			if ($this->isProjectExists($projectID))
			{
				if ($this->isOwner($userID, $projectID)) 
				{
					return  $this->_sql->query("DELETE FROM `Projects` WHERE `ProjectID` = '$projectID' AND `OwnerID` = '$userID' LIMIT 1");
				}
				else 
				{
					throw new Exception("�� �� ��������� ���������� �������.",102);  
				}
			}
			else 
			{
				throw new Exception("������ �� ����������.",101);
			}
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
			$ret = $this->_sql->GetRows($res);
			return $ret;
		}
		
		public function getProjectById($projectID)
		{
			$projectID=(int)$projectID;
			$this->_sql->selAllWhere("ProjectsWithUserName","ProjectID=$projectID");
			$data=$this->_sql->getTable();
			return $data[0];
		}
		
		public function getUserProjectsInfo($userId,MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction,$page=1,$size=10)
		{
			$userId=(int)$userId;
			$this->_sql->setLimit($page, $size);
			$this->_sql->setOrder($orderField, $direction);
			$resource=$this->_sql->selAllWhere("projectanderrorsview", "OwnerID=$userId");
			$this->_sql->clearLimit();
			$this->_sql->clearOrder();
			return $this->_sql->getTable();
		}
		
		public function countUserProjectsInfo($userId)
		{
			$userId=(int)$userId;
			return $this->_sql->countQuery("projectanderrorsview", "OwnerID=$userId");
		}
		
		public function getMemberProjects($userId,MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction,$page=1,$size=10)
		{
			$userId=(int)$userId;
			$this->_sql->setLimit($page, $size);
			$this->_sql->setOrder($orderField, $direction);
			$resource=$this->_sql->selAllWhere("projectsinfowithoutmeview", "UserID=$userId");
			$this->_sql->clearLimit();
			$this->_sql->clearOrder();
			return $this->_sql->getTable();
		}
		
		public function countMemberProjects($userId)
		{
			$userId=(int)$userId;
			return $this->_sql->countQuery("projectsinfowithoutmeview", "UserID=$userId");
		}
		
		public function getProjectUsersInfo($projectID)
		{
			$projectID=(int)$projectID;
			$this->_sql->selAllWhere('projectusersinfo', "ProjectID=$projectID");
			return $this->_sql->getTable();
		}
		
		public function getProjectUsersInfoCount($projectID)
		{
			$projectID=(int)$projectID;
			return $this->_sql->countQuery('projectusersinfo', "ProjectID=$projectID");
		}
		
		public function getProjectsUsersInfoPagOrd($projectID, ProjectFieldsUsersInfoENUM $orderField, MySQLOrderEnum $direction,$page=1,$size=15)
		{
			$this->_sql->setLimit($from, $size);
			$this->_sql->setOrder($orderField, $direction);
			$res=$this->getProjectUsersInfo($projectID);
			$this->_sql->clearLimit();
			$this->_sql->clearOrder();
			return $res;
		}
		
		public function getUserProjects($userId)
		{
			$userId=(int)$userId;
			$this->_sql->selFieldsWhere("Projects", "OwnerId=$userId",'ProjectID','Name');
			return $this->_sql->getTable();
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
			$ret = $this->_sql->GetRows($res);
			return $ret;
		}
		
		/** 
		 * �������� ������������� �������.
		 * @param int $projectID - id �������.
		 */
		public function isProjectExists($projectID)
		{
			$projectID = (int)$projectID;
			$res = $this->_sql->query("SELECT * FROM `Projects` WHERE `ProjectID` = '$projectID'");
			$tmp = $this->_sql->fetchArr($res);
			return $tmp == null ? FALSE : TRUE;
		}
		
		public function getOwnerID($projectID) 
		{
			$projectID = (int)$projectID;
			
			$res = $this->_sql->query("SELECT * FROM `Projects` WHERE `ProjectID` = '$projectID'");
			$tmp = $this->_sql->fetchArr($res);
			return (int)$tmp["OwnerID"];
		}
	}
?>