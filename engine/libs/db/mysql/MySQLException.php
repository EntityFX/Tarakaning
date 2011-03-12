<?php

require_once 'DBException.php';

	class MySQLException extends DBException
	{
		const TYPE="MySQL";
		
		public function __construct(MySQLquery $dbObject, $message, $code=null)
		{
			$message="DB Type: ".self::TYPE.
			"\r\n".$message;
			$message="\r\n".$message."\r\n".
			self::TYPE." message: <span style=\"color:#a00; font-size: 15pt;\">"
			.mysql_error()."</span>\r\n";
			parent::__construct($dbObject, $message, $code);
		}
	}