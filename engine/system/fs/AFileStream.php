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
	 * Создает файл, и закрывает его.
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
	 * Отрывает файл.
	 * @param $rwa -- тип открытия.
	 */
	public function open($rwa = "r+")
	{
		return $this->_fileHandle = fopen($this->_current, $rwa);
	}
	
	/**
	 * Закрывает файл.
	 */
	public function close()
	{
		$this->_position = 0;
		return fclose($this->_fileHandle);
	}
	
	/**
	 * Получает размер файла.
	 */
	public function getLength()
	{
		return filesize($this->_current);
	}
	
}

?>