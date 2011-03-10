<?php

require_once 'DBException.php';

	class MySQLException extends DBException
	{
		const TYPE="MySQL";
		
		public function __construct(MySQLquery $dbObject, $message, $code=null)
		{
			$message="DB Type: ".self::TYPE."<br />$message";
			parent::__construct($dbObject, $message, $code);
		}
	}