<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
	class ProjectsController extends MySQLConnector
	{
		/*����� ���������� ��������� - ProjectsController: +

		���������� �������
		�������� �������
		�������������� �������
		��������� �����
		��������� ��������
		�������� ������ ���� ��������
		�������� ������ �� �������
		
		*/
		
		/**
		 * ���������� ������ �������
		 * @param $userId - id ������������, ���������� ������.
		 * @param $projectName - �������� �������.
		 * @param $description - �������� �������.
		 * @return bool ��������� ��������.
		 * 
		 * @todo �������� ������������� ������������.
		 */
		public function addProject($userId, $projectName, $description)
		{
			$projectName = htmlspecialchars($projectName);
			$description = htmlspecialchars($description);
			return $this->_sql->query("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID` )
			VALUES ('', '$projectName', '$description', '$userId');");
		}
	}
?>