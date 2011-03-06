<?
/**
* ���� � ������� SmartyExst. ��������� Smarty �������� �����������.
* @package kernel
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * ���������� ��������� ��� Smarty
    * @filesource config/smartyConsts.php 
    */  
    require_once "./config/smartyConsts.php";
    
    /**
    * ���������� Smarty
    * @filesource engine/libs/smarty/Smarty.class.php 
    */    
    require_once SOURCE_PATH."engine/libs/smarty/Smarty.class.php";
    
    
    /**
    * ��������� Smarty �������� �����������
    * @package kernel
    * @author Solopiy Artem
    * @final 
    */     
    final class SmartyExst extends Smarty
    {
        public function __construct()
        {
            $this->template_dir=SmartyConsts::TEMPLATES_DIR;
            $this->cache_dir=SmartyConsts::CACHE_DIR;
            $this->compile_dir=SmartyConsts::COMPILE_DIR;   
        }
    }
?>