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
                throw new Exception("Вы уже вошли в систему");
            }
            $login=mysql_escape_string($login);
            $this->_sql->selAllWhere(self::TABLE_USER_AUTH,"NICK='$login'");
            $res=$this->_sql->getTable();
            if ($res!=NULL)
            {
                $res=$res[0];
                if ($res["Active"]==false)
                {
                    throw new Exception("Пользователь не активирован");
                }
                if ($res["PasswordHash"]==md5(md5($password)."MOTPWBAH"))
                {
                    $_SESSION["user"]=$res;    
                }
                else
                {
                    throw new Exception("Неверный пароль");    
                }
            }
            else
            {
                throw new Exception("Пользователь не найден");
            }
        }
        
        public function logOut()
        {
            unset($_SESSION["user"]);    
        }
        
        /**
        * Проверка, активен ли пользователь
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
