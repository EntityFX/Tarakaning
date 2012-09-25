<?php
    abstract class ListPager
    {
        public $name;
   
        protected $_current;   
        
        protected $_size=5; 
        
        protected $_class=""; 
        
        protected $_selectedClass=""; 
        
        protected $_count;
        
        private $_currentModSize;
        
        private $_countModSize;
        
        private $_correction;
        
        private $_paginatorSize;
        
        public function __construct($count,$current=1,$size=20,$paginatorSize=5)
        {
            $this->_count=$count;
            if ($current<=$count && $current>0)
            {
                $this->_current=$current; 
            }
            else if ($count==0)
            {
            	 $this->_current=1;
            }
            else
            {
                throw new Exception("CurrentPage Out Of Range");
            } 
            $this->setSize($paginatorSize);
            $this->_size=$size;
        } 
        
        public function setpaginatorSize($size)
        {
        	$this->_size=$size;
        }
        
        public function setSize($paginatorSize)  
        {
            $paginatorSize=(int)$paginatorSize;
            if ($paginatorSize>0)
            {
                $this->_paginatorSize=$paginatorSize;
                $this->_countModSize=$this->_count % $this->_paginatorSize;
                $this->_currentModSize=$this->_current % $this->_paginatorSize; 
                $this->_correction=(($this->_currentModSize != 0) ? 0 : -$this->_paginatorSize);
            }
        }
        
        public function getSize()
        {
        	return $this->_size;
        }
        
        protected function getFirst()
        {
            return $this->_current > $this->_paginatorSize ? 1 : null;    
        }
        
        protected function getLast()
        {  
        	return $this->_current <= $this->_count - (($this->_countModSize==0)? $this->_paginatorSize : $this->_countModSize ) ? $this->_count : null;    
        }
        
        protected function getPrevious()
        {
            if ($this->getFirst()!=null)
            {
                return $this->_current - $this->_currentModSize + $this->_correction;
            }
            else
            {
                return null;
            }
        }
        
        protected function getNext()
        {
            if ($this->getLast()!=null)
            {
            	return $this->_paginatorSize + $this->_current - $this->_currentModSize + $this->_correction+1;
            }
            else
            {
                return null;
            }            
        }
        
        public function getpaginatorSize()
        {
        	return $this->_paginatorSize;
        }
        
        protected function getPages()
        {
            $from=$this->_current - $this->_currentModSize + $this->_correction+1;
            $to=$this->_paginatorSize + $this->_current - $this->_currentModSize + $this->_correction;
            for($it=$from;$it<=$to && $it<=$this->_count;$it++)    
            {
                $res[]=$it;
            }
            return $res;
        }
        
        final public function setSelectedClass($currentClass="")  
        {
            $this->_selectedClass=$currentClass;    
        }        
        
        final public function setClass($className)
        {
            $this->_class=$className;    
        }
        
        public abstract function getHTML();
        
        public function getCurrent()
        {
            return (int)$this->_current;
        }
        
        public function getOffset()
        {
        	return $this->_current==0?0:($this->_current-1)*$this->_size;
        }
        
    }
?>
