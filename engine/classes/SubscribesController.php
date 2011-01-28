<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
	/**
	 * Класс подтверждения/отклонения на подписку.
	 * @author timur 28.01.2011
	 *
	 */
	class SubscribesController extends MySQLConnector
		{
			/*
			 * 1) подтвердить заявку (взять из таблицы SubscribesRequest, записать в таблицу UsersInProjects)
			 * 2) отклонить заявку (удалить из таблицы SubscribesRequest)
			 * 3) показать список всех заявок (получить из SubscribesRequest)
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