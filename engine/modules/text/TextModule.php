<?
/**
* Текстовый модуль
* @package modules.text 
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/      
  
    
    /**
    * Класс текстового модуля
    * @package modules.text
    */
    class TextModule extends DBConnector
    {
        private $_sectionID;
        
        public function __construct($sectionID)
        {
            parent::__construct();
            $this->_sectionID=(int)$sectionID;
        }
        
        public function getTextData()
        {
            $result=$this->_sql->selAllWhere("TextModule","textID = $this->_sectionID");
            $array=$this->_sql->getTable();
            var_dump($array);
            if ($array!=null)
            {
                return $array;
            }
            else
            {
                return 0;
            }                          
        }
    }

?>