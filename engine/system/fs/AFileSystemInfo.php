<?php
//require_once 'engine/system/fs/IFileExist.php';


class AFileSystemInfo implements IFileExist, IDirectoryParenter
{
	public $name;
	public $path;
	public $current;
	public $creationTime;
	
	public function __construct($filePath)
	{
		$this->current = $filePath;
		if (!$this->isExist($filePath))
		{
			throw new Exception("There is no such element");
		}
		$this->name = basename($filePath);
		$this->path = dirname($filePath);
		
		if (is_dir($filePath))
		{
			substr($filePath, -1) == "/" ? $filePath = substr($filePath, 0,-1) : $filePath = $filePath;
		}
	}
	
	/**
	 * Получение имени файла.
	 * @return string
	 */
	public function getName() 
	{
		return $this->name;
	}
	
	public function isExist()
	{
		if(file_exists($this->current)) return true;
		else return false;
	}
	
	/**
	 * Получение пути к файлу.
	 * @return string
	 */
	public function getFullPath() 
	{
		return $this->path;
	}
	
	public function getAttributes() 
	{
		;
	}
	
	/**
	 * Получение родительской директории
	 */
	public function getParentDirectory()
	{
		return $this->path;
	}
	
	/**
	 * Получение имени родительской директории.
	 */
	public function getParentDirectoryName()
	{
		return basename($this->path);
	}
	
}