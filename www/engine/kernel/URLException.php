<?php
class URLException extends Exception
{
	const URL_BASE="UrlBase class: ";
	
	public $nodeLink;

	public function __construct($nodeLink,$message, $code=0)
	{
		$message=self::URL_BASE.$message;
		$this->nodeLink=$nodeLink;
		parent::__construct($message, $code);
	}
}