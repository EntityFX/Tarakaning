<?php
class UsersOperation extends DBConnector
{
	 
	private static $authTableName=UserAuth;

	public function __construct()
	{
		parent::__construct();
		self::$authTableName=UserAuth::$authTableName;
	}

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
                if (preg_match("/^[a-zA-Z][a-zA-Z0-9_\-\.]*$/", $login)!=1)
                {
                	throw new Exception("Логин не должен содержать спецсимволы или быть пустым",0);
                }
                if ($this->checkIfExsistLogin($login))
                {
                	throw new Exception("Пользователь уже существует",0);
                }
                if ($email!="")
                {
                	if (!self::checkMail($email))
                	{
                		throw new Exception("Неверный формат почты",1);
                	}
                }
                if (!self::checkPassword($password))
                {
                	throw new Exception("Пароль должен быть не менее 7 символов (для безопасности)",2);
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
                $this->_sql->insert(self::$authTableName,$val,$fields);
                $resource=$this->_sql->query("SELECT LAST_INSERT_ID() as ID ");
                $res=$this->_sql->GetRows($resource);
                return (int)$res[0]["ID"];
	}

	public function deleteUser($id)
	{
		$id=(int)$id;
		$usr=$this->getById($id);
		if ($usr!=null)
		{
			if ($usr["NickName"]!="admin")
			{
				$this->_sql->delete(self::$authTableName,"UserID=$id");
			}
			else
			{
				throw new Exception("Can't delete admin");
			}
		}
	}

	public function changeUserType($id,$type)
	{
		$this->changeField($id,$type,"UserType");
	}

	public function activateUser($id)
	{
		$this->changeField($id,true,"Active");
	}
	
	public function diactivateUser($id)
	{
		$this->changeField($id,false,"Active");
	}

	private function changeField($id,$type,$fieldName)
	{
		$id=(int)$id;
		$type=(bool)$type;
		if ($this->checkIfExsist($id))
		{
			$this->_sql->update(self::$authTableName, "UserID=$id",
			new ArrayObject(array(
			$fieldName => $type
			))
			);
		}
	}

	public function getAllUsers()
	{
		$this->_sql->setOrder(
		new UsersOrderFields(UsersOrderFields::NICK_NAME),
		new MySQLOrderENUM(MySQLOrderENUM::ASC)
		);
		$this->_sql->selAll(self::$authTableName);
		$this->_sql->clearOrder();
		return $this->_sql->getTable();
	}

	public function getAllByFirstLetter($letter)
	{
		$letter=(string)$letter[0];
		$this->_sql->selAllWhere(self::$authTableName,"NickName Like '$letter%'");
		return $this->_sql->getTable();
	}

	public function getById($id)
	{
		$id=(int)$id;
		$this->_sql->selAllWhere(self::$authTableName,"UserID=$id");
		$arr=$this->_sql->getTable();
		return $arr[0];
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
		$countGroups=$this->_sql->countQuery(self::$authTableName,"NickName='$login'");
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
		$countGroups=$this->_sql->countQuery(self::$authTableName,"UserID=$id");
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

	public function changePassword($id,$oldPassword,$newPassword)
	{
		if (!self::checkPassword($newPassword))
		{
			throw new Exception("Пароль должен быть не менее 7 символов (для безопасности)",2);
		}
		$newPasswordHash=md5(md5($newPassword)."MOTPWBAH");
		$usr=$this->getById($id);
		if ($usr!=null)
		{
			if (md5(md5($oldPassword)."MOTPWBAH")!=$usr["PasswordHash"])
			{
				throw new Exception("Старый пароль неверный",2);
			}
			else
			{
				$id=(int)$id;
				$this->_sql->update(
					self::$authTableName,
            		"UserID=$id", 
					new ArrayObject(array(
            				"PasswordHash" => $newPasswordHash
					))
				);
			}
		}
		return $newPasswordHash;
	}
	
	public function setRandomPassword($id,$length)
	{
		$usr=$this->getById($id);
		if ($usr!=null)
		{
			$newPassword="";
			mt_srand(time());
			for($it=0;$it<$length;$it++)
			{
				switch (mt_rand(0,2))
				{
					case 0:
						$newPassword.=chr(mt_rand(0x61,0x7A));
						break;
					case 1:
						$newPassword.=chr(mt_rand(0x41,0x5A));
						break;
					case 2:
						$newPassword.=chr(mt_rand(0x30,0x39));
						break;
				}
			}
			$newPasswordHash=md5(md5($newPassword)."MOTPWBAH");
			$id=(int)$id;
			$this->_sql->update(
				self::$authTableName,
	            		"UserID=$id", 
				new ArrayObject(array(
	            			"PasswordHash" => $newPasswordHash
				))
			);
		}
		return $newPassword;	
	}

	public function changeData($id,$name,$surname,$secondName,$email)
	{
		if ($email!="")
		{
			if (!self::checkMail($email))
			{
				throw new Exception("Неверный формат почты",1);
			}
		}
		$id=(int)$id;
		$this->_sql->update(
			self::$authTableName,
            "UserID=$id", 
			new ArrayObject(array
			(
            		"Name" => htmlspecialchars($name),
            		"Surname" => htmlspecialchars($surname),
            		"Secondname" => htmlspecialchars($secondName),   
            		"Email" => htmlspecialchars($email)            	         	
			))
		);
	}

}

class UsersOrderFields extends AEnum
{
	const NICK_NAME="NickName";
}
?>
