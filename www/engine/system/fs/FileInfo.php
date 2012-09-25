<?php
//require_once 'engine/system/fs/AFileSystemInfo.php';

class FileInfo extends AFileSystemInfo 
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}

	/**
	 * ��������� ���������� �����.
	 * @return string
	 */
	public function getType() 
	{
		$parts = pathinfo($this->current);
		return $parts["extension"];
	}

	/**
	 * ��������� ������� �����.
	 * @return int
	 */
	public function getLength() 
	{
		return filesize($this->current);
	}
}