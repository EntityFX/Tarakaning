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
                throw new Exception("������������ ��� ����������",0);
            }
            if (!$this->checkMail($email))
            {
                throw new Exception("�������� ������ �����",1);
            }
            $val=new ArrayObject(array(
                $login,
                md5($password),
                $type,
                htmlspecialchars($name),
                htmlspecialchars($surname),
                htmlspecialchars($secondName),
                $email
            ));
            $this->_sql->insert("Users",$val,$fields);
        }
        
        public function deleteUser($id)
        {
            
        }
        
        public function changeUserType($id,$type)
        {
            
        }
        
        public function getAllUsers()
        {
            
        }
        
        /**
        * ��������� ������������� ������
        * 
        * @param string $name ��������� ������
        * @return bool
        */
        private function checkIfExsistLogin($login)
        {
            $countGroups=$this->_sql->countQuery("Users","NickName='$login'");
            return (Boolean)$countGroups;    
        }
        
        /**
        * �������� ������� �����
        * 
        * @param string $mail
        */
        private function checkMail($mail)
        {
            if (preg_match("/^[a-zA-Z0-9._-]+@([a-zA-Z0-9_-]+.)*[a-zA-Z]{2,}$/",$mail)==1)
            {
                return true;    
            }
            else
            {
                return false;
            }
        }         
        
        private function checkPassword($password)
        {
            
        }
    }
?>
