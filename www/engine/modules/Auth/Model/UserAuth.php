<?php
class UserAuth extends DBConnector
{
	public static $authTableName="AdminUsers";
	
	protected $_authNamespace;
	 
	public function __construct()
	{
		parent::__construct();
		$this->_authNamespace=new Zend_Session_Namespace('AUTH');
	}

	public function logIn($login,$password)
	{
		if ($this->isEntered())
		{
			throw new Exception("�� ��� ����� � �������");
		}
		$slashedLogin = addslashes($login);
		$this->_sql->selAllWhere(self::$authTableName,"NICK='$slashedLogin'");
		$res=$this->_sql->getResultRows();
		if ($res!=NULL)
		{
			$res=$res[0];
			if ($res["PASSW_HASH"]!=md5(md5($password)."MOTPWBAH"))
			{
				throw new Exception("�������� ������");
			}
			else if ($res["ACTIVE"]==false)
			{
				throw new Exception("������������ $login �� �����������");
			}
			else
			{
				$res["EnterTime"]=date("d.m.Y G:i",time());
				Zend_Session::regenerateId();
				$this->_authNamespace->data[self::$authTableName]=$res;
			}
		}
		else
		{
			throw new Exception("������������ $login �� ������");
		}
	}

	public function logOut()
	{
		unset($this->_authNamespace->data[self::$authTableName]);
	}

	/**
	 * ��������, ������� �� ������������
	 *
	 */
	public function isEntered()
	{
		if (isset($this->_authNamespace->data[self::$authTableName]))
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
		return $this->_authNamespace->data[self::$authTableName];
	}

	public function changePassword($oldPassword,$newPassword)
	{
		$uC=new UsersOperation();
		$newPassHash=$uC->changePassword($this->_authNamespace->data[self::$authTableName]["USER_ID"], $oldPassword, $newPassword);
		$this->_authNamespace->data[self::$authTableName]["PASSW_HASH"]=$newPassHash;
	}

	public function changeData($name,$surname,$secondName,$email)
	{
		$uC=new UsersOperation();
		$uC->changeData($this->_authNamespace->data[self::$authTableName]["USER_ID"], $name, $surname, $secondName, $email);
		$this->refreshData();
	}

	public function refreshData()
	{
		$this->_sql->selAllWhere(self::$authTableName,"USER_ID='".$this->_authNamespace->data[self::$authTableName]["USER_ID"]."'");
		$res=$this->_sql->getResultRows();
		$res=$res[0];
		if ($res!=NULL) 
        {
            $enterData=$this->_authNamespace->data[self::$authTableName]['EnterTime'];
            $selectedProject=$this->_authNamespace->data[self::$authTableName]['SelectedProject'];
            $this->_authNamespace->data[self::$authTableName]=$res;
            $this->_authNamespace->data[self::$authTableName]['EnterTime']=$enterData;
            $this->_authNamespace->data[self::$authTableName]['SelectedProject']=$selectedProject;
        }
	}

	public function checkUserType($type)
	{
		$type=(int)$type;
		return $this->_authNamespace->data[self::$authTableName]["USR_TYP"]==$type?true:false;
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
			$this->_sql->update(self::$authTableName, "USER_ID='".$this->_authNamespace->data[self::$authTableName]["UserID"]."'", 
				new ArrayObject(array(
					"LANG_ID" => $langCode
				))
			);
			$this->_authNamespace->data[self::$authTableName]["LANG_ID"]=$langCode;
		}
		else
		{
			throw new Exception("���� � ����� ����� �� ����������");
		}
		return $languageCookie;
	}
	
	public function getLanguage()
	{
		return $this->_authNamespace->data[self::$authTableName]["LANG_ID"];
	}
}
?>
