<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
require_once 'engine/classes/ProjectsController.php';	
require_once 'engine/classes/RequestsController.php';

	class ReportHistory extends MySQLConnector
	{
		/*
		 *����� ���������� �������� ������ - ReportHistory:
		 *
		 *���������� � ������� (+����������� ������������� ��� ���� ���������� ������)
		 *�������� �������
		 *�������� ������� 
		 *
		 */

		/*
		 * $reportID = (int)$reportID;
		 * $userID = (int)$userID;
		 * $projectID = (int)$projectID;
		 * $commentID = (int)$commentID;
		 * $newStatus = (int)$newStatus;
		 * $reportID = (int)$reportID;
		 * $historyID = (int)$historyID;
		 * $description
		 */
		
		/**
		 * ���������� �������� � �������.
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
					throw new Exception("�� �� ��������� ���������� �������.", 602);
				}
			}
			else 
			{
				throw new Exception("������ �� ����������.",101);
			}
		}
		
		/**
		 * �������� �������� �� �������.
		 * @param  $userID
		 * @param  $projectID
		 * @param  $reportID
		 * @throws Exception
		 */
		public function deleteFromHistory($userID,$projectID, $reportID, $historyID) 
		{
			$projectID = (int)$projectID;
			$p = new ProjectsController();
			if($p->isProjectExists($projectID))
			{	
				$r = new RequestsController();
				$userID = (int)$userID;
				if ($r->isSubscribed($userID, $projectID)) 
				{
					if ($this->isOwner($userID, $historyID,$projectID)) 
					{
						$q = "DELETE FROM `ErorrReportHistory` WHERE `ID` = '$reportID' LIMIT 1";
						$this->_sql->query($q);
						return TRUE;
					}
					else 
					{
						throw new Exception("������� ����� ������ ����� ��� �����.", 901);
					}
				}
				else 
				{
					throw new Exception("�� �� ��������� ���������� �������.", 602);
				}
			}
			else 
			{
				throw new Exception("������ �� ����������.",101);
			}
		}
		
		/**
		 * ��������� ��������� � �������.
		 * @param  $userID
		 * @param  $projectID
		 * @param  $reportID
		 * @param  $startIndex
		 * @param  $maxCount
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
					throw new Exception("�� �� ��������� ���������� �������.", 602);
				}
			}
			else 
			{
				throw new Exception("������ �� ����������.",101);
			}
		}
		
		public function getSomeOfHistory($userID, $projectID, $reportID, $count = 3) 
		{
			$projectID = (int)$projectID;
			$p = new ProjectsController();
			if($p->isProjectExists($projectID))
			{	
				$r = new RequestsController();
				$userID = (int)$userID;
				if ($r->isSubscribed($userID, $projectID)) 
				{
					$q = "SELECT * FROM `ErorrReportHistory` WHERE `ErrorReportID` = '$reportID' ORDER BY `OldTime` DESC LIMIT $count";
					$res = $this->_sql->query($q);
					$ret = $this->_sql->fetchArr($res);
					return $ret;
				}
				else 
				{
					throw new Exception("�� �� ��������� ���������� �������.", 602);
				}
			}
			else 
			{
				throw new Exception("������ �� ����������.",101);
			}
		}
		
		/**
		 * ��������� �������� �� ������ ������������ ������� � ������� ��� ������� ����� �������.
		 * @param  $userID
		 * @param  $historyID
		 */
		public function isOwner($userID, $historyID, $projectID) 
		{
			$res = $this->_sql->query("SELECT * FROM `ErorrReportHistory` WHERE `ID`='$historyID'");
			$tmp = $this->_sql->fetchArr($res);
			$p = new ProjectsController();
			$s = $p->isOwner($userID, $projectID);
			return $tmp["UserID"] == $userID || $s ? TRUE : FALSE;
		}
		
		public function isHistoryIdExists($historyID)
		{
			$res = $this->_sql->query("SELECT * FROM `ErorrReportHistory` WHERE `ID`='$historyID'");
			$tmp = $this->_sql->fetchArr($res);
			return ($tmp == NULL) ? FALSE : TRUE;
		}
		
	}
?>