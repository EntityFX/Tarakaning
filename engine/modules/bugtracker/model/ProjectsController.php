<?php 
require_once 'engine/libs/mysql/DBConnector.php';

	/**
	 * Класс управления проектами.
	 * @author timur 27.01.2011
	 *
	 */

	class ProjectsController extends DBConnector
	{
		/*Класс управления проектами - ProjectsController: +

		добавление проекта +
		удаление проекта -+
		редактирование проекта +
			изменение имени +
			изменение описания + 
		получить список всех проектов +
		получить список по фильтру +
		
		*/
		
		/**
		 * Добавление нового проекта. Создано 27.01.2011.
		 * @param $userId - id пользователя, создавшего проект.
		 * @param $projectName - название проекта.
		 * @param $description - описание проекта.
		 * @return bool результат операции. 
		 * 
		 * @todo 1) проверку существования пользователя.<br />
		 * 2) при добавлении проекта должно происходить добавление в таблицу истории.
		 */
		public function addProject($userID, $projectName, $description)
		{
			$projectName = htmlspecialchars($projectName);
			$description = htmlspecialchars($description);
			$projectName = mysql_escape_string($projectName);
			$description = mysql_escape_string($description);
			$userID = (int)$userID;
			if ($this->isExistThisProjectName($description)) throw new Exception("Проект с таким именем уже существует.", 103); ;
			$r = $this->_sql->query("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`, `CreateDate`)
			VALUES ('', '$projectName', '$description', '$userID', '". date("c")."');");

			return $r;
		}
		
		/**
		 * Проверяет существует ли проект с ананлогичным названием.
		 * @param unknown_type $description
		 */
		public function isExistThisProjectName($description) 
		{
			$res = $this->_sql->query("SELECT * FROM `Projects` WHERE `Name`='$description'");
			$ret = $this->_sql->fetchArr($res);
			if ($ret != null) return TRUE;//throw new Exception("Проект с таким именем уже существует.", 103); 
		}
		/**
		 * Обновление имени проекта. Обновить имя может только создатель проекта. Создано 28.01.2011.
		 * @param int $userID - id пользователя, создавшего проект.
		 * @param string $projectNewName - новое название проекта.
		 * @param int $projectID - id проекта, подлежащего изменению названия.
		 * @return bool - результат выполнения.
		 * 
		 * @todo при изменении названия проекта происходить должно: <br /> 
		 * добавление в таблицу истории проекта (-) <br />
		 * и обновление в таблице проектов (+) <br />
		 */
		public function setProjectName($userID, $projectNewName, $projectID)
		{
			$userID = (int)$userID;
			$projectID = (int)$projectID;
			if ($this->isProjectExists($projectID))
			{
				$projectNewName = htmlspecialchars($projectNewName);
				$projectNewName = mysql_escape_string($projectNewName);
				if ($this->isOwner($userID, $projectID))  
				{
					return $this->_sql->query("UPDATE `Projects` SET `Name` = '$projectNewName'
					WHERE `ProjectID` = '$projectID' AND `OwnerID` = '$userID';");
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
		 * Обновление описания проекта. Обновить описание может только создатель проекта. Создано 28.01.2011.
		 * @param int $userID - id пользователя, создавшего проект.
		 * @param int $projectID - id проекта, подлежащего изменению названия.
		 * @param string $newDescription - новое описание проекта.
		 *  
		 * @todo при изменении описания проекта происходить должно: <br /> 
		 * добавление в таблицу истории проекта (-) <br />
		 * и обновление в таблице проектов (+) <br />
		 */
		public function setProjectDescription($userID, $projectID, $newDescription) 
		{
			$newDescription = htmlspecialchars($newDescription);
			$newDescription = mysql_escape_string($newDescription);
			$userID = (int)$userID;
			$projectID = (int)$projectID;
			if ($this->isProjectExists($projectID))
			{
				if ($this->isOwner($userID, $projectID)) 
				{
					return $this->_sql->query("UPDATE `Projects` SET `Description` = '$newDescription'
					WHERE `ProjectID` = '$projectID' AND `OwnerID` = '$userID'"); 
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
		 * Проверяется является ли пользователь автором проекта.
		 * @param int $userID - id пользователя, создавшего проект.
		 * @param int $projectID - id проекта, подлежащего изменению названия.
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
		 * Удаление проекта. Удалять проект может только создатель. Создано 28.01.2011.
		 * @param int $userID
		 * @param int $projectID
		 * 
		 * @return bool - результат выполнения
		 * 
		 * @todo добавить проверку пользователя - является ли администратором. Тогда можно будет и ему удалять.
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
					throw new Exception("Не Вы являетесь Создателем проекта.",102);  
				}
			}
			else 
			{
				throw new Exception("Проект не существует.",101);
			}
		}
		
		/**
		 * Получение списка всех проектов. Создано 28.01.2011.
		 * @param int $startIndex - индекс с которого нужно показывать результаты поиска.
		 * @param int $maxCount - максимальное количество результатов поиска.
		 */
		public function getProjects($startIndex = 0, $maxCount = 20) 
		{
			$startIndex = (int)$startIndex;
			$maxCount = (int)$maxCount;
			$res = $this->_sql->query("SELECT * FROM `Projects` LIMIT $startIndex, $maxCount");
			$ret = $this->_sql->GetRows($res);
			return $ret;
		}
		
		/**
		 * Получение списка проектов по фильтру.
		 * @param string $sortType - фильтр поиска. "date", "name".
		 * @param bool $ask - по возрастанию или по убыванию.
		 * @param int $startIndex - вывод резултатов с данной позиции.
		 * @param int $maxCount - количество результатов вывода.
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
		 * Проверка существования проекта.
		 * @param int $projectID - id проекта.
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