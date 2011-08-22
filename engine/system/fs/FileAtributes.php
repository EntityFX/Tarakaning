<?php

class FileAtributes 
{
	
	/*
	 * fileatime — Gets last access time of file +
filectime — Gets inode change time of file +
filegroup — Gets file group +
fileinode — Gets file inode +
filemtime — Gets file modification time +
fileowner — Gets file owner +
fileperms — Gets file permissions +
is_executable — Tells whether the filename is executable +
is_readable — Tells whether a file exists and is readable +
is_writeable — Alias of is_writable +
	 */
	
	private $_current;

	public function __construct($filePath) 
	{
		$this->_current = $filePath;
	}
	
	/**
	 * Получение времени последенего доступа к файлу.
	 */
	public function getAccessTime() 
	{
		return fileatime($this->_current);
	}
	
	/**
	 * Получение времени последнего изменения что ли. хз
	 */
	public function getChangeTime() 
	{
		return filectime($this->_current);
	}
	
	/**
	 * Получение группы, к которой принадлежит файл
	 */
	public function getGroup() 
	{
		return filegroup($this->_current);
	}
	
	/**
	 * Получение inode
	 */
	public function getInode() 
	{
		return fileinode($this->_current);
	}
	
	/**
	 * Получение времени модификации файла
	 */
	public function getModificationTime() 
	{
		return filemtime($this->_current);
	}
	
	/**
	 * Получение владельца
	 */
	public function getOwner() 
	{
		return fileowner($this->_current);
	}
	
	/**
	 * Получение разрешений файла
	 */
	public function getPermissions() 
	{
		return fileperms($this->_current);
	}
	
	/**
	 * Получение информации о том, предназначен ли файл для чтения
	 */	
	public function isReadable() 
	{
		return is_readable($this->_current);
	}
	
	/**
	 * Получение информации о том, предназначен ли файл для записи 
	 */		
	public function isWriteable() 
	{
		return is_writeable($this->_current);
	}
	
	/**
	 * Получение информации о том, предназначен ли файл для запуска
	 */
	public function isExecutable() 
	{
		return is_executable($this->_current);
	}
}

?>