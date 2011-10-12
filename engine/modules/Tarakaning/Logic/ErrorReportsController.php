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
                            throw new Exception("Пользователь не существует. Нельзя несуществующему пользователю отставлять отчёт об ошибках");
                        }
                    }
                    if ($sub->isSubscribed($this->_errorOwnerID,(int)$projectID) || $this->_errorOwnerID==$projectsController->getOwnerID((int)$projectID))
                    {
                        $this->_projectOwnerID=(int)$projectID;        
                    }
                    else
                    {
                        throw new Exception("Пользователь №".$this->_errorOwnerID." не подписан на проект или не является его владельцем");
                    }
                }
                else
                {
                    throw new Exception("Проект не существует. Нельзя присвоить несуществующему проекту отчёты об ошибках");
                }
            }
        }
        
        public function addReport(ItemDBKindENUM $kind, ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title, $description="", $steps="", $assignedTo=null)
        {
            $title=htmlspecialchars($title);
            if ($title=="")
            {
            	throw new Exception("Заголовок не должен быть пустым");
            }
            if ($kind->check())
            {
            	$kindValue=(string)$kind->getValue();  
            }
            else
            {
            	throw  new Exception("Неверный тип ошибки");
            }
            if ($priority->check())
            {
                $priorityValue=(string)$priority->getValue();    
            }
            else
            {
                throw new Exception("Неверный приоритет ошибки");
            }
            if ($errorStatus->check())
            {
                $errorStatusValue=$errorStatus->getValue();    
            }
            else
            {
                throw new Exception("Неверный статус ошибки");
            } 
            if ($type->check())
            {
                $typeValue=$type->getValue();    
            }
            else
            {
                throw new Exception("Неверный формат ошибки");
            }
            $description=nl2br(htmlspecialchars($description));
            $steps=nl2br(htmlspecialchars($steps));
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
         * Редактировать статус задачи
         * @param $reportID int ID отчёта
         * @param $errorStatusErrorStatusENUM Новый статус
         * @param $userID int Текущий юзер
         */
        public function editReport($reportID,ErrorStatusENUM $newStatus, $userID)
        {
            if ($newStatus->check())
            {
                $newStatusValue=$newStatus->getValue();    
            }
            else
            {
                throw new Exception("Неверный статус ошибки");
            }
			$report=$this->getReportByID($reportID);
			if ($report!=null)
			{
				$currentStatusValue=$report["Status"];
				if ($currentStatusValue==$newStatusValue) return false;
                $statusesArray=$newStatus->getNumberedKeys();
                $currentValueKey=array_search($currentStatusValue,$statusesArray);
                $newValueKey=array_search($newStatusValue, $statusesArray);
				if ($this->canEditReport($reportID) && ($newValueKey-$currentValueKey)<=1)
                {
                	$editFlag=false;
                	if ($currentStatusValue!=ErrorStatusENUM::CLOSED)
                	{
                		if ($currentStatusValue==ErrorStatusENUM::RESOLVED)
                		{
                			$editFlag=$this->canClose($reportID);
                		}
                		else 
                		{
                			$editFlag=true;
                		}
                	}
                	else if ($userID==$report["UserID"])
                	{
                		$editFlag=true;
                	}
                	if ($editFlag)
                	{
                		$this->_sql->update(
                			"ErrorReport", 
                			"ID=$reportID", 
                			new ArrayObject(array(
                				"Status" => $newStatusValue
                			))
                		);
                		return true;
                	}
                }
			}
        }
        
        /**
        * Получиить владельца отчёта
        * 
        * @param int $reportID ID отчёта
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
            $this->_sql->selAllWhere("ErrorReportsInfo","ProjectID=$projectID $kindExpression");
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
            return $this->_sql->countQuery("ErrorReportsInfo","ProjectID=$projectID $kindExpression");
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
                    throw new Exception("Проект не существует. Нельзя получить список ошибок по несуществующему проекту");    
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
                    throw new Exception("Пользователь не существует. Нельзя несуществующему пользователю оставлять отчёт об ошибках");    
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
        
        public function canEditReport($reportID)
        {
        	$user=$this->_errorOwnerID;
        	$isOwnerORAssigned=$this->_sql->countQuery("ErrorReport","ID=$reportID AND (UserID=$user OR AssignedTo=$user)");
        	$pC=new ProjectsController();
            return ($isOwnerORAssigned !=0) || $this->_errorOwnerID==$pC->isOwner($this->_errorOwnerID,$this->_projectOwnerID);
        }
        
        public function canClose($reportID)
        {
        	$pC=new ProjectsController();
        	return $this->_errorOwnerID==$this->getReportOwner($reportID) || $this->_errorOwnerID==$pC->isOwner($this->_errorOwnerID,$this->_projectOwnerID);
        }
        
        /**
         * 
         * Нормализует информацию отчёта об ошибке
         */
        private function normalizeBugReport(&$reportData)
        {
        	switch ($reportData["PriorityLevel"])
        	{
        		case ErrorPriorityENUM::MINIMAL:
        			$reportData["PriorityLevel"]="Низкий"; break;
        		case ErrorPriorityENUM::NORMAL:
        			$reportData["PriorityLevel"]="Обычный"; break;
         		case ErrorPriorityENUM::HIGH:
        			$reportData["PriorityLevel"]="Важный"; break;
        	}
        	switch ($reportData["Kind"])
        	{
        		case ItemKindENUM::DEFECT:
        			$reportData["KindN"]="Дефект"; break;
        		case ItemKindENUM::TASK:
        			$reportData["KindN"]="Задача"; break;
        	}
        	switch ($reportData["ErrorType"])
        	{
        		case ErrorTypeENUM::BLOCK: 
        			$reportData["ErrorType"]="Блокирующая"; break;
        		case ErrorTypeENUM::COSMETIC: 
        			$reportData["ErrorType"]="Косметическая"; break;
        		case ErrorTypeENUM::CRASH:
        			$reportData["ErrorType"]="Крах"; break;
        		case ErrorTypeENUM::ERROR_HANDLE: 
        			$reportData["ErrorType"]="Исключение"; break;
        		case ErrorTypeENUM::FUNCTIONAL:
        			$reportData["ErrorType"]="Функциональня"; break;
        		case ErrorTypeENUM::MAJOR: 
        			$reportData["ErrorType"]="Значительная"; break;
        		case ErrorTypeENUM::MINOR: 
        			$reportData["ErrorType"]="Неначительная"; break;
        		case ErrorTypeENUM::SETUP: 
        			$reportData["ErrorType"]="Ошибка инсталляции"; break;
        	}
        	switch ($reportData["Status"])
        	{
        		case ErrorStatusENUM::IS_NEW:
        			$reportData["StatusN"]="Новый"; break;
        		case ErrorStatusENUM::IDENTIFIED:
        			$reportData["StatusN"]="Идентифицирован"; break;
        		case ErrorStatusENUM::ASSESSED:
        			$reportData["StatusN"]="Оценён"; break;
        		case ErrorStatusENUM::RESOLVED:
        			$reportData["StatusN"]="Решён"; break;
        		case ErrorStatusENUM::CLOSED:
        			$reportData["StatusN"]="Закрыт"; break;
        	}
        }
    }
?>