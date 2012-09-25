<?php
require_once "AFileStream.php";
class TextFile extends AFileStream 
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}
	
	/**
	 * Чтение строки.
	 */
	public function readLine() 
	{
		$ret = @fgets($this->_fileHandle);
		return $ret;
	}
	
	/**
	 * Запись строки
	 * @param string $value
	 */
	public function writeLine($value)
	{
		$ret = @fwrite($this->_fileHandle,$value."\n");
		return $ret;
	}

	/**
	 * Чтение одного символа
	 */
	public function readChar() 
	{
		$ret = @fgetc($this->_fileHandle);
		return $ret;
	}
	/**
	 * Запись одного символа
	 * @param char $value
	 */
	public function writeChar($value)
	{
		$ret = @fwrite($this->_fileHandle,$value,1);
		return $ret;
	}
	
	/**
	 * Записать новую строку.
	 */
	public function writeNewLine()
	{
		$ret = @fwrite($this->_fileHandle,"\n");
		return $ret;
	}
	
	/**
	 * Читать все.
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