<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
	/**
	 * ����� �������������/���������� �� ��������.
	 * @author timur 28.01.2011
	 *
	 */
	class SubscribesController extends MySQLConnector
		{
			/*
			 * 1) ����������� ������ (����� �� ������� SubscribesRequest, �������� � ������� UsersInProjects)
			 * 2) ��������� ������ (������� �� ������� SubscribesRequest)
			 * 3) �������� ������ ���� ������ (�������� �� SubscribesRequest)
			 */
			public function acceptRequest($requestID, $userID, $projectID) 
			{
				$this->_sql->query("INSERT INTO `UsersInProjects` ( `RecordID` , `ProjectID` , `UserID` )
				VALUES ('', '$projectID', '$userID');");
				$this->_sql->query("DELETE FROM `SubscribesRequest` WHERE `ID` = '$requestID' LIMIT 1");
			}
			
			public function declineRequest() 
			{
				;
			}
			
			public function getRequests() 
			{
				;
			}
		}
?>