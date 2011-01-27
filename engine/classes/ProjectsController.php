<?php
require_once '../libs/mysql/MySQLConnector.php';
//die("sdfasd");	

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
		public function addProject($userId, $projectName, $description)
		{
			$projectName = htmlspecialchars($projectName);
			$description = htmlspecialchars($description);
			$r = $this->_sql->query("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`)
			VALUES ('', '$projectName', '$description', '$userId');");
			die("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`)
			VALUES ('', '$projectName', '$description', '$userId');");
			return $r;
		}
		
		public function editProjectName()
		{
			
		}
	}
?>