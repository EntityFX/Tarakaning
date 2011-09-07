<?php
    require_once "ProjectsController.php";
    
    class ConcreteUser extends UserAuth
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
            if ($id==NULL)
            {
                $userData=$this->getName();
            	if ($userData!=null) 
                {
                    $this->setData($userData);
                }
                else
                {
                    throw new Exception("Выполните аутентификацию",5);
                }       
            }
            else
            {
            	$userOperation=new UsersOperation();
            	$userData=$userOperation->getById($id);
                if ($userData!=NULL)
                {
                    $this->setData($userData);    
                }
                else
                {
                    throw new Exception("Пользователь не существует",0);
                }
            }
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
                $u=new SubscribesController();
                if ($u->isSubscribed($this->id,$projectID) || $pContr->getOwnerID($projectID)==$this->id)
                {
                    $this->_sql->query("
                        UPDATE Users SET 
                            DefaultProjectID=$projectID 
                        WHERE UserID=$this->id
                    ");
                }
                else
                {
                    throw new Exception("Пользователь не подписан на проект");
                }
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
