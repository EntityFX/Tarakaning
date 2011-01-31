<?php
    require_once "ErrorStatusENUM.php";
    
    require_once "ErrorPriorityENUM.php"; 
    
    require_once "ErrorTypeENUM.php";
    
    require_once "ConcreteUser.php"; 
    
    require_once "ProjectsController.php"; 
    
    require_once "UsersController.php"; 
    
    class ErrorReportsController extends MySQLConnector
    {
        private $_errorOwnerID;
        
        private $_projectOwnerID;
        
        public function __construct($projectID=NULL,$ownerID=NULL)
        {
            if ($projectID==NULL)
            {
                $concreteUser=new ConcreteUser();
                if ($ownerID==NULL)    
                {
                    $this->_errorOwnerID=$concreteUser->id;
                }
                else
                {
                    $usersController=new UsersController();
                    if ($usersController->checkIfExsist((int)$ownerID))
                    {
                        $this->_errorOwnerID=$ownerID;    
                    }
                    else
                    {
                        throw new Exception("Обнаружена попытка назначить отчёт несуществующему пользователю");
                    }
                }
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
                if (ProjectsController::isProjectExists((int)$projectID))
                {
                    $this->_projectOwnerID=(int)$projectID;
                            
                }
                else
                {
                    throw new Exception("Проект не существует. Нельзя присвоить несуществующему проекту отчёты об ошибках");
                }
            }
        }
        
        public function addReport($projectId, ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title="", $description="", $steps="")
        {
            $title=htmlspecialchars($title);
            $description=htmlspecialchars($description);
            $steps=htmlspecialchars($steps);
        }   
        
        public function deleteReport($reportID) 
        {
            
        }
        
        public function getReportsByProject()
        {
            
        }
        
        public function getAllReports()
        {
            
        }
    }
?>
