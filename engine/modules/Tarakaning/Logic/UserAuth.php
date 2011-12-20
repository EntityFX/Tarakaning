<?php
    class UserAuth extends DBConnector
    {
        const TABLE_USER_AUTH = 'USER';
    	
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
            $this->_sql->selAllWhere(self::TABLE_USER_AUTH,"NICK='$login'");
            $res=$this->_sql->getTable();
            if ($res!=NULL)
            {
                $res=$res[0];
                if ($res["Active"]==false)
                {
                    throw new Exception("������������ �� �����������");
                }
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
