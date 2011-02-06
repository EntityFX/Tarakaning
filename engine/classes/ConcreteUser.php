<?php
    require_once "ProjectsController.php";
    
    class ConcreteUser extends MySQLConnector
    {
        public $login;
        
        public $name;
        
        public $surname;
        
        public $secondName;
        
        public $mail;
        
        public $id;
        
        public $defaultProjectID;
        
        private $_passwordHash;
        
        public function __construct($id=NULL)
        {
            parent::__construct();
            session_start();
            if ($id==NULL)
            {
                if (isset($_SESSION["user"])) 
                {
                    $this->setData($_SESSION["user"]);
                }
                else
                {
                    throw new Exception("Выполните аутентификацию",5);
                }       
            }
            else
            {
                $id=(int)$id;
                $this->_sql->selAllWhere("Users","UserID=$id");
                $res=$this->_sql->getTable();
                if ($res!=NULL)
                {
                    $this->setData($res[0]);    
                }
                else
                {
                    throw new Exception("Пользователь не существует",0);
                }
            }
        }
        
        public function changePassword($oldPassword,$newPassword)
        {
            if ($this->_passwordHash==md5(md5($oldPassword)."MOTPWBAH"))
            {
                $passHash=md5(md5($newPassword)."MOTPWBAH");
                $id=$this->id;
                if (UsersController::checkPassword($newPassword))
                {
                    $this->_sql->query("UPDATE Users SET PasswordHash='$passHash' WHERE UserID=$id");
                }
                else
                {
                    throw new Exception("Неверный формат пароля",2);
                }    
            }
            else
            {
                throw new Exception("Неверный пароль",1);    
            } 
        }
        
        public function updateInfo()
        {
            $id=$this->id;
            if (!UsersController::checkMail($this->mail))
            {
                throw new Exception("Неверный формат почты",3);
            }
            $name=htmlspecialchars($this->name,ENT_QUOTES);
            $surname=htmlspecialchars($this->surname,ENT_QUOTES);
            $secondName=htmlspecialchars($this->secondName,ENT_QUOTES);
            $this->_sql->query("
                UPDATE Users SET 
                    Name='$name', 
                    Surname='$surname', 
                    SecondName='$secondName',
                    Email='$this->mail' 
                WHERE UserID=$id
            ");
        }
        
        public function setDefaultProject($projectID=NULL)
        {
            if ($projectID!=NULL)
            {
                $projectID=(int)$projectID;
            }
            $pContr=new ProjectsController();
            if ($pContr->isProjectExists($projectID))
            {
                $this->_sql->query("
                    UPDATE Users SET 
                        DefaultProjectID=$projectID 
                    WHERE UserID=$this->id
                ");
                $_SESSION["user"]["DefaultProjectID"]=$projectID;                    
            }
            else
            {
                throw new Exception("Проект не существует. Зверский хак!",4);
            }
        }
        
        public function deleteDefaultProject()
        {
            $this->_sql->query("
                UPDATE Users SET 
                    DefaultProjectID=NULL 
                WHERE UserID=$this->id
            ");             
        }
        
        /**
        * Устанавливает состояния полей из массива
        * 
        * @param array $resArray
        */
        private function setData($resArray)
        {
            $this->login=$resArray["NickName"];
            $this->name=$resArray["Name"];
            $this->secondName=$resArray["SecondName"];
            $this->surname=$resArray["Surname"];
            $this->mail=$resArray["Email"];
            $this->id=(int)$resArray["UserID"];
            $this->_passwordHash=$resArray["PasswordHash"]; 
            $this->defaultProjectID=$resArray["DefaultProjectID"];
        }
    }
?>
