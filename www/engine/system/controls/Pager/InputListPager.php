<?php
    class InputListPager extends ListPager
    {
        private $_name;
        
        public function __construct($count,$name)
        {
            session_start();
            if (isset($_POST[$name]))
            {
                $getVal=$_POST[$name];
                switch ($getVal)
                {
                    case "<<":
                        $getVal=(int)$_POST["f_".$name];
                        break;
                    case "<":
                        $getVal=(int)$_POST["pr_".$name]; 
                        break;
                    case ">":
                        $getVal=(int)$_POST["n_".$name]; 
                        break;
                    case ">>":
                        $getVal=(int)$_POST["l_".$name];
                        break;
                    default:
                        $getVal=(int)$getVal;
                        break;                       
                }
            }
            else
            {
                if (isset($_SESSION["ENTITYFX_CONTROLLS"][__class__][$name]))
                {
                    $getVal=$_SESSION["ENTITYFX_CONTROLLS"][__class__][$name];    
                }
                else
                {
                    $getVal=1;
                }
            }
            $_SESSION["ENTITYFX_CONTROLLS"][__class__][$name]=$getVal;
            $this->_name=(string)$name;
            try
            {
                parent::__construct($count,$getVal);
            }
            catch(Exception $e)
            {
                parent::__construct($count,1);
            }
        }
        
        public function getHTML()
        {
            $str.="<form action=\"\" method=\"post\">\r\n<div>\r\n";
            if ($this->_selectedClass!=="")
            {
                $cl=" class=\"$this->_selectedClass\"";
            }
            $fL=$this->getFirst(); 
            if ($fL!=null)
            {
                $str.="\t<input type=\"submit\" name=\"$this->_name\" value=\"&lt;&lt;\" title=\"Страница $fL\"/>\r\n";
                $str.="\t<input type=\"hidden\" name=\"f_$this->_name\" value=\"$fL\" />\r\n";
            } 
            $prL=$this->getPrevious();
            if ($prL!=null)
            {
                $str.="\t<input type=\"submit\" name=\"$this->_name\" value=\"&lt;\" title=\"Страница $prL\"/>\r\n";
                $str.="\t<input type=\"hidden\" name=\"pr_$this->_name\" value=\"$prL\"/>\r\n"; 
            } 
            foreach($this->getPages() as $pageNum)
            {
                if ($pageNum==$this->_current)
                {
                    $str.="\t<input$cl type=\"submit\" name=\"$this->_name\" value=\"$pageNum\" title=\"Страница $pageNum\"/>\r\n"; 
                }
                else
                {
                    $str.="\t<input type=\"submit\" name=\"$this->_name\" value=\"$pageNum\" title=\"Страница $pageNum\"/>\r\n"; 
                }  
            }
            $nL=$this->getNext();
            if ($nL!=null)
            {
                $str.="\t<input type=\"submit\" name=\"$this->_name\" value=\"&gt;\" title=\"Страница $nL\"/>\r\n";
                $str.="\t<input type=\"hidden\" name=\"n_$this->_name\" value=\"$nL\"/>\r\n";             
            } 
            $lL=$this->getLast();
            if ($lL!=null)
            {
                $str.="\t<input type=\"submit\" name=\"$this->_name\" value=\"&gt;&gt;\" title=\"Страница $lL\"/>\r\n";
                $str.="\t<input type=\"hidden\" name=\"l_$this->_name\" value=\"$lL\"/>\r\n"; 
            }     
            $str.="</div>\r\n</form>\r\n";
            return $str;
        }        
    }
?>
