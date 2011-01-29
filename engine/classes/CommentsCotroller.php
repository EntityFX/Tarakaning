<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
	/**
	 * Класс управления комментариями к ошибкам.
	 * @author timur 29.01.2011
	 *
	 */
	class CommentsController extends MySQLConnector
		{
			/*
			 * 1) прокомментировать ошибку
			 * 2) удаление комментария
			 * 3) получить список всех комментариев к проекту
			 * 4) получить список комментариев к ошибке
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
			
			public function deleteComment() 
			{
				;
			}
			
			public function getProjectComments() 
			{
				;
			}		

			public function getReportComments() 
			{
				;
			}
		}
?>