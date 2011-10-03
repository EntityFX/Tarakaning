<?php
    require_once "ErrorStatusENUM.php";
    
    require_once "ErrorPriorityENUM.php"; 
    
    require_once "ErrorTypeENUM.php";
    
    require_once "ConcreteUser.php"; 
    
    require_once "ProjectsController.php"; 
    
    require_once "UsersController.php";
    
    require_once "SubscribesController.php";
    
    require_once "ReportsAssigment.php";
    
    require_once 'ErrorFieldsENUM.php';
    
    require_once 'ItemKindENUM.php';
    
    require_once 'ItemDBKindENUM.php';
    
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
                        throw new Exception("������������ �".$this->_errorOwnerID." �� �������� �� ������ ��� �� �������� ��� ����������");
                    }
                }
                else
                {
                    throw new Exception("������ �� ����������. ������ ��������� ��������������� ������� ������ �� �������");
                }
            }
        }
        
        public function addReport(ItemDBKindENUM $kind, ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title="", $description="", $steps="")
        {
            $title=htmlspecialchars($title);
            if ($title=="")
            {
            	throw new Exception("��������� �� ������ ���� ������");
            }
            $description=htmlspecialchars($description);
            $steps=htmlspecialchars($steps);
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
            $this->_sql->call(
            	'AddItem', 
            	new ArrayObject(array(
                    $this->_errorOwnerID,
                    $this->_projectOwnerID,
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
        }   
        
        public function deleteReport($reportID) 
        {
            $id=(int)$reportID;
            $this->_sql->delete("ErrorReport","ID=$id");    
        }
        
        public function editReport($reportID,ErrorStatusENUM $errorStatus, $userID)
        {
            if ($errorStatus->check())
            {
                $errorStatusValue=$errorStatus->getValue();    
            }
            else
            {
                throw new Exception("�������� ������ ������");
            } 
            if ($this->checkIsExsist($reportID))
            {
                if ($this->checkIsProjectError($reportID))
                {
                    $report=$this->getReportByID($reportID);
                    if ($report["Status"]!=$errorStatusValue)
                    {
                        $reportID=(int)$reportID;
                        switch ($report["Status"])
                        {
                            case ErrorStatusENUM::CLOSED:
                                $pC=new ProjectsController();
                                if ($pC->isOwner($this->_errorOwnerID,$this->_projectOwnerID))
                                {
                                    $this->doEdit($reportID,$errorStatusValue,$userID);  
                                }
                                else
                                {
                                    throw new Exception("����� �� �������� CLOSED ����� ������������� �������� �������");
                                }
                                break;
                            case ErrorStatusENUM::ASSIGNED:
                                $repAss=new ReportsAssigment($reportID);
                                $repAsigmentRecord=$repAss->getByReportID($reportID);
                                if ($this->chekProjectOwnerOrReportOwner($reportID) || $repAsigmentRecord["UserID"]==$this->_errorOwnerID)
                                {
                                    $this->doEdit($reportID,$errorStatusValue,$userID);    
                                } 
                                else
                                {
                                    throw new Exception("����� �� �������� ASSIGNED ����� ������������� �������� ������� � ��������� ������, � ����� ���, ���� ��� �������� �����");
                                }
                                break;
                            default:
                                if ($this->chekProjectOwnerOrReportOwner($reportID))
                                {
                                    $this->doEdit($reportID,$errorStatusValue,$userID);
                                }
                                else
                                {
                                    throw new Exception("����� �� �������� $report[Status] ����� ������������� �������� ������� � ��������� ������");
                                }                           
                        }                        
                    }
                }
                else
                {
                    throw new Exception("������������� ����� �� ����������� �������� �������");
                }
            }
            else
            {
                throw new Exception("����� �� ������ �� ����������");
            }      
        }
        
        private function doEdit($reportID,$errorStatusValue,$userID)
        {
            $repAss=new ReportsAssigment($reportID);
            if ($errorStatusValue==ErrorStatusENUM::ASSIGNED)
            {
                $repAss->addAssigment($userID);
            }
            else
            {
                $repAss->deleteAssigment($userID);
            }   
            $this->_sql->query("UPDATE ErrorReport SET Status='$errorStatusValue' WHERE ID=$reportID");
        }
        
        /**
        * ��������� ��������� ������
        * 
        * @param int $reportID ID ������
        * @return int
        */
        private function getReportOwner($reportID)
        {
            $this->_sql->selFieldsWhere("ErrorReport","ID=$reportID","UserID");
            $res=$this->_sql->GetRows();
            return $res[0]["UserID"];
        }
        
        private function checkIsProjectError($reportID)
        {
            $reportID=(int)$reportID;
            $this->_sql->selFieldsWhere("ErrorReport","ID=$reportID","ProjectID");
            $projectID=$this->_sql->GetRows();
            $projectID=$projectID[0]["ProjectID"];
            return $projectID==$this->_projectOwnerID;
        }
        
        public function checkIsExsist($reportId)
        {
            $id=(int)$reportId;
            $countGroups=$this->_sql->countQuery("ErrorReport","ID=$id");
            return (Boolean)$countGroups;   
        }
        
        public function getReportsByProject($projectID,$from,$size)
        {
            $this->checkProject($projectID);  
            $this->useLimit($from,$size);
            $this->_sql->selAllWhere("ErrorReportsInfo","ProjectID=$projectID");
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
        
        public function countReportsByProject($projectID)
        {
            $this->checkProject($projectID);
            return $this->_sql->countQuery("ErrorReportsInfo","ProjectID=$projectID");
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
                    throw new Exception("������������ �� ����������. ������ ��������������� ������������ ��������� ����� �� �������");    
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
        
        public function getProjectOrdered($projectID,ErrorFieldsENUM $field, MySQLOrderEnum $direction,$from,$size)
        {
            $this->useOrder($field,$direction);
            $res=$this->getReportsByProject($projectID,$from,$size);
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
        	$this->_sql->selAllWhere("ErrorReportsInfo","ID=$reportID");
            $arr=$this->_sql->getTable();
            $this->normalizeBugReport($arr[0]);
            return $arr[0];
        }
        
        private function chekProjectOwnerOrReportOwner($reportID)
        {
            $id=(int)$id;
            $pC=new ProjectsController();
            return ($this->_errorOwnerID==$this->getReportOwner($reportID) || $this->_errorOwnerID==$pC->isOwner($this->_errorOwnerID,$this->_projectOwnerID));               
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
        			$reportData["PriorityLevel"]="������"; break;
        		case ErrorPriorityENUM::NORMAL:
        			$reportData["PriorityLevel"]="�������"; break;
         		case ErrorPriorityENUM::HIGH:
        			$reportData["PriorityLevel"]="������"; break;
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
        			$reportData["ErrorType"]="�����������"; break;
        		case ErrorTypeENUM::COSMETIC: 
        			$reportData["ErrorType"]="�������������"; break;
        		case ErrorTypeENUM::CRASH:
        			$reportData["ErrorType"]="����"; break;
        		case ErrorTypeENUM::ERROR_HANDLE: 
        			$reportData["ErrorType"]="����������"; break;
        		case ErrorTypeENUM::FUNCTIONAL:
        			$reportData["ErrorType"]="�������������"; break;
        		case ErrorTypeENUM::MAJOR: 
        			$reportData["ErrorType"]="������������"; break;
        		case ErrorTypeENUM::MINOR: 
        			$reportData["ErrorType"]="�������������"; break;
        		case ErrorTypeENUM::SETUP: 
        			$reportData["ErrorType"]="������ �����������"; break;
        	}
        	switch ($reportData["Status"])
        	{
        		case ErrorStatusENUM::IS_NEW:
        			$reportData["StatusN"]="�����"; break;
        		case ErrorStatusENUM::IDENTIFIED:
        			$reportData["StatusN"]="���������������"; break;
        		case ErrorStatusENUM::ASSESSED:
        			$reportData["StatusN"]="�����"; break;
        		case ErrorStatusENUM::RESOLVED:
        			$reportData["StatusN"]="�����"; break;
        		case ErrorStatusENUM::CLOSED:
        			$reportData["StatusN"]="������"; break;
        	}
        }
    }
?>