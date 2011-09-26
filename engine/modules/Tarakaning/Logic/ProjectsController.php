<?php 

	require_once 'engine/modules/Tarakaning/Logic/ProjectFieldsUsersInfoENUM.php';
	require_once 'engine/modules/Tarakaning/Logic/MyProjectsFieldsENUM.php';
	
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
		
		public function __construct()
		{
			parent::__construct();
		}
		
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
			if ($this->isExistThisProjectName($projectName))
			{
				throw new Exception(sprintf("Проект с именем %s уже существует.",$projectName), 103); ;
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
					throw new Exception("Имя проекта не должно быть пустым");
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