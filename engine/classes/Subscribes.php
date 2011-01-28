<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
	
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
				$state = $this->isSubscribed($userID, $projectID);
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