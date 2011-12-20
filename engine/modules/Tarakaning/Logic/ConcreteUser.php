<?php
    require_once "ProjectsController.php";
	require_once SOURCE_PATH.'engine/modules/Auth/Logic/UserAuth.php';
    
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
                $this->_authNamespace->data['DFLT_PROJ_ID']=$projectID;                    
            }
            else
            {
                throw new Exception("Проект не существует. Зверский хак!",4);
            }
        }
        
        public function deleteDefaultProject()
        {
            $this->_sql->query("
                UPDATE ".self::$authTableName." SET 
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
            $this->login=$resArray["NICK"];
            $this->name=$resArray["FRST_NM"];
            $this->secondName=$resArray["SECND_NM"];
            $this->surname=$resArray["LAST_NM"];
            $this->mail=$resArray["EMAIL"];
            $this->id=(int)$resArray["USER_ID"];
            $this->_passwordHash=$resArray["PASSW_HASH"]; 
            $this->defaultProjectID=$resArray["DFLT_PROJ_ID"];
        }
        
        public function getCurrentProject()
        {
        	//var_dump($this->_authNamespace->data["SelectedProject"]);
        	if ($this->_authNamespace->data[self::$authTableName]["SelectedProject"]==null)
        	{
        		$this->_authNamespace->data[self::$authTableName]["SelectedProject"]=$this->defaultProjectID;
        	}
        	return $this->_authNamespace->data[self::$authTableName]["SelectedProject"];
        }
        
        public function setCurrentProject($projectID)
        {
        	$this->_authNamespace->data[self::$authTableName]["SelectedProject"]=(int)$projectID;
        }
    }
?>
