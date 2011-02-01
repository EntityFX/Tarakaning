<?php
require_once 'engine/libs/mysql/MySQLConnector.php';
require_once 'engine/classes/ProjectsController.php';	
require_once 'engine/classes/RequestsController.php';
	/**
	 * Класс управления комментариями к ошибкам.
	 * @author timur 29.01.2011
	 *
	 */
	class CommentsController extends MySQLConnector
		{
			/* ErorrReportHistory, ReportComment 
			 * 1) прокомментировать ошибку 
			 * 2) удаление комментария
			 * 3) получить список всех комментариев к проекту
			 * 4) получить список комментариев к ошибке
			 */
			
			/*
			 * $reportID = (int)$reportID;
			 * $userID = (int)$userID;
			 * $projectID = (int)$projectID;
			 * $commentID = (int)$commentID;
			 */
			
			/**
			 * Функция для комментирования отчета об ошибке.
			 * @param unknown_type $projectID
			 * @param unknown_type $userID
			 * @param unknown_type $reportID
			 * @param unknown_type $comment
			 * 
			 * @todo 1) добавить проверку на существование отчета об ошибке. <br />
			 * 2) добавить проверку на существование данного пользователя.
			 */
			public function setReportComment($projectID, $userID,$reportID, $comment)
			{
				/*
				 * сперва проверить существование проекта
				 * потом - пользователя
				 * потом является ли подписанным или хозяином
				 * еще проверить существует ли отчет
				 */
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{	
					$r = new RequestsController();
					$userID = (int)$userID;
					if ($r->isSubscribed($userID, $projectID)) 
					{
						;//здесь происходить должна основная работа
						
						$comment = htmlspecialchars($comment);
						$comment = mysql_escape_string($comment);
						$reportID = (int)$reportID;
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
			 * Удаление комментария к отчету об ошибке.
			 * @param unknown_type $projectID
			 * @param unknown_type $userID
			 * @param unknown_type $commentID
			 * @throws Exception
			 */
			public function deleteComment($projectID, $userID, $commentID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{
					if ($this->isCommentExist($commentID))
					{
						$r = new RequestsController();
						if ($r->isSubscribed($userID, $projectID))  
						{
							;
						}
						else 
						{
							throw new Exception("Вы не являетесь участником проекта.", 602);
						}
					}
					else 
					{
						throw new Exception("Комментария не существует.", 1001);
					}
				}
				else 
				{
					throw new Exception("Проект не существует.",101);
				}
				
			}
			
			/**
			 * Получение списка комментариев к проекту.
			 * @param unknown_type $projectID
			 * @param unknown_type $userID
			 * @throws Exception
			 */
			public function getProjectComments($projectID,$userID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{
					$r = new RequestsController();
					if ($r->isSubscribed($userID, $projectID))  
					{
						;
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
			 * Получение списка комментариев к отчету об ошибке.
			 * @param unknown_type $projectID
			 * @param unknown_type $reportID
			 * @param unknown_type $userID
			 */
			public function getReportComments($projectID, $reportID, $userID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$reportID = (int)$reportID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{
				
				}
				else 
				{
					throw new Exception("Проект не существует.",101);
				}
			}
			
			public function isCommentExist($commentID)
			{
				$res = $this->_sql->query("SELECT * FROM `ReportComment` WHERE `ID`='$commentID'");
				$ret = $this->_sql->fetchArr($res);
				return $ret == NULL ? FALSE : TRUE;
			}
		}
?>