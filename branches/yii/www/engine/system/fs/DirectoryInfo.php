<?php
//require_once 'engine/system/fs/AFileSystemInfo.php';

class DirectoryInfo extends AFileSystemInfo
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}
	
	/**
	 * ��������� ���������� ������ � ������� ����������.
	 * @return int
	 */
	public function countFiles() 
	{
		$tmp = scandir($this->current);
		$count = 0;
		foreach ($tmp as $key => $value) 
		{
			if (is_file($this->current."/".$value)) 
			{
				$count++;
			};
		}
		return $count;
	}
	
	/**
	 * ��������� ���������� ���������� � ������� ����������
	 * @return int
	 */
	public function countDirs() 
	{
		$tmp = scandir($this->current);
		$count = 0;
		foreach ($tmp as $key => $value) 
		{
			if (is_dir($this->current."/".$value) && $value!="." && $value!="..") 
			{
				$count++;
			};
		}
		return $count;
	}
	
	/**
	 * ��������� ���������� �������� � ������� ����������. 
	 * @return ������������� ������
	 */
	public function countObjects() 
	{
		return count(scandir($this->current)-2);
	}
	
	/**
	 * ��������� �������� ����������, ������������ � ������ ����������
	 */
	public function getSubDirs() 
	{
		$tmp = scandir($this->current);
		foreach ($tmp as $key => $value) 
		{
			if (is_dir($this->current."/".$value) && $value!="." && $value!="..") 
			{
				$res[] = new DirectoryInfo($this->current."/".$value);
			}
		}
		return $res;
	}

	/**
	 * ��������� �������� ������, ������������ � ������� ����������.
	 */
	public function getFiles() 
	{
		$tmp = scandir($this->current);
		foreach ($tmp as $key => $value) 
		{
			if (is_file($this->current."/".$value) && $value!="." && $value!="..") 
			{
				$res[] = new FileInfo($this->current."/".$value);
			}
		}
		return $res;
	}
	
	/**
	 * ���������� �������, ������������� � ������ ����������.
	 */
	public function getObjects() //��������� ��� �� ���������? ���� ��������� � ���� �������������� �������!
	{
		$tmp = scandir($this->current);
		foreach ($tmp as $key => $value) 
		{
			if (is_file($this->current."/".$value) && $value!="." && $value!="..") 
			{
				$res[] = new FileInfo($this->current."/".$value);
			}
			if (is_dir($this->current."/".$value) && $value!="." && $value!="..") 
			{
				$res[] = new DirectoryInfo($this->current."/".$value);
			}
		}
		return $res;
	}
			
}