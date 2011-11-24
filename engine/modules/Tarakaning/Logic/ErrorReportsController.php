<?php
    require_once "ErrorStatusENUM.php";
    
    require_once "ErrorPriorityENUM.php"; 
    
    require_once "ErrorTypeENUM.php"; 
    
    require_once "ProjectsController.php"; 
    
    require_once "UsersController.php";
    
    require_once "SubscribesController.php";
    
    require_once "ReportsAssigment.php";
    
    require_once 'ErrorFieldsENUM.php';
    
    require_once 'ItemKindENUM.php';
    
    require_once 'ItemDBKindENUM.php';
    
	require_once 'ConcreteUser.php';
    
    class ErrorReportsController extends DBConnector
    {
        private $_errorOwnerID;
        
        private $_projectOwnerID;
        
        public function __construct($projectID=NULL,$ownerID=NULL)
        {
            parent::__construct();
            $concreteUser=new ConcreteUser();
            $projectsController=new ProjectsController(); 
            if ($projectID==NULL)
            {
                $this->_errorOwnerID=$concreteUser->id;
                if ($concreteUser->defaultProjectID!=NULL)
                {
                    $this->_projectOwnerID=$concreteUser->defaultProjectID;
                }
            }
            else
            {
                if ($projectsController->isProjectExists((int)$projectID))
                {
                	$sub=new SubscribesController();
                    if ($ownerID==NULL)
                    {
                        $this->_errorOwnerID=$concreteUser->id;
                    }
                    else
                    {
                    	$usrController=new UsersController();
                        if ($usrController->checkIfExsist((int)$ownerID))
                        {
                            $this->_errorOwnerID=(int)$ownerID;        
                        }
                        else
                        {
                            throw new Exception("������������ �� ����������. ������ ��������������� ������������ ���������� ����� �� �������");
                        }
                    }
                    if ($sub->isSubscribed($this->_errorOwnerID,(int)$projectID) || $this->_errorOwnerID==$projectsController->getOwnerID((int)$projectID))
                    {
                        $this->_projectOwnerID=(int)$projectID;        
                    }
                    else
                    {
                        throw new Exception("������������ �".$this->_errorOwnerID." �� �������� �� ������ $projectID ��� �� �������� ��� ����������");
                    }
                }
                else
                {
                    throw new Exception("������ �� ����������. ������ ��������� ��������������� ������� ������ �� �������");
                }
            }
        }
        
        public function addReport(ItemDBKindENUM $kind, ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title, $description="", $steps="", $assignedTo=null)
        {
            $title=htmlspecialchars($title);
            if ($title=="")
            {
            	throw new Exception("��������� �� ������ ���� ������");
            }
            if ($kind->check())
            {
            	$kindValue=(string)$kind->getValue();  
            }
            else
            {
            	throw  new Exception("�������� ��� ������");
            }
            if ($priority->check())
            {
                $priorityValue=(string)$priority->getValue();    
            }
            else
            {
                throw new Exception("�������� ��������� ������");
            }
            if ($errorStatus->check())
            {
                $errorStatusValue=$errorStatus->getValue();    
            }
            else
            {
                throw new Exception("�������� ������ ������");
            } 
            if ($type->check())
            {
                $typeValue=$type->getValue();    
            }
            else
            {
                throw new Exception("�������� ������ ������");
            }
            $description=htmlspecialchars($description);
            $steps=htmlspecialchars($steps);
            $assignedTo=$assignedTo==' '?null:(int)$assignedTo;
            $this->_sql->call(
            	'AddItem', 
            	new ArrayObject(array(
                    $this->_errorOwnerID,
                    $this->_projectOwnerID,
                    $assignedTo,
                    $priorityValue,
                    $errorStatusValue,
                    date("Y-m-d H:i:s"),
                    $title,
                    $kindValue,
                    $description,
                    $typeValue,
                    $steps
                ))
            );
            return $this->_sql->getLastID();
        }   
        
        public function deleteReport($reportID) 
        {
            $id=(int)$reportID;
            $this->_sql->delete("ErrorReport","ID=$id");    
        }
        
        public function deleteReportsFromList($keysList,$userID=null,$projectID=null)
        {
        	$userID=$userID==null?$this->_errorOwnerID:(int)$userID;
        	$projectID=$projectID==null?$this->_projectOwnerID:(int)$projectID;
        	if ($keysList!='')
        	{
        		$this->_sql->call(
        			'DeleteItemsFromList', 
        			new ArrayObject(array(
        				$userID,
        				$projectID,
        				$keysList
        			))
        		);
        	}
        }
        
        /**
         * 
         * ������������� ������ ������
         * @param $reportID int ID ������
         * @param $errorStatusErrorStatusENUM ����� ������
         * @param $userID int ������� ����
         */
        public function editReport($reportID, $userID, $projectID, $title, ErrorStatusENUM $newStatus,ErrorPriorityENUM $priority, ErrorTypeEnum $type, $description="", $steps="", $assignedTo=null)
        {       	
        	if ($newStatus->check())
            {
                $newStatusValue=$newStatus->getValue();    
            }
            else
            {
                throw new Exception("�������� ������ ������");
            }
			$report=$this->getReportByID($reportID);
			if ($report!=null)
			{
				$currentStatusValue=$report["Status"];
                $statusesArray=$newStatus->getNumberedKeys();
                $currentValueKey=array_search($currentStatusValue,$statusesArray);
                $newValueKey=array_search($newStatusValue, $statusesArray);
				if ($this->canEditStatus($reportID, $projectID) && ($newValueKey-$currentValueKey)<=1)
                {
                	$editStatusFlag=false;
                	$canEditData=$this->canEditData($reportID, $projectID);
                	if ($currentStatusValue!=ErrorStatusENUM::CLOSED)
                	{
                		if ($currentStatusValue==ErrorStatusENUM::RESOLVED)
                		{
                			$editStatusFlag=$newStatusValue!=ErrorStatusENUM::CLOSED?true:$canEditData;
                		}
                		else 
                		{
                			$editStatusFlag=true;
                		}
                	}
                	else if ($userID==$report["UserID"])
                	{
                		$editStatusFlag=true;
                	}
                	if ($editStatusFlag)
                	{
                		if (!$canEditData)
                		{
	                		if ($currentStatusValue==$newStatusValue) return false;
                			$this->_sql->update(
	                			"ErrorReport", 
	                			"ID=$reportID", 
	                			new ArrayObject(array(
	                				"Status" => $newStatusValue
	                			))
	                		);
                		}
                		else 
                		{
                			if ($title=='') throw new Exception("��������� �� ������ ���� ������");
                            $this->_sql->call(
	                			"EditItem", 
	                			new ArrayObject(array(
	                				(int)$reportID,
	                				$title,
	                				(int)$priority->getValue(),
	                				$newStatusValue,
	                				(int)$assignedTo,
	                				$description,
	                				$type->getValue(),
	                				$steps
	                			))
	                		);
                		}
                		return true;
                	}
                }
			}
        }
        
        /**
        * ��������� ��������� ������
        * 
        * @param int $reportID ID ������
        * @return int
        */
        private function getReportOwner($reportID)
        {
            $reportID=(int)$reportID;
        	$this->_sql->selFieldsWhere("ErrorReport","ID=$reportID","UserID");
            $res=$this->_sql->getTable();
            return (int)$res[0]["UserID"];
        }
        
        private function checkIsProjectError($reportID)
        {
            $reportID=(int)$reportID;
            $this->_sql->selFieldsWhere("ErrorReport","ID=$reportID","ProjectID");
            $projectID=$this->_sql->getTable();          
            $projectID=$projectID[0]["ProjectID"];
            return $projectID==$this->_projectOwnerID;
        }
        
        public function checkIsExsist($reportId)
        {
            $id=(int)$reportId;
            $countGroups=$this->_sql->countQuery("ErrorReport","ID=$id");
            return (Boolean)$countGroups;   
        }
        
        public function getReportsByProject($projectID,ItemKindENUM $kind,$from,$size)
        {
            $this->checkProject($projectID);  
            $this->useLimit($from,$size);
            $itemKind=$kind->getValue();
            if ($itemKind<>ItemKindENUM::ALL)
            {
            	$kindExpression="AND Kind='$itemKind'";
            }
            $this->_sql->selAllWhere("errorreportsinfo","ProjectID=$projectID $kindExpression");
            $res=$this->_sql->getTable();
            if ($res!=null)
            {
	            foreach($res as $index => $report)
	            {
	            	$this->normalizeBugReport(&$report);
	            	$res[$index]=$report;
	            }
            }
            return $res;  
        }
        
        public function countReportsByProject($projectID,ItemKindENUM $kind)
        {
            $this->checkProject($projectID);
            $itemKind=$kind->getValue();
            if ($itemKind<>ItemKindENUM::ALL)
            {
            	$kindExpression="AND Kind='$itemKind'";
            }
            return $this->_sql->countQuery("errorreportsinfo","ProjectID=$projectID $kindExpression");
        }
        
        private function checkProject(&$projectID)
        {
            if ($projectID==NULL)
            {
                $projectID=$this->_projectOwnerID;    
            }
            else
            {
                $pc=new ProjectsController();
                if ($pc->isProjectExists((int)$projectID))
                {
                    $projectID=(int)$projectID;            
                }
                else
                {
                    throw new Exception("������ �� ����������. ������ �������� ������ ������ �� ��������������� �������");    
                }     
            }
        }
        
        public function countReports(ItemKindENUM $kind)
        {
        	$userID=$this->_errorOwnerID;
        	$projectID=$this->_projectOwnerID;
            $itemKind=$kind->getValue();
            if ($itemKind<>ItemKindENUM::ALL)
            {
            	$kindExpression="AND Kind='$itemKind'";
            }
        	return $this->_sql->countQuery("errorreportsinfo","UserID=$userID AND ProjectID=$projectID $kindExpression");
        }
        
        public function countAssignedReports(ItemKindENUM $kind)
        {
        	$userID=$this->_errorOwnerID;
        	$projectID=$this->_projectOwnerID;
            $itemKind=$kind->getValue();
            if ($itemKind<>ItemKindENUM::ALL)
            {
            	$kindExpression="AND Kind='$itemKind'";
            }
        	return $this->_sql->countQuery("errorreportsinfo","AssignedTo=$userID AND ProjectID=$projectID $kindExpression");
        }
        
        public function getReports(ItemKindENUM $kind,$page=1,$size=15,$userID=NULL,$projectID=NULL)
        {
            $res=NULL;
            
            if ($userID==NULL)
            {
                $userID=$this->_errorOwnerID;
            }
            else
            {
                $userID=(int)$userID;
                $uc=new UsersController();
                if ($uc->checkIfExsist($userID))
                {
                    $this->checkProject($projectID); 
                }
                else
                {
                    throw new Exception("������������ �� ����������.");    
                }
            }
            if ($projectID==NULL)
            {
                $projectID=$this->_projectOwnerID;    
            }
            $itemKind=$kind->getValue();
            if ($itemKind<>ItemKindENUM::ALL)
            {
            	$kindExpression="AND Kind='$itemKind'";
            }
            $this->_sql->setLimit($page, $size);
            $this->_sql->selAllWhere("errorreportsinfo","UserID=$userID AND ProjectID=$projectID $kindExpression");
            $this->_sql->clearLimit();
            $res=$this->_sql->getTable();
            if ($res!=null)
            {
	            foreach($res as $index => $report)
	            {
	            	$this->normalizeBugReport(&$report);
	            	$res[$index]=$report;
	            }
            }
            return $res;
        }
        
        public function getMyOrdered(ItemKindENUM $kind,ErrorFieldsENUM $field, MySQLOrderEnum $direction,$page=1,$size=15,$userID=NULL,$projectID=NULL)
        {
            $this->useOrder($field,$direction);   
            return $this->getReports($kind,$page,$size,$userID=NULL,$projectID=NULL);
        }
        
        public function getAssignedToMe(ItemKindENUM $kind,ErrorFieldsENUM $field, MySQLOrderEnum $direction,$page=1,$size=15,$userID=NULL,$projectID=NULL)
        {
        	$this->_sql->setOrder($field, $direction);
        	$this->_sql->setLimit($page, $size);
            if ($userID==NULL)
            {
                $userID=$this->_errorOwnerID;
            }
            else
            {
                $userID=(int)$userID;
                $uc=new UsersController();
                if ($uc->checkIfExsist($userID))
                {
                    $this->checkProject($projectID); 
                }
                else
                {
                    throw new Exception("������������ �� ����������.");    
                }
            }
            if ($projectID==NULL)
            {
            	$projectID=$this->_projectOwnerID;    
            }
            $itemKind=$kind->getValue();
            if ($itemKind<>ItemKindENUM::ALL)
            {
            	$kindExpression="AND Kind='$itemKind'";
            }
            $this->_sql->selAllWhere("errorreportsinfo","AssignedTo=$userID AND ProjectID=$projectID $kindExpression");
        	$this->_sql->clearOrder();
        	$this->_sql->clearLimit();
            $res=$this->_sql->getTable();
            if ($res!=null)
            {
	            foreach($res as $index => $report)
	            {
	            	$this->normalizeBugReport(&$report);
	            	$res[$index]=$report;
	            }
            }
            return $res;
        }
        
        public function getProjectOrdered($projectID,ItemKindENUM $kind,ErrorFieldsENUM $field, MySQLOrderEnum $direction,$from,$size)
        {
            $this->useOrder($field,$direction);
            $res=$this->getReportsByProject($projectID,$kind,$from,$size);
            return $res;
        }
        
        public function getAllReports()
        {
            $this->_sql->selAll("ErrorReport");
            return $this->_sql->getTable();
        }
        
        public function getReport($reportID)
        {
        	return 
        	$report=$this->getReportByID($reportID);
        	if ($report!=null){
        		
        	}
        }
        
        private function getReportByID($reportID)
        {
            $reportID=(int)$reportID;
        	$this->_sql->selAllWhere("errorreportsinfo","ID=$reportID");
            $arr=$this->_sql->getTable();
            if ($arr==null)
            {
            	return null;
            }
            $this->normalizeBugReport($arr[0]);
            return $arr[0];
        }
        
        private function chekProjectOwnerOrReportOwner($reportID)
        {
            $id=(int)$id;
            $pC=new ProjectsController();
            return ($this->_errorOwnerID==$this->getReportOwner($reportID) || $this->_errorOwnerID==$pC->isOwner($this->_errorOwnerID,$this->_projectOwnerID));               
        }
        
        public function canEditStatus($reportID,$projectID)
        {
        	$reportID=(int)$reportID;
        	$projectID=(int)$projectID;
        	$user=$this->_errorOwnerID;
        	$isOwnerORAssigned=$this->_sql->countQuery("ErrorReport","ID=$reportID AND (UserID=$user OR AssignedTo=$user)");
        	$pC=new ProjectsController();
            return ($isOwnerORAssigned !=0) || $this->_errorOwnerID==$pC->isOwner($user,$projectID);
        }
        
        public function canEditData($reportID,$projectID)
        {
        	$projectID=(int)$projectID;
        	$pC=new ProjectsController();
        	return $this->_errorOwnerID==$this->getReportOwner($reportID) || $this->_errorOwnerID==$pC->isOwner($this->_errorOwnerID,$projectID);
        }
        
        /**
         * 
         * ����������� ���������� ������ �� ������
         */
        private function normalizeBugReport(&$reportData)
        {
        	switch ($reportData["PriorityLevel"])
        	{
        		case ErrorPriorityENUM::MINIMAL:
        			$reportData["PriorityLevelN"]="������"; break;
        		case ErrorPriorityENUM::NORMAL:
        			$reportData["PriorityLevelN"]="�������"; break;
         		case ErrorPriorityENUM::HIGH:
        			$reportData["PriorityLevelN"]="������"; break;
        	}
        	switch ($reportData["Kind"])
        	{
        		case ItemKindENUM::DEFECT:
        			$reportData["KindN"]="������"; break;
        		case ItemKindENUM::TASK:
        			$reportData["KindN"]="������"; break;
        	}
        	switch ($reportData["ErrorType"])
        	{
        		case ErrorTypeENUM::BLOCK: 
        			$reportData["ErrorTypeN"]="�����������"; break;
        		case ErrorTypeENUM::COSMETIC: 
        			$reportData["ErrorTypeN"]="�������������"; break;
        		case ErrorTypeENUM::CRASH:
        			$reportData["ErrorTypeN"]="����"; break;
        		case ErrorTypeENUM::ERROR_HANDLE: 
        			$reportData["ErrorTypeN"]="����������"; break;
        		case ErrorTypeENUM::FUNCTIONAL:
        			$reportData["ErrorTypeN"]="�������������"; break;
        		case ErrorTypeENUM::MAJOR: 
        			$reportData["ErrorTypeN"]="������������"; break;
        		case ErrorTypeENUM::MINOR: 
        			$reportData["ErrorTypeN"]="�������������"; break;
        		case ErrorTypeENUM::SETUP: 
        			$reportData["ErrorTypeN"]="������ �����������"; break;
        	}
        	switch ($reportData["Status"])
        	{
        		case ErrorStatusENUM::IS_NEW:
        			$reportData["StatusN"]="�����"; break;
        		case ErrorStatusENUM::IDENTIFIED:
        			$reportData["StatusN"]="���������������"; break;
        		case ErrorStatusENUM::ASSESSED:
        			$reportData["StatusN"]="� ��������"; break;
        		case ErrorStatusENUM::RESOLVED:
        			$reportData["StatusN"]="�����"; break;
        		case ErrorStatusENUM::CLOSED:
        			$reportData["StatusN"]="������"; break;
        	}
        }
    }
?>