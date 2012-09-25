<?php
//require_once 'engine/system/fs/DirectoryInfo.php';


class Dir extends DirectoryInfo implements IFileSystemOperation
{
	public function __construct($filePath) 
	{
		parent::__construct($filePath);
	}
	
	public function delete()//удаляет только если файл или папка. прочие типы хреней пока не удаляет
	{
		$tmp = $this->getObjects();
		if ($tmp != null)
		{
			foreach ($tmp as $key => $value) 
			{
				if ($value instanceof FileInfo)
				{					
					$delFile = new File($value->current);
					$delFile->delete();
				}
				if ($value instanceof DirectoryInfo)
				{
					$delDir = new Dir($value->current);
					$delDir->delete();
				}
			}
		}
		return rmdir($this->current);
	}
	
	public function rename($newname)
	{
		return rename($this->current, $newname);
	}
	
	public function moveTo($dest)
	{
		$r = $this->copy($dest);
		$this->delete();
		return $r;
	}
	
	public function copy($dest)
	{
		$this->createDirectory($dest);
		$tmp = $this->getObjects();
		if ($tmp != null)
		{
			foreach ($tmp as $key => $value) 
			{
				if ($value instanceof DirectoryInfo)
				{
					$copyDir = new Dir($value->current);
					$copyDir->copy($dest."/".$value->name);
				}
				if ($value instanceof FileInfo)
				{
					$copyDir = new Dir($value->current);
					$copyDir->copy($dest."/".$value->name);
				}
			}
		}
		return true;
	}
	
	/**
	 * Создание директории.
	 * @param $pathname
	 */
	public function createDirectory($pathname)
	{
		return mkdir($pathname);
	}
}