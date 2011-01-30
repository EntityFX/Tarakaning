<?php
    class UserAuth extends MySQLConnector
    {
        public function __construct()
        {
            parent::__construct();
            session_start();    
        }
        
        public function logIn($login,$password)
        {
            if ($this->isEntered())
            {
                throw new Exception("�� ��� ����� � �������");
            }
            $login=mysql_escape_string($login);
            $this->_sql->selAllWhere("Users","NickName='$login'");
            $res=$this->_sql->getTable();
            if ($res!=NULL)
            {
                $res=$res[0];
                if ($res["PasswordHash"]==md5(md5($password)."MOTPWBAH"))
                {
                    $_SESSION["user"]=$res;    
                }
                else
                {
                    throw new Exception("�������� ������");    
                }
            }
            else
            {
                throw new Exception("������������ �� ������");
            }
        }
        
        public function logOut()
        {
            unset($_SESSION["user"]);    
        }
        
        /**
        * ��������, ������� �� ������������
        * 
        */
        public function isEntered()
        {
            if (isset($_SESSION["user"]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
?>