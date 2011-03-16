<?
	require_once "TextPage.php";
	
	require_once 'TextModule.php';
	
	require_once 'engine/kernel/Menu.php';
	
    class TextController extends PageController
	{
        public function initializePages()
        {
        	//$url=new TextModule();
        	//$url->addText("one10", "Привет", "Да здравствует мир!");
        	//$url->delText(54);
        	//$url->editText(19, "А вот нихрена не привеет", "Капеец");
        	$menu=new Menu();
        	//$menu->addLink("hi", "HI");
        	//$menu->deleteLink(2);
        	//$menu->editLink(3, "one", "Единственный");
        	//var_dump($menu->getAll());
        	$page=new TextPage($this,"textTemplate.tpl");
        }
        
	}
?>