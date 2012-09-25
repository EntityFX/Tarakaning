<?php
//require_once 'engine/system/fs/FileInfo.php';

class File extends FileInfo implements IFileSystemOperation
{

	public function __construct($filePath)
	{
		parent::__construct($filePath);
	}
	
	public function delete()
	{
		return unlink($this->current);
	}
	
	public function rename($newname)
	{
		return rename($this->current, $newname);
	}
	
	public function moveTo($dest)
	{
		return rename($this->current, $newname);//отлично приспособлен для этой функции
	}
	
	public function copy($dest)
	{
		return copy($this->current, $dest);
	}
}