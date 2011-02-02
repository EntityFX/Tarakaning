<?php
    require_once "ErrorStatusENUM.php";
    
    require_once "ErrorPriorityENUM.php"; 
    
    require_once "ErrorTypeENUM.php";
    
    require_once "ConcreteUser.php"; 
    
    require_once "ProjectsController.php"; 
    
    require_once "UsersController.php";
    
    require_once "SubscribesController.php";      
    
    class ErrorReportsController extends MySQLConnector
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
                else
                {
                    throw new Exception("У пользователя не задан проект по-умолчанию. Задайте конкретный проект");
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
                    if ($sub->isSubscribed($this->_errorOwnerID,(int)$projectID))
                    {
                        $this->_projectOwnerID=(int)$projectID;        
                    }
                    else
                    {
                        throw new Exception("Пользователь №".$this->_errorOwnerID." не подписан на проект");
                    }
                }
                else
                {
                    throw new Exception("Проект не существует. Нельзя присвоить несуществующему проекту отчёты об ошибках");
                }
            }
        }
        
        public function addReport(ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title="", $description="", $steps="")
        {
            $this->_sql->debugging=true;
            $title=htmlspecialchars($title);
            $description=htmlspecialchars($description);
            $steps=htmlspecialchars($steps);
            if ($priority->check())
            {
                $priorityValue=$priority->getValue();    
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
            var_dump(new ArrayObject(array(
                    $this->_errorOwnerID,
                    $this->_projectOwnerID,
                    $priorityValue,
                    $errorStatusValue,
                    time(),
                    $title,
                    $typeValue,
                    $description,
                    $steps    
                )));
            $this->_sql->insert("ErrorReport",
                new ArrayObject(array(
                    $this->_errorOwnerID,
                    $this->_projectOwnerID,
                    $priorityValue,
                    $errorStatusValue,
                    date("Y-m-d H:i:s"),
                    $title,
                    $typeValue,
                    $description,
                    $steps    
                )),
                new ArrayObject(array(
                    "UserID",
                    "ProjectID",
                    "PriorityLevel",
                    "Status",
                    "Time",
                    "Title",
                    "ErrorType",
                    "Description",
                    "StepsText"
                ))
            );
        }   
        
        public function deleteReport($reportID) 
        {
            $id=(int)$reportID;
            $this->_sql->delete("ErrorReport","ID=$id");    
        }
        
        public function editReport($reportID,ErrorStatusENUM $errorStatus)
        {
            if ($errorStatus->check())
            {
                $errorStatusValue=$errorStatus->getValue();    
            }
            else
            {
                throw new Exception("Неверный статус ошибки");
            } 
            if ($this->checkIsExsist($reportID))
            {
                if ($this->checkIsProjectError($reportID))
                {
                    $reportID=(int)$reportID;
                    if ($errorStatusValue==ErrorStatusENUM::ASSIGNED || $errorStatusValue==ErrorStatusENUM::CONFIRMED)
                    {
                        
                    }   
                    $this->_sql->query("UPDATE ErrorReport SET Status=$errorStatusValue WHERE ID=$reportID");
                }
                else
                {
                    throw new Exception("Редактируемый отчёт не принадлежит текущему проекту");
                }
            }
            else
            {
                throw new Exception("Отчёт об ошибке не существует");
            }      
        }
        
        private function checkIsProjectError($reportID)
        {
            $reportID=(int)$reportID;
            $this->_sql->selFieldsWhere("ErrorReport","ID=$reportID","ProjectID");
            $projectID=$this->_sql->GetRows();
            $projectID=$projectID[0]["ProjectID"];
            return $projectID==$this->_projectOwnerID;
        }
        
        private function checkIsExsist($reportId)
        {
            $id=(int)$reportId;
            $countGroups=$this->_sql->countQuery("ErrorReport","ID=$id");
            return (Boolean)$countGroups;   
        }
        
        public function getReportsByProject($projectID=NULL)
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
            $this->_sql->selAllWhere("ErrorReport","ProjectID=$projectID");
            return $this->_sql->getTable();    
        }
        
        public function getReportsByUser($userID=NULL)
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
                    $this->_sql->selAllWhere("ErrorReport","UserID=$userID");
                    $res=$this->_sql->getTable();
                }
            }
            return $res;
        }
        
        public function getAllReports()
        {
            $this->_sql->selAll("ErrorReport");
            return $this->_sql->getTable();
        }
    }
?>
