<?php

Loader::LoadModel('ProjectsModel');   

/**
* Класс подтверждения/отклонения на подписку.
* @author timur 28.01.2011
*
*/
class RequestsController extends DBConnector
{
	const TABLE_USER_IN_PROJECT = 'USER_IN_PROJ';
	
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
		$p = new ProjectsModel();
		if($p->isProjectExists($projectID))
		{
			$requestID = (int)$requestID;
			$ownerID = (int)$ownerID;
			if (ProjectsController::isOwner($ownerID, $projectID))
			{
				if($this->isSubscribed($userID, $projectID))
				{
					throw new Exception("Пользователь уже участвует в проекте.",601);
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
				throw new Exception("Не Вы являетесь Создателем проекта.",102);  
			}
		}
		else 
		{
			throw new Exception("Проект не существует.",101);
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
		$this->_sql->selAllWhere(self::TABLE_USER_IN_PROJECT, "PROJ_ID = '$projectID' AND USER_ID='$userID'");
		$records = $this->_sql->getTable();
		return $records == null ? false : true;
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
		if (ProjectsController::isProjectExists($projectID))
		{
			$requestID = (int)$requestID;
			$ownerID = (int)$ownerID;
			
			if (ProjectsController::isOwner($ownerID, $projectID))
			{
				if($this->isSubscribed($userID, $projectID))
				{
					throw new Exception("Пользователь уже участвует в проекте.",601);
				}
				else 
				{
					$this->_sql->query("DELETE FROM `SubscribesRequest` WHERE `ID` = '$requestID' LIMIT 1");
					return TRUE;
				}
			}
			else 
			{
				throw new Exception("Не Вы являетесь Создателем проекта.",102);  
			}
		}
		else 
		{
			throw new Exception("Проект не существует.",101);
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
		if (ProjectsController::isProjectExists($projectID))
		{
			if (ProjectsController::isOwner($ownerID, $projectID))
			{	
				$startIndex = (int)$startIndex;
				$maxCount = (int)$maxCount;
				$res = $this->_sql->query("SELECT * FROM `SubscribesRequest` WHERE `ProjectID` = '$projectID' LIMIT $startIndex, $maxCount");
				$ret = $this->_sql->GetRows($res);
				return $ret;
			}
			else 
			{
				throw new Exception("Не Вы являетесь Создателем проекта.",102); 
			}
		}
		else 
		{
			throw new Exception("Проект не существует.",101);
		}
	}
}
?>