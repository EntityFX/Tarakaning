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
        
        public function deleteAssigment()
        {
            if ($this->checkByReport($this->_errorReportID))            
            {
                $rep=$this->_errorReportID;
                $this->_sql->delete("ReportsUsersHandling","ReportID=$rep");
            }
        }
        
        public function getByReportID($reportID)
        {
            $rep=$this->_errorReportID;            
            $res=$this->_sql->selAllWhere("ReportsUsersHandling","ReportID=$rep");
            return $res[0];
        }
        
        private function checkByReport($reportId)
        {
            $reportId=(int)$reportId;
            $userID=(int)$userID;
            return $this->_sql->countQuery("ReportsUsersHandling","ReportID=$reportId")==1;
        }
        
        private function checkIfExsist($assigmentId)
        {
            
        }
    }
?>
