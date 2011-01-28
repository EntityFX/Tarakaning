<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
	
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
				$state = $this->isSubscribed($userID, $projectID);
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
			public function isSubscribed($userID, $projectID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$res = $this->_sql->query("SELECT * FROM `SubscribesRequest` WHERE `UserID` = '$userID' AND `ProjectID` = '$projectID'");
				return $res == null ? false : true;
			}

			public function getUserSubscribes($userID)
			{
				$userID = (int)$userID;
				$res = $this->_sql->query("SELECT * FROM `UsersInProjects` WHERE `UserID` = $userID");
				while($tmp = $this->_sql->fetchArr($res))
				{
					$ret[] = $tmp;
				}
				return $ret;
			}
			
			public function removeSubscribe($userID, $projectID)
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				return $this->_sql->query("DELETE FROM `UsersInProjects` WHERE `OwnerID` = '$userID' AND `ProjectID` = '$projectID' LIMIT 1");
			}
		}
?>