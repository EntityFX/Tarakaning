<?
/*if(is_dir("../fs")) die("file");
else 
die("not file");
ini_set('display_errors',1);
error_reporting(E_ALL);*/
require_once 'IDirectoryParenter.php';
require_once 'IFileExist.php';
require_once 'IFileSystemOperation.php';

require_once 'AFileSystemInfo.php';
require_once 'DirectoryInfo.php';
require_once 'FileInfo.php';
require_once 'File.php';

require_once 'Directory.php';
require_once 'AFileStream.php';
require_once 'FileStream.php';
require_once 'TextFile.php';

//die("ok");
try 
{
	$filePath = "sdf";
	var_dump(fileinode($filePath));
} 
catch (Exception $e) 
{
	die("<b> ERRORRISHE </b>".$e->getMessage());
}

/*
$name = "../../../engine/system/fs.fsd/";
echo dirname($name);*/
?>
