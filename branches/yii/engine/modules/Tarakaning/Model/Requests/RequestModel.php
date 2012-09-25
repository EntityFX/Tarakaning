<?php
/**
 *  ласс подтверждени€/отклонени€ на подписку.
 * @author timur 28.01.2011
 *
 */
class RequestModel extends DBConnector
{
	const TABLE_USER_IN_PROJ = 'USER_IN_PROJ';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * 1) подтвердить за€вку (вз€ть из таблицы SubscribesRequest, записать в таблицу UsersInProjects)
	 * 2) отклонить за€вку (удалить из таблицы SubscribesRequest)
	 * 3) показать список всех за€вок (получить из SubscribesRequest)
	 */
	
	/**
	 * ѕодтверждение запроса на подписку.
	 * @param int $keysList - список запросов.
	 * @param int $ownerID - id автора проекта.
	 * @param int $projectID - id проекта.
	 */
	public function acceptRequest($keysList,$ownerID,$projectID) 
	{
		$ownerID = (int)$ownerID;
		$projectID = (int)$projectID;
		
		$projectsController=new ProjectsModel();
		if ($projectsController->isOwner($ownerID, $projectID))
		{
			var_dump($keysList);
			$this->_sql->call(
				'AcceptRequest',
				new ArrayObject(array(
					$projectID,
					$keysList
				)) 
			);
		}
	}
	
	/**
	 * ѕровер€етс€ подписан ли данный пользователь в данном проекте.
	 * @param int $userID - id пользовател€.
	 * @param int $projectID - id проекта.
	 */
	public function isSubscribed($userID, $projectID) 
	{
		$userID = (int)$userID;
		$projectID = (int)$projectID;
		$res = $this->_sql->countQuery(self::TABLE_USER_IN_PROJ, "PROJ_ID = $projectID AND USER_ID = $userID");
		return $res == 1 ? true : false;
	}
	
	/**
	 * ќтклонение за€вки.
	 * @param int $requestID - id запроса.
	 * @param int $userID - id пользовател€, пославшего запрос.
	 * @param int $projectID - id проекта.
	 * @param int $ownerID - id автора проекта.
	 */
	public function declineRequest($requestID, $userID, $projectID, $ownerID) 
	{
		$userID = (int)$userID;
		$projectID = (int)$projectID;
		if (ProjectsController::isProjectExists($projectID))
		{
			$requestID = (int)$requestID;
			$ownerID = (int)$ownerID;
			
			if (ProjectsController::isOwner($ownerID, $projectID))
			{
				if($this->isSubscribed($userID, $projectID))
				{
					throw new Exception("ѕользователь уже участвует в проекте.",601);
				}
				else 
				{
					$this->_sql->query("DELETE FROM `SubscribesRequest` WHERE `ID` = '$requestID' LIMIT 1");
					return TRUE;
				}
			}
			else 
			{
				throw new Exception("Ќе ¬ы €вл€етесь —оздателем проекта.",102);  
			}
		}
		else 
		{
			throw new Exception("ѕроект не существует.",101);
		}
	}
	
	/**
	 * ѕолучение списка за€вок дл€ данного проекта.
	 * @param unknown_type $userID
	 * @param unknown_type $projectID
	 * @param unknown_type $startIndex
	 * @param unknown_type $maxCount
	 */
	public function getRequests($userID, $projectID, $startIndex=0, $maxCount=20) 
	{
		$userID = (int)$userID;
		$projectID = (int)$projectID;
		if (ProjectsController::isProjectExists($projectID))
		{
			if (ProjectsController::isOwner($ownerID, $projectID))
			{	
				$startIndex = (int)$startIndex;
				$maxCount = (int)$maxCount;
				$res = $this->_sql->query("SELECT * FROM `SubscribesRequest` WHERE `ProjectID` = '$projectID' LIMIT $startIndex, $maxCount");
				$ret = $this->_sql->GetRows($res);
				return $ret;
			}
			else 
			{
				throw new Exception("Ќе ¬ы €вл€етесь —оздателем проекта.",102); 
			}
		}
		else 
		{
			throw new Exception("ѕроект не существует.",101);
		}
	}
}
?>