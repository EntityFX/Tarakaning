<?php

    require_once "engine/libs/mysql/MySQLConnector.php";
    
    require_once "engine/config/databaseConsts.php";
        
    class UsersController extends MySQLConnector {
        
        public function createUser($login,$password,$type=0,$name="",$surname="",$secondName="",$email="")            
        {
            $hash="";
            $fields=new ArrayObject(array(
                "NickName",
                "PasswordHash",
                "UserType",
                "Name",
                "Surname",
                "SecondName",
                "Email"
            ));
            if ($this->checkIfExsistLogin($login))
            {
                throw new Exception("Пользователь уже существует",0);
            }
            if (!self::checkMail($email))
            {
                throw new Exception("Неверный формат почты",1);
            }
            if (!self::checkPassword($password))
            {
                throw new Exception("Неверный формат пароля",2);
            }           
            $val=new ArrayObject(array(
                $login,
                md5(md5($password)."MOTPWBAH"),
                $type,
                htmlspecialchars($name,ENT_QUOTES),
                htmlspecialchars($surname,ENT_QUOTES),
                htmlspecialchars($secondName,ENT_QUOTES),
                $email
            ));
            $this->_sql->insert("Users",$val,$fields);
        }
        
        public function deleteUser($id)
        {
            $id=(int)$id;
            $this->_sql->delete("Users","UserID=$id");
        }
        
        public function changeUserType($id,$type)
        {
            $this->changeField($id,$type,"UserType");
        }
        
        public function activateUser($id)
        {
            $this->changeField($id,true,"Active"); 
        }
 
        private function changeField($id,$type,$fieldName)
        {
            $id=(int)$id;
            $type=(bool)$type;
            if ($this->checkIfExsist($id))    
            {
                $this->_sql->query("UPDATE Users SET $fieldName=$type WHERE UserID=$id");
            }
        }
        
        public function getAllUsers()
        {
            $this->_sql->selAll("Users");
            return $this->_sql->getTable();
        }
        
        public function getAllByFirstLetter($letter)
        {
            $letter=(string)$letter[0];
            $this->_sql->selAllWhere("Users","NickName Like '$letter%'");
            return $this->_sql->getTable();
        }        
        
        /**
        * Проверить существование логина
        * 
        * @param string $name Заголовок группы
        * @return bool
        */
        private function checkIfExsistLogin($login)
        {
            $login=mysql_escape_string($login);
            $countGroups=$this->_sql->countQuery("Users","NickName='$login'");
            return (Boolean)$countGroups;    
        }
        
        /**
        * Проверить существование лпо ID
        * 
        * @param int $name ID пользователя
        * @return bool
        */
        public function checkIfExsist($id)
        {
            $id=(int)$id;
            $countGroups=$this->_sql->countQuery("Users","UserID=$id");
            return (Boolean)$countGroups;    
        }        
        
        /**
        * Проверка формата почты
        * 
        * @param string $mail
        */
        public static function checkMail($mail)
        {
            if (preg_match("/^([a-zA-Z0-9]([a-zA-Z0-9\._-]*)@)([a-zA-Z0-9_-]+\.)*[a-z]{2,}$/",$mail)==1)
            {
                return true;    
            }
            else
            {
                return false;
            }
        }         
        
        public static function checkPassword($password)
        {
            if (strlen($password)>=7)    
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
