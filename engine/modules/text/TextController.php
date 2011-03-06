<?
	require_once "TextModule.php";
    
    class TextController extends PageController
	{
        public function initializePages()
        {
            var_dump($this);
            $textController=new TextModule($this->_sectionID);
            $textController->getTextData();
        }
        
	}
?>