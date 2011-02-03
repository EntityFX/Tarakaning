<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
require_once 'engine/classes/ProjectsController.php';	
require_once 'engine/classes/RequestsController.php';

	class ReportHistory extends MySQLConnector
	{
		/*
		 *Класс управления историей ошибки - ReportHistory:
		 *
		 *добавление в историю (+срабатывает автоматически при всех изменениях ошибки)
		 *удаление истории
		 *просмотр истории 
		 *
		 */

		/*
		 * $reportID = (int)$reportID;
		 * $userID = (int)$userID;
		 * $projectID = (int)$projectID;
		 * $commentID = (int)$commentID;
		 * $newStatus = (int)$newStatus;
		 * $reportID = (int)$reportID;
		 * $description
		 */
		
		/**
		 * Добавление элемента в историю.
		 * @param $userID
		 * @param $projectID
		 * @param $reportID
		 * @param $newStatus
		 * @param $description
		 */
		public function addHistory($userID,$projectID,$reportID, $newStatus, $description) 
		{
			$projectID = (int)$projectID;
			$p = new ProjectsController();
			if($p->isProjectExists($projectID))
			{	
				$r = new RequestsController();
				$userID = (int)$userID;
				if ($r->isSubscribed($userID, $projectID)) 
				{
					$q = "INSERT INTO `ErorrReportHistory` ( `ID` , `ErrorReportID` , `UserID` , `OldStatus` , `OldTime` , `Description` )
					VALUES ('', '$reportID', '$userID', '$newStatus', NOW( ) , '$description');";
					$this->_sql->query($q);
					return TRUE;
				}
				else 
				{
					throw new Exception("Вы не являетесь участником проекта.", 602);
				}
			}
			else 
			{
				throw new Exception("Проект не существует.",101);
			}
		}
		
		/**
		 * Удаление элемента из истории.
		 * @param unknown_type $userID
		 * @param unknown_type $projectID
		 * @param unknown_type $reportID
		 * @throws Exception
		 */
		public function deleteFromHistory($userID,$projectID, $reportID) 
		{
			$projectID = (int)$projectID;
			$p = new ProjectsController();
			if($p->isProjectExists($projectID))
			{	
				$r = new RequestsController();
				$userID = (int)$userID;
				if ($r->isSubscribed($userID, $projectID)) 
				{
					$q = "DELETE FROM `ErorrReportHistory` WHERE `ID` = '$reportID' LIMIT 1";
					$this->_sql->query($q);
					return TRUE;
				}
				else 
				{
					throw new Exception("Вы не являетесь участником проекта.", 602);
				}
			}
			else 
			{
				throw new Exception("Проект не существует.",101);
			}
		}
		
		/**
		 * 
		 * @param unknown_type $userID
		 * @param unknown_type $projectID
		 * @param unknown_type $reportID
		 * @param unknown_type $startIndex
		 * @param unknown_type $maxCount
		 * @throws Exception
		 */
		public function getHistory($userID, $projectID, $reportID, $startIndex = 0, $maxCount = 20) 
		{
			$projectID = (int)$projectID;
			$p = new ProjectsController();
			if($p->isProjectExists($projectID))
			{	
				$r = new RequestsController();
				$userID = (int)$userID;
				if ($r->isSubscribed($userID, $projectID)) 
				{
					$q = "SELECT * FROM `ErorrReportHistory` WHERE `ID` = '$reportID' LIMIT $startIndex,$maxCount";
					$res = $this->_sql->query($q);
					$ret = $this->_sql->fetchArr($res);
					return $ret;
				}
				else 
				{
					throw new Exception("Вы не являетесь участником проекта.", 602);
				}
			}
			else 
			{
				throw new Exception("Проект не существует.",101);
			}
		}
	}
?>