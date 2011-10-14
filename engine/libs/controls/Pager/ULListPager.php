<?php
    require_once "ListPager.php"; 
    
    class ULListPager extends ListPager
    {
        
        protected $_selectedLiTag="span";
        
        private $_Href="";
        
        private $_getRef; 
        
        protected $_ulClass="";
        
        protected $_cStyle="";
        
        protected $_idTag="";
        
        public function __construct($count,$get="page",$size=5,$paginatorSize=5)
        {
            if (isset($_GET[$get]))
            {
                $getVal=(int)$_GET[$get];
            }
            else
            {
                $getVal=1;
                $_GET[$get]=$getVal;   
            }
            $this->_getRef=$get;
            $this->_size=(int)$size;
            $count=$size>0?(int)ceil($count / $this->_size):0;
            try
            {
                parent::__construct($count,$getVal,$size,$paginatorSize);
            }
            catch(Exception $e)
            {
                parent::__construct($count,1,$size,$paginatorSize);
            }
        }
        
        public function getHTML()
        {
            if ($this->_count<=1)
            {
            	return "";
            }
        	$this->_ulClass=$this->_class;
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
                if ($pageNum==$this->_current)
                {
                    $tagOpen="<$this->_selectedLiTag".
                    ($this->_selectedClass!="" ? " class=\"".$this->_selectedClass."\"" : "").
                    ($this->_cStyle!="" ? " style=\"".$this->_cStyle."\"" : "").
                    ">";
                    $tagClose="</$this->_selectedLiTag>";
                    $str.="\t<li>$tagOpen$pageNum$tagClose</li>\r\n"; 
                }
                else
                {
                    $lnk=$this->getLinkParamsString($pageNum);  
                    $str.="\t<li><a href=\"$this->_Href$lnk\" title=\"Страница $pageNum\">$pageNum</a></li>\r\n";
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
        
        final public function setHref($href)
        {
            $this->_Href=$href;    
        }
        
        public function setCurrentTag($tagName="span")  
        {
            $this->_selectedLiTag=$tagName;  
        }
        
        public function setCurrentStyle($value)
        {
        	$this->_cStyle=$value;
        }
        
        public function setIDTag($value)
        {
        	$this->_idTag=$value;
        }
        
        protected function getLinkParamsString($val)
        {
            if ($_GET!=null)
            {
                $first=0;
                foreach($_GET as $getParamKey => $getParamVal)
                {
                    if ($first!=0)
                    {
                        $str.="&amp;";
                    }
                    $first++;
                    if ($getParamKey==$this->_getRef)
                    {
                        $str.=$getParamKey."=".$val;     
                    }
                    else
                    {
                        $str.=$getParamKey."=".$getParamVal; 
                    }
                }
            	$str.=$this->_idTag !="" ? "#". $this->_idTag : "";
                return "?$str";
            }

        }
    }
?>
