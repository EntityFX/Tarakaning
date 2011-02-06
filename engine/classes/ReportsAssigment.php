<?php
    require_once 'engine/libs/mysql/MySQLConnector.php';

    class ReportsAssigment extends MySQLConnector
    {
        private $_errorReportID;
        
        public function __construct($errorReportID)    
        {
            parent::__construct();
            $this->_errorReportID=(int)$errorReportID;
            $errController=new ErrorReportsController();
            if ($errController->checkIsExsist($errorReportID))
            {
                
            }
            else
            {
                throw new Exception("Нельзя назначать для несуществующей ошибки");
            }
        }
        
        public function addAssigment($userID)
        {
            $userController=new UsersController();
            if ($userController->checkIfExsist($userID))
            {
                try
                {
                    $this->_sql->insert(
                        "ReportsUsersHandling",
                        new ArrayObject(array(
                            $this->_errorReportID,
                            (int)$userID    
                        )),
                        new ArrayObject(array(
                            "ReportID",
                            "UserID"
                        ))
                    );
                }
                catch(Exception $e)
                {
                    
                }
            }
            else
            {
                throw new Exception("Нельзя назначать ошибку несуществующему пользователю");
            }
        }
        
        public function deleteAssigment($userID)
        {
            if ($this->checkByReportAndUser($this->_errorReportID,$userID))            
            {
                $userID=(int)$userID;
                $rep=$this->_errorReportID;
                $this->_sql->delete("ReportsUsersHandling","ReportID=$rep AND UserID=$userID");
            }
        }
        
        private function checkByReportAndUser($reportId,$userID)
        {
            $reportId=(int)$reportId;
            $userID=(int)$userID;
            return $this->_sql->countQuery("ReportsUsersHandling","ReportID=$reportId AND UserID=$userID")==1;
        }
        
        private function checkIfExsist($assigmentId)
        {
            
        }
    }
?>
