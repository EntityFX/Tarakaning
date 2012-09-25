<?
/**
* Файл с классом SmartyExst. Расширяет Smarty дополняя настройками.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * Подключает константы для Smarty
    * @filesource config/smartyConsts.php 
    */  
    require_once SOURCE_PATH."engine/config/smartyConsts.php";
    
    /**
    * Подключает Smarty
    * @filesource engine/libs/smarty/Smarty.class.php 
    */    
    require_once SOURCE_PATH."engine/libs/smarty/Smarty.class.php";
    
    require_once SOURCE_PATH."engine/system/language/Language.php";
    
    
    /**
    * Расширяет Smarty дополняя настройками
    * @package kernel
    * @author Solopiy Artem
    * @final 
    */     
    class SmartyExst extends Smarty
    {
        const LANGUAGE_CONFIG_DIRECTORY="engine/config/languages/";
    	
    	private $langCode=false;
    	
    	public function __construct()
        {
            parent::__construct(); 
            $this->setTemplateDir(SOURCE_PATH.SmartyConsts::TEMPLATES_DIR);
            $this->setCacheDir(SOURCE_PATH.SmartyConsts::CACHE_DIR);
            $this->setCompileDir(SOURCE_PATH.SmartyConsts::COMPILE_DIR); 
        }
        
        public function fetch($template, $cache_id = null, $compile_id = null, $parent = null, $display = false)
        {
        	if ($this->langCode!==false)
        	{
        		$languagePath=SOURCE_PATH.'/'.self::LANGUAGE_CONFIG_DIRECTORY.Language::getLangName($this->langCode).".ini";
        		$this->configLoad($languagePath);
        		$this->assign($languagePath,"LANGUAGE");
        	}
        	return parent::fetch($template,$cache_id,$compile_id,$parent,$display);
        }
        
        public function setUseLanguage($langCode)
        {
        	if (is_numeric($langCode) || is_bool($langCode))
        	{
        		$this->langCode=$langCode;
        	}
        }
    }
?>
