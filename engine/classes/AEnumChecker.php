<?php
    abstract class AEnumChecker
    {
        protected $__value;
        
        private $__checked=false;
        
        public function __construct($value)
        {
            $this->__value=$value;
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
    }
?>
