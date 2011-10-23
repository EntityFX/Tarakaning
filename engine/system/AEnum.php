<?php
    abstract class AEnum
    {
        protected $__value;
        
        private $__checked=false;
        
        public function __construct($value)
        {
            $this->__value=$value;
            if (!$this->check())
            {
                throw new Exception("Элемент <strong style=\"color: blue; font-style: italic;\">$value</strong> не соответствует константам в списке");
            }
        }
        
        public function check()
        {
            $reflector=new ReflectionClass($this);
            $consts=$reflector->getConstants();
            foreach($consts as $constValue)
            {
                if ($this->__value==$constValue)    
                {
                    return true;
                }
            }
            $this->__checked=true;
            return false;
        }
        
        public function getValue()
        {
            return $this->__value;
        }
        
        public function getArray()
        {
        	$reflector=new ReflectionClass($this);
        	return $reflector->getConstants();
        }
    }
?>
