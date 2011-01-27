<?php 
require_once 'engine/libs/mysql/MySQLConnector.php';


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
		 * ���������� ����� �������
		 * @param unknown_type $userID - id ������������, ���������� ������.
		 * @param unknown_type $projectNewName - ����� �������� �������.
		 * @param unknown_type $projectID - id �������, ����������� ��������� ��������.
		 */
	/*	public function editProjectName($userID, $projectNewName, $projectID)
		{
			
		}*/
	}
?>