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
			throw new Exception(iconv("windows-1251", "utf-8","������ ����� �� ����������."));
			else
			require_once $classname; 
		}
		else
		{
			while(1)
			{
				$classname = "../".$classname;
				$i++;
				if($i > 20) {throw new Exception(iconv("windows-1251", "utf-8","���������� �������� �������� $i. <br />
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
            require_once self::$_modulePath.'Logic/'.$className.'.php'; 
        }
    }
    
    public static function LoadPageController($className,$moduleName=null)
    {
        if ($moduleName==null)   
        {
            require_once self::$_modulePath.'PageControllers/'.$className.'.php'; 
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
    
    public static function LoadSystem($package,$className)   
    {
            require_once SOURCE_PATH.'engine/system/'.$package.'/'.$className.'.php';    
    }
    
    public static function setModulePath($modulePath)
    {
        self::$_modulePath=$modulePath; 
    }
}
?>