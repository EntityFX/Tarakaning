<?php
//require_once 'engine/system/fs/AFileSystemInfo.php';

class DirectoryInfo extends AFileSystemInfo
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}
	
	/**
	 * Получение количества файлов в текущей директории.
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
	 * Получение количества директорий в текущей директории
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
	 * Получение количества объектов в текущей директории. 
	 * @return ассоциативный массив
	 */
	public function countObjects() 
	{
		return count(scandir($this->current)-2);
	}
	
	/**
	 * Получение объектов директорий, содержащихся в данной директории
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
	 * Получение объектов файлов, содержащихся в текущей директории.
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
	 * Возвращает объекты, содержащиейся в данной директории.
	 */
	public function getObjects() //смешанное что ли присылать? надо присылать в виде ассоциативного массива!
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