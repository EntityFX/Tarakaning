<?php
require_once '../libs/mysql/MySQLConnector.php';
//die("sdfasd");	

	class ProjectsController extends MySQLConnector
	{
		/*����� ���������� ��������� - ProjectsController: +

		���������� ������� +
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
			$description = htmlspecialchars($description);die("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`)
			VALUES ('', '$projectName', '$description', '$userId');");
			$r = $this->_sql->query("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`)
			VALUES ('', '$projectName', '$description', '$userId');");
			
			return $r;
		}
	}
?>