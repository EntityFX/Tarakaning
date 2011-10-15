<?php
require_once 'ProjectsController.php';	
require_once 'ItemCommentsENUM.php';
require_once 'RequestsController.php';
require_once 'engine/system/addons/Serialize.php';

	/**
	 * ����� ���������� ������������� � �������.
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
			
			/**
			 * ������� ��� ��������������� ������ �� ������.
			 * @param unknown_type $projectID
			 * @param unknown_type $userID
			 * @param unknown_type $reportID
			 * @param unknown_type $comment
			 * 
			 * @todo 1) �������� �������� �� ������������� ������ �� ������. <br />
			 * 2) �������� �������� �� ������������� ������� ������������.
			 */
			public function setReportComment($projectID, $userID,$reportID, $comment)
			{
				/*
				 * ������ ��������� ������������� �������
				 * ����� - ������������
				 * ����� �������� �� ����������� ��� ��������
				 * ��� ��������� ���������� �� �����
				 */
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{	
					$r = new RequestsController();
					$userID = (int)$userID;
					if ($r->isSubscribed($userID, $projectID) || $p->isOwner($userID, $projectID)) 
					{
						;//����� ����������� ������ �������� ������
						
						$comment = htmlspecialchars($comment);
						$comment = mysql_escape_string($comment);
						$reportID = (int)$reportID;
						
						$q = "INSERT INTO `ReportComment` ( `ID` , `ReportID` , `UserID` , `Time` , `Comment` )
						VALUES ('', '$reportID', '$userID', NOW( ) , '$comment');";
						$this->_sql->query($q);
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
			 * �������� ����������� � ������ �� ������.
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
								throw new Exception("�� �� ��������� ������� ����������� ��� �������", 1002);
							}
						}
						else 
						{
							throw new Exception("�� �� ��������� ���������� �������.", 602);
						}
					}
					else 
					{
						throw new Exception("����������� �� ����������.", 1001);
					}
				}
				else 
				{
					throw new Exception("������ �� ����������.",101);
				}
				
			}
			
			/**
			 * 
			 * ������� ������� ��������� ������������
			 * @param int $userID ������������� ������������
			 * @param array $projectsList ������ �������� �� �������� (���� - �������������)
			 */
			public function deleteCommentsFromList($userID,$commentsList)
			{
				$userID = (int)$userID;
				if ($commentsList!=null)
	        	{
	        		$commentsListSerialized=Serialize::SerializeForStoredProcedure($commentsList);
	        		$this->_sql->call(
	        			'DeleteCommentsFromList',
	        			new ArrayObject(array(
	        				$userID,
	        				$commentsListSerialized
	        			))
	        		);
	        	}
			}
			
			/**
			 * ��������� ������ ������������ � �������.
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
						//$this->_sql->query("");  			����� ������ ��� ����????
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
			 * ��������� ������ ������������ � ������ �� ������.
			 * @param unknown_type $projectID
			 * @param unknown_type $reportID
			 * @param unknown_type $userID
			 */
			public function getReportComments($projectID, $reportID, $userID, ItemCommentsENUM $fieldEnum, MySQLOrderENUM $direction,$page=1,$size=15) 
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
						$this->_sql->setLimit($page, $size);
						$this->_sql->setOrder($fieldEnum, $direction);
						$this->_sql->selAllWhere("commentsdetail", "ReportID = $reportID");
						$this->_sql->clearLimit();
						$this->_sql->clearOrder();
						return $this->_sql->getTable();
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
			 * 
			 * ���������� ����� ������������ � ������� ������
			 * @param int $reportID ������������� ������
			 */
			public function getReportCommentsCount($reportID)
			{
				$reportID = (int)$reportID;
				return $this->_sql->countQuery("commentsdetail", "ReportID = $reportID");
			}
			
			/**
			 * �������� ������������� �����������.
			 * @param unknown_type $commentID
			 */
			public function isCommentExist($commentID)
			{
				return $this->_sql->countQuery("ReportComment","ID=$commentID")==0 ? false : true;
			}
			
			/**
			 * ��������� id ������������ �� id �����������.
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
			 * ��������� �������� �� ������ ������������ ������� ������� �����������.
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