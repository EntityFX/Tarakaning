<?
	require_once "TextPage.php";
	
	require_once 'engine/kernel/URLEditor.php';
	
	require_once 'TextModule.php';
	
    class TextController extends PageController
	{
        public function initializePages()
        {
        	$url=new URLEditor();
        	$mod=new ModuleEditor();
        	//$url->addUrl("hi", 36, 11);
        	$page=new TextPage($this,"textTemplate.tpl");
        }
        
	}
?>