<?php
class UserAuth extends DBConnector
{
	public static $authTableName="AdminUsers";
	 
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
		$this->_sql->selAllWhere(self::$authTableName,"NickName='$login'");
		$res=$this->_sql->getTable();
		if ($res!=NULL)
		{
			$res=$res[0];
			if ($res["PasswordHash"]!=md5(md5($password)."MOTPWBAH"))
			{
				throw new Exception("Неверный пароль");
			}
			else if ($res["Active"]==false)
			{
				throw new Exception("Пользователь $login не активирован");
			}
			else
			{
				$res["EnterTime"]=date("d.m.Y G:i",time());
				$_SESSION[self::$authTableName]=$res;
			}
		}
		else
		{
			throw new Exception("Пользователь $login не найден");
		}
	}

	public function logOut()
	{
		unset($_SESSION[self::$authTableName]);
	}

	/**
	 * Проверка, активен ли пользователь
	 *
	 */
	public function isEntered()
	{
		if (isset($_SESSION[self::$authTableName]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getName()
	{
		return $_SESSION[self::$authTableName];
	}

	public function changePassword($oldPassword,$newPassword)
	{
		$uC=new UsersController();
		$newPassHash=$uC->changePassword($_SESSION[self::$authTableName]["UserID"], $oldPassword, $newPassword);
		$_SESSION[self::$authTableName]["PasswordHash"]=$newPassHash;
	}

	public function changeData($name,$surname,$secondName,$email)
	{
		$uC=new UsersController();
		$uC->changeData($_SESSION[self::$authTableName]["UserID"], $name, $surname, $secondName, $email);
		$this->refreshData();
	}

	public function refreshData()
	{
		$this->_sql->selAllWhere(self::$authTableName,"UserID='".$_SESSION[self::$authTableName]["UserID"]."'");
		$res=$this->_sql->getTable();
		$res=$res[0];
		if ($res!=NULL) $_SESSION[self::$authTableName]=$res;
	}

	public function checkUserType($type)
	{
		$type=(int)$type;
		return $_SESSION[self::$authTableName]["UserType"]==$type?true:false;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $langCode
	 * @throws Exception
	 * @return Zend_Http_Cookie
	 */
	public function setLanguage($langCode)
	{
		$langCode=(int)$langCode;
		$count=$this->_sql->countQuery("Locations","LANG_ID=$langCode");
		if ($count!=0)
		{
			$this->_sql->update(self::$authTableName, "UserID='".$_SESSION[self::$authTableName]["UserID"]."'", 
				new ArrayObject(array(
					"LANG_ID" => $langCode
				))
			);
			$_SESSION[self::$authTableName]["LANG_ID"]=$langCode;
		}
		else
		{
			throw new Exception("Язык с таким кодом не существует");
		}
		return $languageCookie;
	}
	
	public function getLanguage()
	{
		return $_SESSION[self::$authTableName]["LANG_ID"];
	}
}
?>
