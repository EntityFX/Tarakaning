<?php
require_once 'IFileExist.php';
class AFileStream implements IFileExist
{
	protected $_current;
	protected $_fileHandle;
	
	public function __construct($filePath) 
	{
		$this->_current = $filePath;
		
	}
	public function isExist()
	{
		if(file_exists($this->current)) return true;
		else return false;
	}
	
	/**
	 * ������� ����, � ��������� ���.
	 */
	public function createFile()
	{
		if (!$this->isExist())
		{
			$this->open("w");
			$this->close();
		}
	}
	
	/**
	 * �������� ����.
	 * @param $rwa -- ��� ��������.
	 */
	public function open($rwa = "r+")
	{
		$this->_fileHandle = fopen($this->_current, $rwa);
		if ($this->_fileHandle!==false)
		{
			return $this->_fileHandle;
		}
		else
		{
			throw new Exception("Can't open file '".$filePath."'");
		}
	}
	
	/**
	 * ��������� ����.
	 */
	public function close()
	{
		$this->_position = 0;
		return fclose($this->_fileHandle);
	}
	
	/**
	 * �������� ������ �����.
	 */
	public function getLength()
	{
		return filesize($this->_current);
	}
	
}

?>