<?php
require_once "AFileStream.php";
class TextFile extends AFileStream 
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}
	
	/**
	 * ������ ������.
	 */
	public function readLine() 
	{
		$ret = @fgets($this->_fileHandle);
		return $ret;
	}
	
	/**
	 * ������ ������
	 * @param string $value
	 */
	public function writeLine($value)
	{
		$ret = @fwrite($this->_fileHandle,$value."\n");
		return $ret;
	}

	/**
	 * ������ ������ �������
	 */
	public function readChar() 
	{
		$ret = @fgetc($this->_fileHandle);
		return $ret;
	}
	/**
	 * ������ ������ �������
	 * @param char $value
	 */
	public function writeChar($value)
	{
		$ret = @fwrite($this->_fileHandle,$value,1);
		return $ret;
	}
	
	/**
	 * �������� ����� ������.
	 */
	public function writeNewLine()
	{
		$ret = @fwrite($this->_fileHandle,"\n");
		return $ret;
	}
	
	/**
	 * ������ ���.
	 */
	public function readAll()
	{
		while(!feof($this->_fileHandle))
		{
			$str .= @fgets($this->_fileHandle);
		}
		return $str;
	}
}
?>