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
			$mes.="DATABASE: $this->_db";
			$mes.=$message;
			$this->message=nl2br($mes);
			$this->_dbObject=$dbObject;
		}
		
		public function getDbObject()
		{
			return $this->_dbObject;
		}
	}