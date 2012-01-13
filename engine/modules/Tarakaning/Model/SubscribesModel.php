<?php

	require_once 'SubscribesDetailENUM.php';

/**
 * ����� ���������� ���������� �� ������.
 * @author timur 28.01.2011
 *
 */
	class SubscribesModel extends DBConnector
	{
/*
 *  1) �������� ������ ��������, � ������� ��������� ������������ (��� ���� �������) (�� ������� UsersInProjects)
	2) �������� ������� � ������ ������� (�������� ������ �� ������� UsersInProjects)
	3) ������ ������ �� ������ (������ � ������� SubscribesRequest)
 */

		const TABLE_SUBSCR_RQST 		= 'SUBSCR_RQST';
		const VIEW_SUBSCRIBES_DETAIL	= 'view_SubscribesDetails';
		const VIEW_SUBSCRIBES_USER_NICK = 'view_SubscribesUserNick';
		
		public function __construct()
		{
			parent::__construct();
		}
			
			
			/**
			 * ������ ������ �� ������.
			 * @param int $userID - id ������������, ��������� ������.
			 * @param int $projectID - id �������, �� ������� ������������ ����� ������.
			 * @return bool - ��������� ������ ������.
			 */
			public function sendRequest($userID, $projectID)
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				
				if (!$this->isRequestExists($userID, $projectID))
				{
					$this->_sql->insert(
						self::TABLE_SUBSCR_RQST, 
						new ArrayObject(array(
							0,
							$userID,
							$projectID,
							date("Y-m-d H:i:s")
						)),
						new ArrayObject(array(
							'ID', 'UserID', 'ProjectID','RequestTime'
						))
					);
				}
			}
			
			/**
			 * ����������� ���� �� ��������������� ������ �� ������� ������������ �� ������ ������.
			 * @param int $userID - id ������������, ��������� ������.
			 * @param int $projectID - id �������, �� ������� ������������ ����� ������.
			 * @return bool - ��������� ��������.
			 */
			public function isRequestExists($userID, $projectID) 
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{
					$count=$this->_sql->countQuery(self::TABLE_SUBSCR_RQST,"USER_ID = '$userID' AND `ProjectID` = '$projectID'");
					return $count==0 ? false : true;
				}
				else 
				{
					throw new Exception("������ �� ����������.", 101);
				}
			}

			/**
			 * ��������� ������ ��������, �� ������� �������� ������������.
			 * @param int $userID - id ������������, ��������� ������.
			 */
			public function getUserSubscribes($userID,Orderer $orderer,ListPager $paginator)
			{
				$userID = (int)$userID;
				$this->_sql->setLimit($paginator->getOffset(), $paginator->getSize());
				$this->_sql->setOrder(
					new SubscribesDetailENUM($orderer->getOrderField()), 
					new MySQLOrderENUM($orderer->getOrder())
				);
				$this->_sql->selAllWhere(self::VIEW_SUBSCRIBES_DETAIL, "UserID = $userID");
				$this->_sql->clearOrder();
				$this->_sql->clearLimit();
				return $this->_sql->getTable();
			}
			
			
			public function getSubscribesCount($userID)
			{
				$userID = (int)$userID;
				return $this->_sql->countQuery(self::TABLE_SUBSCR_RQST, "USER_ID = $userID");
			}
			
			public function getProjectSubscribes($projectID,Orderer $orderer,ListPager $paginator)
			{
				$projectID=(int)$projectID;
				$this->_sql->setLimit($paginator->getOffset(), $paginator->getSize());
				$this->_sql->setOrder(
					new ProjectSubscribesDetailENUM($orderer->getOrderField()), 
					new MySQLOrderENUM($orderer->getOrder())
				);
				$this->_sql->selAllWhere(self::VIEW_SUBSCRIBES_USER_NICK, "ProjectID = $projectID");
				$this->_sql->clearOrder();
				$this->_sql->clearLimit();
				return $this->_sql->getTable();
			}
			
			/**
			 * 
			 * ���������� ���������� ������ �� ������
			 * @param unknown_type $projectID
			 */
			public function getProjectSubscribesCount($projectID)
			{
				$projectID=(int)$projectID;
				return $this->_sql->countQuery(self::TABLE_SUBSCR_RQST, "PROJ_ID = $projectID");
			}
			
		    public function getMyOrdered(ItemKindENUM $kind,ErrorFieldsENUM $field, MySQLOrderEnum $direction,$page=1,$size=15,$userID=NULL,$projectID=NULL)
	        {
	            $this->useOrder($field,$direction);   
	            return $this->getReports($kind,$page,$size,$userID=NULL,$projectID=NULL);
	        }
			
			/**
			 * �������� ������� ������������ �� ������ ���������� �������.
			 * @param int $userID - id ������������.
			 * @param int $projectID - id �������, �� �������� ������������ ���������.
			 */
			public function removeSubscribe($userID, $projectID)
			{
				$userID = (int)$userID;
				$projectID = (int)$projectID;//���� ������ �� ����������, �� ������ ������.
				if ($this->isRequestExists($userID, $projectID))
				{
					return $this->_sql->query("DELETE FROM `UsersInProjects` WHERE `OwnerID` = '$userID' AND `ProjectID` = '$projectID' LIMIT 1");
				}
				else 
				{
					throw new Exception("�� �� ��������� ���������� �������.", 501);
				}
			}
			
			/**
			 * 
			 * ������� ���������� �������
			 * @param array $keysList ������ ��������������� ����������
			 * @param int $projectID ID �������
			 */
			public function deleteProjectMembers($keysList,$ownerID,$projectID)
			{
				$ownerID = (int)$ownerID;
				$projectID = (int)$projectID;
				$projectOperation=new ProjectsController();
	        	if ($projectOperation->isOwner($ownerID, $projectID) && $keysList!='')
	        	{
	        		$this->_sql->call(
	        			'DeleteUsersFromProject', 
	        			new ArrayObject(array(
	        				$projectID,
	        				$keysList
	        			))
	        		);
	        	}
			}
			
			public function getProjectUsers($projectID) 
			{
				$p = new ProjectsController();
				$projectID = (int)$projectID;
				if($p->isProjectExists($projectID))
				{
					$ownerID = $p->getOwnerID($projectID);
					$res = $this->_sql->query("SELECT `UserID` FROM `UsersInProjects` WHERE `ProjectID` = '$projectID'"); 
					$tmp = $this->_sql->GetRows($res);
					array_unshift($tmp, $ownerID);
					return $tmp;
				}
				else 
				{
					throw new Exception("������ �� ����������.", 101);
				}
			}
			
			public function getProjectUsersPaged($projectID, $startIndex = 0, $maxCount = 30)
			{
				$projectID = (int)$projectID;
				$p = new ProjectsController();
				if($p->isProjectExists($projectID))
				{
					$ownerID = $p->getOwnerID($projectID);
					$startIndex = (int)$startIndex;
					$maxCount = (int)$maxCount;
					$res = $this->_sql->query("SELECT `UserID` FROM `UsersInProjects` WHERE `ProjectID` = '$projectID' LIMIT $startIndex, $maxCount"); 
					$tmp = $this->_sql->GetRows($res);
					$startIndex == 0 ? array_unshift($tmp, $ownerID): TRUE;
					return $tmp;
				}
				else 
				{
					throw new Exception("������ �� ����������.", 101);
				}	
			}
		}
?>