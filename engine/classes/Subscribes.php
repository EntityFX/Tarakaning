<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
require_once 'engine/classes/ProjectsController.php';	
/**
 * ����� ���������� ���������� �� ������.
 * @author timur 28.01.2011
 *
 */
	class Subscribes extends MySQLConnector
		{
/*
 *  1) �������� ������ ��������, � ������� ��������� ������������ (��� ���� �������) (�� ������� UsersInProjects)
	2) �������� ������� � ������ ������� (�������� ������ �� ������� UsersInProjects)
	3) ������ ������ �� ������ (������ � ������� SubscribesRequest)
 */
			
			/**
			 * ������ ������ �� ������.
			 * @param int $userID - id ������������, ��������� ������.
			 * @param int $projectID - id �������, �� ������� ������������ ����� ������.
			 * @return bool - ��������� ������ ������.
			 */
			public function sendRequest($userID, $projectID)
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				
				$state = $this->isRequestExists($userID, $projectID);
				$state ? $r = TRUE : 
				$r = $this->_sql->query("INSERT INTO `SubscribesRequest` ( `ID` , `UserID` , `ProjectID` )
				VALUES ('', '$userID', '$projectID');");
				return $r;
				
			}
			
			/**
			 * ����������� ���� �� ��������������� ������ �� ������� ������������ �� ������ ������.
			 * @param int $userID - id ������������, ��������� ������.
			 * @param int $projectID - id �������, �� ������� ������������ ����� ������.
			 * @return bool - ��������� ��������.
			 */
			public function isRequestExists($userID, $projectID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{
					$res = $this->_sql->query("SELECT * FROM `SubscribesRequest` WHERE `UserID` = '$userID' AND `ProjectID` = '$projectID'");
					return $res == null ? false : true;
				}
				else 
				{
					throw new Exception("������ �� ����������.", 101);
				}
			}

			/**
			 * ��������� ������ ��������, �� ������� �������� ������������.
			 * @param int $userID - id ������������, ��������� ������.
			 */
			public function getUserSubscribes($userID, $startIndex = 0, $maxCount = 30)
			{
				$userID = (int)$userID;
				$startIndex = (int)$startIndex;
				$maxCount = (int)$maxCount;
				$res = $this->_sql->query("SELECT * FROM `UsersInProjects` WHERE `UserID` = $userID LIMIT $startIndex, $maxCount");
				$ret = $this->_sql->GetRows($res);
				return $ret;
			}
			
			/**
			 * �������� ������� ������������ �� ������ ���������� �������.
			 * @param int $userID - id ������������.
			 * @param int $projectID - id �������, �� �������� ������������ ���������.
			 */
			public function removeSubscribe($userID, $projectID)
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;//���� ������ �� ����������, �� ������ ������.
				if ($this->isRequestExists($userID, $projectID))
				{
					return $this->_sql->query("DELETE FROM `UsersInProjects` WHERE `OwnerID` = '$userID' AND `ProjectID` = '$projectID' LIMIT 1");
				}
				else 
				{
					throw new Exception("�� �� ��������� ���������� �������.", 501);
				}
			}
		}
?>