<?php
class FileStream extends AFileStream
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}
	
	/**
	 * Читает байт.
	 */
	public function readByte()
	{
		$byte = fread($this->_fileHandle, 1);
	}
	
	/**
	 * Пишет байт.
	 * @param byte $value - записываемый байт.
	 */
	public function writeByte($value) 
	{
		$res = fwrite($this->_fileHandle, $value);
		return $res;
	}
	
	/**
	 * Пишет массив байтов.
	 * @param array $valArray - массив байтов.
	 */
	public function write($valArray) 
	{
		foreach ($valArray as $key => $value) 
		{
			$this->writeByte($value);
		}
		return true;
	}

	/**
	 * Читает заданное количество байтов. 
	 * @param int $count - количество байтов.
	 */
	public function read($count) 
	{
		if ($count <=0 ) 
		{
			throw new Exception("The count is too short.");
		}
		$bytes = fread($this->_fileHandle, $count);
		return $bytes;
	}
}

?>