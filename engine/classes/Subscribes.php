<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
require_once 'engine/classes/ProjectsController.php';	
/**
 * Класс управления подписками на проект.
 * @author timur 28.01.2011
 *
 */
	class Subscribes extends MySQLConnector
		{
/*
 *  1) получить список проектов, в которых участвует пользователь (для меня минимум) (из таблицы UsersInProjects)
	2) прервать участие в данном проекте (удаление записи из таблицы UsersInProjects)
	3) подать заявку на проект (запись в таблицу SubscribesRequest)
 */
			
			/**
			 * Подача заявки на проект.
			 * @param int $userID - id пользователя, подавшего заявку.
			 * @param int $projectID - id проекта, на который пользователь подал заявку.
			 * @return bool - результат подачи заявки.
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
			 * Проверяется есть ли нерассмотренная заявка от данного пользователя на данный проект.
			 * @param int $userID - id пользователя, подавшего заявку.
			 * @param int $projectID - id проекта, на который пользователь подал заявку.
			 * @return bool - результат проверки.
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
					throw new Exception("Проект не существует.", 101);
				}
			}

			/**
			 * Получение списка проектов, на которые подписан пользователь.
			 * @param int $userID - id пользователя, подавшего заявку.
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
			 * Удаление данного пользователя из списка участников проекта.
			 * @param int $userID - id пользователя.
			 * @param int $projectID - id проекта, из которого пользователь удаляется.
			 */
			public function removeSubscribe($userID, $projectID)
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;//если проект не существует, то вернет ошибку.
				if ($this->isRequestExists($userID, $projectID))
				{
					return $this->_sql->query("DELETE FROM `UsersInProjects` WHERE `OwnerID` = '$userID' AND `ProjectID` = '$projectID' LIMIT 1");
				}
				else 
				{
					throw new Exception("Вы не являетесь участником проекта.", 501);
				}
			}
		}
?>