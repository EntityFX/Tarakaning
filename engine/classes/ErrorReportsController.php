<?php
    require_once "ErrorStatusENUM.php";
    
    require_once "ErrorPriorityENUM.php"; 
    
    require_once "ErrorTypeENUM.php";
    
    require_once "ConcreteUser.php"; 
    
    require_once "ProjectsController.php"; 
    
    require_once "UsersController.php";
    
    require_once "Subscribes.php";      
    
    class ErrorReportsController extends MySQLConnector
    {
        private $_errorOwnerID;
        
        private $_projectOwnerID;
        
        public function __construct($projectID=NULL,$ownerID=NULL)
        {
            $concreteUser=new ConcreteUser(); 
            if ($projectID==NULL)
            {
                $this->_errorOwnerID=$concreteUser->id;
                if ($concreteUser->defaultProjectID!=NULL)
                {
                    $this->_projectOwnerID=$concreteUser->defaultProjectID;
                }
                else
                {
                    throw new Exception("� ������������ �� ����� ������ ��-���������. ������� ���������� ������");
                }
            }
            else
            {
                if (ProjectsController::isProjectExists((int)$projectID))
                {
                    $sub=new Subscribes();
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
                    if ($sub->isSubscribed($this->_errorOwnerID,(int)$projectID))
                    {
                        $this->_projectOwnerID=(int)$projectID;        
                    }
                    else
                    {
                        throw new Exception("������������ �".$concreteUser->id." �� �������� �� ������");
                    }
                }
                else
                {
                    throw new Exception("������ �� ����������. ������ ��������� ��������������� ������� ������ �� �������");
                }
            }
        }
        
        public function addReport(ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title="", $description="", $steps="")
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
