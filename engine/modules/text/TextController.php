<?
	require_once "TextPage.php";
	
    class TextController extends PageController
	{
        public function initializePages()
        {
            /*$textController=new TextModule($this->_sectionID);
            //$request->setRedirect("/error/404/");
            try 
            {
            	$textController->getTextData();
            }
            catch (Exception $e)
            {
            	echo "Не найдена запись в таблице TextModule";	
            }*/
        	$page=new TextPage($this,"textTemplate.tpl");
        	//var_dump($page);
        }
        
	}
?>