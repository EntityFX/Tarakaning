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
			
			/**
			 * Подтверждение запроса на подписку.
			 * @param int $requestID - id запроса.
			 * @param int $userID - id пользователя, пославшего запрос.
			 * @param int $projectID - id проекта.
			 * @param int $ownerID - id автора проекта.
			 */
			public function acceptRequest($requestID, $userID, $projectID, $ownerID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$requestID = (int)$requestID;
				$ownerID = (int)$ownerID;
				$p = new ProjectsController();
				if ($p->isOwner($ownerID, $projectID))
				{
					if($this->isSubscribed($userID, $projectID))
					{
						return FALSE;
					}
					else 
					{
						$this->_sql->query("INSERT INTO `UsersInProjects` ( `RecordID` , `ProjectID` , `UserID` )
						VALUES ('', '$projectID', '$userID');");
						$this->_sql->query("DELETE FROM `SubscribesRequest` WHERE `ID` = '$requestID' LIMIT 1");
						return TRUE;
					}
				}
				else 
				{
					return FALSE;
				}
			}
			
			/**
			 * Проверяется подписан ли данный пользователь в данном проекте.
			 * @param int $userID - id пользователя.
			 * @param int $projectID - id проекта.
			 */
			public function isSubscribed($userID, $projectID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$res = $this->_sql->query("SELECT * FROM `UsersInProjects` WHERE `ProjectID` = '$projectID' AND `UserID`='$userID'");
				$tmp = $this->_sql->fetchArr($res);
				if ($tmp == null) 
				{
					return FALSE;
				}
				else 
				{
					return TRUE;
				}
			}
			
			/**
			 * Отклонение заявки.
			 * @param int $requestID - id запроса.
			 * @param int $userID - id пользователя, пославшего запрос.
			 * @param int $projectID - id проекта.
			 * @param int $ownerID - id автора проекта.
			 */
			public function declineRequest($requestID, $userID, $projectID, $ownerID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$requestID = (int)$requestID;
				$ownerID = (int)$ownerID;
				$p = new ProjectsController();
				if ($p->isOwner($ownerID, $projectID))
				{
					if($this->isSubscribed($userID, $projectID))
					{
						return FALSE;
					}
					else 
					{
						$this->_sql->query("DELETE FROM `SubscribesRequest` WHERE `ID` = '$requestID' LIMIT 1");
						return TRUE;
					}
				}
				else 
				{
					return FALSE;
				}
			}
			
			/**
			 * Получение списка заявок для данного проекта.
			 * @param unknown_type $userID
			 * @param unknown_type $projectID
			 * @param unknown_type $startIndex
			 * @param unknown_type $maxCount
			 */
			public function getRequests($userID, $projectID, $startIndex=0, $maxCount=20) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if ($p->isOwner($ownerID, $projectID))
				{	
					$startIndex = (int)$startIndex;
					$maxCount = (int)$maxCount;
					$res = $this->_sql->query("SELECT * FROM `SubscribesRequest` WHERE `ProjectID` = '$projectID' LIMIT $startIndex, $maxCount");
					$ret = $this->_sql->GetRows($res);
					return $ret;
				}
				else 
				{
					return NULL;
				}
			}
		}
?>