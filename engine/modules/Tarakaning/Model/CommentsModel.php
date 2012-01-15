<?php

Loader::LoadModel('ProjectsModel');   
Loader::LoadModel('ItemCommentsENUM'); 
Loader::LoadModel('RequestModel');  

Loader::LoadSystem('addons','Serialize');

/**
 * ����� ���������� ������������� � �������.
 * @author timur 29.01.2011
 *
 */
class CommentsModel extends DBConnector
	{
		
        const TABLE_ITEM_COMMENT    = 'ITEM_CMMENT';
        const VIEW_COMMENTS_DETAIL  = 'view_CommentsDetail';
		
		public function __construct($projectID=NULL,$ownerID=NULL)
       	{
            parent::__construct();
       	}
		
		
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
		public function setReportComment($projectID, $userID, $reportID, $comment)
		{
			/*
			 * ������ ��������� ������������� �������
			 * ����� - ������������
			 * ����� �������� �� ����������� ��� ��������
			 * ��� ��������� ���������� �� �����
			 */
			$projectID = (int)$projectID;
			$p = new ProjectsModel();
			if($p->isProjectExists($projectID))
			{	
				$r = new RequestModel();
				$userID = (int)$userID;
				if ($r->isSubscribed($userID, $projectID) || $p->isOwner($userID, $projectID)) 
				{
					$comment = htmlspecialchars($comment);
					$comment = mysql_escape_string($comment);
					$reportID = (int)$reportID;
					$this->_sql->insert(
                        self::TABLE_ITEM_COMMENT,
                        new ArrayObject(array(
                            0,
                            $reportID,
                            $userID,
                            'NOW( )',
                            $comment
                        )),
                        new ArrayObject(array(
                            'ITEM_CMMENT_ID',
                            'ITEM_ID',
                            'USER_ID',
                            'CRT_TM',
                            'CMMENT'
                        ))
                    );
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
			$p = new ProjectsModel();
			if($p->isProjectExists($projectID))
			{
				if ($this->isCommentExist($commentID))
				{
					$r = new RequestModel();
					if ($r->isSubscribed($userID, $projectID))  
					{
						if ($this->isCommentOwner($commentID, $userID, $projectID))
						{
							$this->_sql->delete(self::TABLE_ITEM_COMMENT,"ITEM_CMMENT_ID=$commentID");
                            return true;
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
			$p = new ProjectsModel();
			if($p->isProjectExists($projectID))
			{
				$r = new RequestModel();
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
			$p = new ProjectsModel();
			if($p->isProjectExists($projectID))
			{
				$r = new RequestModel();
				if ($r->isSubscribed($userID, $projectID) || $p->isOwner($userID, $projectID))  
				{
					$this->_sql->setLimit($page, $size);
					$this->_sql->setOrder($fieldEnum, $direction);
					$this->_sql->selAllWhere(self::VIEW_COMMENTS_DETAIL, "ItemID = $reportID");
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
			return $this->_sql->countQuery(self::VIEW_COMMENTS_DETAIL, "ItemID = $reportID");
		}
		
		/**
		 * �������� ������������� �����������.
		 * @param unknown_type $commentID
		 */
		public function isCommentExist($commentID)
		{
			return $this->_sql->countQuery(self::TABLE_ITEM_COMMENT,"ITEM_CMMENT_ID=$commentID")==0 ? false : true;
		}
		
		/**
		 * ��������� id ������������ �� id �����������.
		 * @param unknown_type $commentID
		 */
		public function getUserIDbyCommentID($commentID) 
		{
			$commentID = (int)$commentID;
            $this->_sql->selAllWhere(self::TABLE_ITEM_COMMENT,"ITEM_CMMENT_ID=$commentID");
            $res=$this->_sql->getTable();
			return $res[0]["USER_ID"];
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
            $this->_sql->selAllWhere(self::TABLE_ITEM_COMMENT,"ITEM_CMMENT_ID=$commentID");
            $res=$this->_sql->getTable();
			$p = new ProjectsModel();
			$s = $p->isOwner($userID, $projectID);
			return  $res[0]["UserID"] == $userID || $s ? TRUE : FALSE;
		}
	}
?>