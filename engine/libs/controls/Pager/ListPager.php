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
        
        public function __construct($count,$current=1)
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
            $this->setSize($this->_size);
        } 
        
        public function setSize($size)  
        {
            $size=(int)$size;
            if ($size>0)
            {
                $this->_size=$size;
                $this->_countModSize=$this->_count % $this->_size;
                $this->_currentModSize=$this->_current % $this->_size; 
                $this->_correction=(($this->_currentModSize != 0) ? 0 : -$this->_size);
            }
        }
        
        public function getSize()
        {
        	return $this->_size;
        }
        
        protected function getFirst()
        {
            return $this->_current > $this->_size ? 1 : null;    
        }
        
        protected function getLast()
        {
            return $this->_current <= $this->_count - (($this->_countModSize==0)? $this->_size : $this->_countModSize ) ? $this->_count : null;    
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
                return $this->_size + $this->_current - $this->_currentModSize + $this->_correction+1;
            }
            else
            {
                return null;
            }            
        }
        
        protected function getPages()
        {
            $from=$this->_current - $this->_currentModSize + $this->_correction+1;
            $to=$this->_size + $this->_current - $this->_currentModSize + $this->_correction;
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
