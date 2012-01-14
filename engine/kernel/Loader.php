<?php 

class Loader
{
    
    private static $_modulePath;  
    
	public static function loadClass($classname)
	{
        $root = $_SERVER["DOCUMENT_ROOT"];
		$scriptName = $_SERVER["SCRIPT_NAME"];
		$defaultScript = "/index.php";
		$i = 1;
		
		if ($scriptName == $defaultScript)
		{
			if(file_exists($classname)==FALSE)
			throw new Exception(iconv("windows-1251", "utf-8","Кажись файла не существует."));
			else
			require_once $classname; 
		}
		else
		{
			while(1)
			{
				$classname = "../".$classname;
				$i++;
				if($i > 20) {throw new Exception(iconv("windows-1251", "utf-8","Количество итераций достигло $i. <br />
				<b>classname:</b> $classname "));}
				if(file_exists($classname))
				{
					require_once $classname;
					break;
				}
			}
		}
	}
    
    public static function LoadModel($className,$moduleName=null)
    {
        if ($moduleName==null)   
        {
            require_once self::$_modulePath.'Model/'.$className.'.php'; 
        }
        else
        {
            require_once SOURCE_PATH.'engine/modules/'.$moduleName.'/Model/'.$className.'.php'; 
        }
    }
    
    public static function LoadPageController($className,$moduleName=null)
    {
        if ($moduleName==null)   
        {
            require_once self::$_modulePath.'PageControllers/'.$className.'.php'; 
        }
        else
        {
            require_once SOURCE_PATH.'engine/modules/'.$moduleName.'/PageControllers/'.$className.'.php';   
        }
    }
    
    public static function LoadModuleController($className,$moduleName=null)
    {
        if ($moduleName==null)   
        {
            require_once self::$_modulePath.$className.'.php'; 
        }
        else
        {
            require_once SOURCE_PATH.'engine/modules/'.$moduleName.'/'.$className.'.php';   
        }
    }
    
    public static function LoadControl($className,$moduleName=null)
    {
        if ($moduleName==null || self::$_modulePath==null)   
        {
            require_once self::$_modulePath.'Controls/'.$className.'.php'; 
        }
        else
        {
            //require_once SOURCE_PATH.'engine/system/controls'.$className.'.php';
        }
    }
    
    public static function LoadSystem($package,$className=null)   
    {
        if ($className!=null)
        {
            require_once SOURCE_PATH.'engine/system/'.$package.'/'.$className.'.php';
        }
        else
        {
            require_once SOURCE_PATH.'engine/system/'.$package.'.php'; 
        }  
    }
    
    public static function setModulePath($modulePath)
    {
        self::$_modulePath=$modulePath; 
    }
}
?>