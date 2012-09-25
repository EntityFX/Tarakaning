<?php
    require_once "ULNumberedListPager.php";
    require_once "InputListPager.php"; 
    
    $page=new ULListPager(200000,"p1");
    $page->setSize(20);
    $page->setClass("single");
    $page->setSelectedClass("selected");
    $page->setCurrentTag("span");
    $page1=new ULNumberedListPager(30000,"p2");
    $page1->setSize(10);
    $page1->setClass("single");
    $page1->setSelectedClass("selected");
    $page1->setCountElements(11);
    $page1->setCurrentTag("span"); 
    $page2=new InputListPager(300,"one");
    $page2->setSize(10);
    $page2->setSelectedClass("sel");
    $page3=new InputListPager(50,"vasya");
    $page3->setSelectedClass("sel"); 
    $page3->setSize(5); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Tarakaning</title>
        <!--<link rel="shortcut icon" href="images/quki.ico" />-->
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
        <link rel="stylesheet" type="text/css" href="verstko/style.css" />
        <link href="verstko/custom-theme/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
        <script type="text/javascript">
        /* <![CDATA[ */
            $(document).ready(function() {
                $("input:button, input:submit, button, .groupier a, .groupier li span").button();
            });
        /* ]]>*/
        </script>
        <style type="text/css">
            .sel {
                color: red;
            }
        </style>
    </head>
    <body>
        <div id="content_body">
            <div class="groupier"> 
<?php
  
    echo $page->getHTML();
    
    ?>
            </div>
            <div class="groupier">
    <?  
    
    echo $page1->getHTML();
    echo $page2->getHTML();
    ?>
            </div>
            <div class="groupier">
    <?  
    echo $page3->getHTML();     
?>
            </div>
            <?
    echo "<br />".$page->getCurrent()."; ".$page1->getCurrent()."; ".$page2->getCurrent()."; ".$page3->getCurrent();
?>
        </div>
    </body>
</html>