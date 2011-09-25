<?php
require_once 'ProjectsController.php';	
require_once 'ProjectCommentsENUM.php';
	/**
	 * Класс управления комментариями к ошибкам.
	 * @author timur 29.01.2011
	 *
	 */
	class CommentsController extends DBConnector
		{
			public function __construct($projectID=NULL,$ownerID=NULL)
       		{
            	parent::__construct();
       		}
			
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
					if ($r->isSubscribed($userID, $projectID) || $p->isOwner($userID, $projectID)) 
					{
						;//здесь происходить должна основная работа
						
						$comment = htmlspecialchars($comment);
						$comment = mysql_escape_string($comment);
						$reportID = (int)$reportID;
						
						$q = "INSERT INTO `ReportComment` ( `ID` , `ReportID` , `UserID` , `Time` , `Comment` )
						VALUES ('', '$reportID', '$userID', NOW( ) , '$comment');";
						$this->_sql->query($q);
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
							if ($this->isCommentOwner($commentID, $userID, $projectID))
							{
								$this->_sql->query("DELETE FROM `ReportComment` WHERE `ID` = '$commentID' LIMIT 1");
								return TRUE;
							}
							else 
							{
								throw new Exception("Вы не являетесь автором комментария или проекта", 1002);
							}
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
						//$this->_sql->query("");  			какой запрос тут надо????
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
			public function getReportComments($projectID, $reportID, ProjectCommentsENUM $orderField, MySQLOrderEnum $direction,$page=1,$size=15) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$reportID = (int)$reportID;
				$startIndex = (int)$startIndex;
				$maxCount = (int)$maxCount;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{
					$r = new RequestsController();
					if ($r->isSubscribed($userID, $projectID) || $p->isOwner($userID, $projectID))  
					{
						$this->_sql->setLimit($startIndex, $maxCount);
						$this->_sql->selAllWhere("commentsdetail", "ReportID = $reportID");
						$this->_sql->clearLimit();
						return $this->_sql->getTable();
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
			
			public function getReportCommentsCount($projectID, $reportID, $userID, $startIndex = 0, $maxCount = 20)
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$reportID = (int)$reportID;
				$startIndex = (int)$startIndex;
				$maxCount = (int)$maxCount;
				$this->_sql->countQuery("commentsdetail", "ReportID = $reportID");
			}
			
			/**
			 * Проверка существования комментария.
			 * @param unknown_type $commentID
			 */
			public function isCommentExist($commentID)
			{
				return $this->_sql->countQuery("ReportComment","ID=$commentID")==0 ? false : true;
			}
			
			/**
			 * Получение id пользователя по id комментария.
			 * @param unknown_type $commentID
			 */
			public function getUserIDbyCommentID($commentID) 
			{
				$commentID = (int)$commentID;
				$res = $this->_sql->query("SELECT * FROM `ReportComment` WHERE `ID`='$commentID'");
				$ret = $this->_sql->fetchArr($res);
				return $ret["UserID"];
			}
			
			/**
			 * Проверяет является ли данный пользователь автором данного комментария.
			 * @param unknown_type $commentID
			 * @param unknown_type $userID
			 */
			public function isCommentOwner($commentID, $userID, $projectID) 
			{
				$userID = (int)$userID;
				$commentID = (int)$commentID;
				$res = $this->_sql->query("SELECT * FROM `ReportComment` WHERE `ID`='$commentID'");
				$ret = $this->_sql->fetchArr($res);
				$p = new ProjectsController();
				$s = $p->isOwner($userID, $projectID);
				return  $ret["UserID"] == $userID || $s ? TRUE : FALSE;
			}
		}
?>