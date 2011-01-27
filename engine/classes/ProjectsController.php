<?php 
require_once 'engine/libs/mysql/MySQLConnector.php';


	class ProjectsController extends MySQLConnector
	{
		/*Класс управления проектами - ProjectsController: +

		добавление проекта +
		удаление проекта
		редактирование проекта
			изменение имени
			изменение описания
		получить список всех проектов
		получить список по фильтру
		
		*/
		
		/**
		 * Добавление нового проекта
		 * @param $userId - id пользователя, создавшего проект.
		 * @param $projectName - название проекта.
		 * @param $description - описание проекта.
		 * @return bool результат операции.
		 * 
		 * @todo проверку существования пользователя.
		 */
		public function addProject($userID, $projectName, $description)
		{
			$projectName = htmlspecialchars($projectName);
			$description = htmlspecialchars($description);
			
			var_dump($this->_sql);
			
			$r = $this->_sql->query("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`)
			VALUES ('', '$projectName', '$description', '$userId');");
			
			die("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`)
			VALUES ('', '$projectName', '$description', '$userID');");
			return $r;
		}
		
		/**
		 * Обновление имени проекта
		 * @param unknown_type $userID - id пользователя, создавшего проект.
		 * @param unknown_type $projectNewName - новое название проекта.
		 * @param unknown_type $projectID - id проекта, подлежащего изменению названия.
		 */
	/*	public function editProjectName($userID, $projectNewName, $projectID)
		{
			
		}*/
	}
?>