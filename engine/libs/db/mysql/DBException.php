<?php
	class DBException extends Exception
	{
		protected $_db;
		
		protected $_host;
		
		private $_dbObject;
		
		public function __construct(MySQLquery $dbObject, $message, $code=null)
		{
			parent::__construct($mes, $code);
			$this->message="";
			$this->_host=$dbObject->getServerName();
			$this->_db=$dbObject->getDBName();
			$mes="\n\rHOST: $this->_host\n\r";
			$mes.="DATABSE: $this->_db\n\r";
			$mes.=$message;
			$this->message=$mes."\n\r";
			$this->_dbObject=$dbObject;
		}
		
		public function getDbObject()
		{
			return $this->_dbObject;
		}
	}