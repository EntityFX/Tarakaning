<?php
require_once 'IPageController.php';

require_once 'URLBase.php';

require_once 'SinglePage.php';

require_once 'Error.php';

    abstract class ModuleController extends URLBase
    {
       
        const XML_CONFIG_NAME='module.config.xml';
    	
    	final function __construct(&$initData)
        {
            parent::__construct($initData);
            $this->initializePages();
        }
        
    	protected function loadPages($pages)
		{
			$pageInfo=null;
			$url=$this->getModuleURL();
			foreach($pages as $key => $value)
			{
				if ($value["url"]==$url || $value["url"]==null) 
				{
					$pageInfo=array('class' => $key,'page' => $value);
					break;
				}
			}
			if ($pageInfo!=null)
			{
				require_once "engine/modules/".$this->_moduleType."/PageControllers/".$pageInfo["class"].".php";
				switch($pageInfo["page"]["type"])
				{
					case "HTML":
						$page=new $pageInfo["class"]($this,$pageInfo["class"].".tpl");
						break;
					case "SINGLE":
						$loginPage=new $pageInfo["class"]($this);
						break;
					case "JSON":
						$loginPage=new $pageInfo["class"]($this);
						break;
					default:
						throw new Exception("module.config.xml: illegal attribute value");
				}
			}
			else
			{
				throw new Exception("No config for URL $url");
			}
		}
		
		protected function loadPagesByXML()
		{
			require_once 'Zend/Config/Xml.php';
			$path="engine/modules/".$this->_moduleType.'/'.self::XML_CONFIG_NAME;
			$xmlConfig=new Zend_Config_Xml($path,'PageClasses');
			$data=$xmlConfig->toArray();
			$this->loadPages(&$data);
		}
        
        public function initializePages()
        {
        	$this->loadPagesByXML();
        }
        
    }
?>
