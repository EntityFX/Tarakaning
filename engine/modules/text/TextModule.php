<?
/**
* Текстовый модуль
* @package modules.text 
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/      
  
	require_once 'engine/kernel/URLEditor.php';
	    
    /**
    * Класс текстового модуля
    * @package modules.text
    */
    class TextModule extends URLEditor
    {
        private $_pid;
        
        public function __construct()
        {
            parent::__construct();
            $arr=$this->getParent();
            $this->_pid=$arr["id"];
        }
        
        public function addText($url,$title,$text)
        {
        	$text=htmlspecialchars($text);
        	$title=htmlspecialchars($text);
        	try
        	{
        		$curID=$this->addUrl($url, $this->_pid, 1,$title,$title);
        		$this->_sql->insert("TextModule", 
        			new ArrayObject(array(
        				$curID,
        				$text
        			))
        		);
        	}
        	catch (URLException $exception)
        	{
        		throw $exception;		
        	}				
        }
        
        public function delText($id)
        {
        	$id=(int)$id;
        	$this->deleteUrl($id, true);
        	$this->_sql->delete("TextModule", "textID=$id");
        }
        
        public function editText($id,$text,$title)
        {
        	$id=(int)$id;
        	$url=$this->getByID($id);
        	if ($url!=null)
        	{
        		var_dump($url);
        		$this->updateUrl($id, $url["link"], $title);
        		$this->_sql->update("TextModule", "textID=$id", 
	        		new ArrayObject(array(
	        			"data" => htmlspecialchars($text)
	        		))
	        	);
        	}
        }
        
        public function getTextData($sectionID)
        {
            $sectionID=(int)$sectionID;
        	$result=$this->_sql->selAllWhere("TextModule","textID = $sectionID");
            $array=$this->_sql->getTable();
            if ($array!=null)
            {
                return $array[0];
            }
            else
            {
                return "";
            }                          
        }
    }

?>