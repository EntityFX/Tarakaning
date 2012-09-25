<?php
    require_once "ULListPager.php";
    
    class ULNumberedListPager extends ULListPager
    {
        private $_elements=5;
        
        public function setCountElements($number)
        {
            $this->_elements=$number;
        }
        
        public function getHTML()
        {
            $ulClass=$this->_ulClass != "" ? " class=\"$this->_ulClass\"" : "";
            $str.="<ul$ulClass>\r\n";
            $fL=$this->getFirst();
            if ($fL!=null)
            {
                $lnk=$this->getLinkParamsString($fL);
                $str.="\t<li><a href=\"$this->_Href$lnk\" title=\"Страница $fL\">&lt;&lt;</a></li>\r\n";
            } 
            $prL=$this->getPrevious();
            if ($prL!=null)
            {
                $lnk=$this->getLinkParamsString($prL);
                $str.="\t<li><a href=\"$this->_Href$lnk\" title=\"Страница $prL\">&lt;</a></li>\r\n";
            }
            $nL=$this->getNext();
            foreach($this->getPages() as $pageNum)
            {
                $eF=($pageNum-1)*$this->_elements+1;
                $eT=$eF+$this->_elements-1;
                if ($pageNum==$this->_current)
                {
                    $tagOpen="<$this->_selectedLiTag".($this->_selectedClass!="" ? " class=\"".$this->_selectedClass."\"" : "")." title=\"Страница $pageNum\">"; 
                    $tagClose="</$this->_selectedLiTag>";
                    $str.="\t<li>$tagOpen$eF...$eT$tagClose</li>\r\n"; 
                }
                else
                {
                    $lnk=$this->getLinkParamsString($pageNum); 
                    $str.="\t<li><a href=\"$this->_Href$lnk\" title=\"Страница $pageNum\">$eF...$eT</a></li>\r\n";
                }    
            }
            if ($nL!=null)
            {
                $lnk=$this->getLinkParamsString($nL);
                $str.="\t<li><a href=\"$this->_Href$lnk\" title=\"Страница $nL\">&gt;</a></li>\r\n";
            } 
            $lL=$this->getLast();
            if ($lL!=null)
            {
                $lnk=$this->getLinkParamsString($lL);
                $str.="\t<li><a href=\"$this->_Href$lnk\" title=\"Страница $lL\">&gt;&gt;</a></li>\r\n";
            }                         
            $str.="</ul>\r\n";
            return $str;
        }           
    } 
?>
