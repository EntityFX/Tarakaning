<?php
require_once 'IPageController.php';

require_once 'URLBase.php';

require_once 'PageBase.php';

    class PageController extends URLBase implements IPageController
    {
       
        final public function __construct(&$initData)
        {
            parent::__construct($initData);
            $this->initializePages();
        }
        
        public function initializePages() {}
        
    }
?>
