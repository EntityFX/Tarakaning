<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
	/**
	 * ����� ���������� ������������� � �������.
	 * @author timur 29.01.2011
	 *
	 */
	class CommentsController extends MySQLConnector
		{
			/* ErorrReportHistory, ReportComment 
			 * 1) ����������������� ������ 
			 * 2) �������� �����������
			 * 3) �������� ������ ���� ������������ � �������
			 * 4) �������� ������ ������������ � ������
			 */
			
			/*
			 * $reportID = (int)$reportID;
			 * $userID = (int)$userID;
			 * $projectID = (int)$projectID;
			 * $commentID = (int)$commentID;
			 */
			
			public function setReportComment($userID,$reportID, $comment)
			{
				$projectName = htmlspecialchars($projectName);
				$description = htmlspecialchars($description);
				$projectName = mysql_escape_string($projectName);
				$description = mysql_escape_string($description);
				$userID = (int)$userID;
				$projectID = (int)$projectID;
			}
			
			public function deleteComment($userID, $commentID) 
			{
				;
			}
			
			public function getProjectComments($projectID,$userID) 
			{
				;
			}		

			public function getReportComments($reportID, $userID) 
			{
				;
			}
		}
?>