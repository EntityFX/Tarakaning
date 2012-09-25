<?php
class FileStream extends AFileStream
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}
	
	/**
	 * ������ ����.
	 */
	public function readByte()
	{
		$byte = fread($this->_fileHandle, 1);
	}
	
	/**
	 * ����� ����.
	 * @param byte $value - ������������ ����.
	 */
	public function writeByte($value) 
	{
		$res = fwrite($this->_fileHandle, $value);
		return $res;
	}
	
	/**
	 * ����� ������ ������.
	 * @param array $valArray - ������ ������.
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
	 * ������ �������� ���������� ������. 
	 * @param int $count - ���������� ������.
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