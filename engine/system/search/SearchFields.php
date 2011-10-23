<?php
	require_once "SearchFieldTypeENUM.php";

	class SearchFields implements ArrayAccess,Iterator,Countable
	{
		private $_container;
		
		public function count()
		{
			return count($this->_container);
		}
		
		public function addField($name, SearchFieldTypeENUM $type)
		{
			$this->_container[$name]=$type->getValue();
		}
		
	    public function offsetSet($offset, $value) {
	        if (!is_null($offset)) {
	        	$this->addField($offset, $value);
	        }
	    }
	    
	    public function offsetExists($offset) {
	        return isset($this->_container[$offset]);
	    }
	    
	    public function offsetUnset($offset) {
	        unset($this->_container[$offset]);
	    }
	    
	    public function offsetGet($offset) {
	        return isset($this->_container[$offset]) ? $this->_container[$offset] : null;
	    }
	    
	    public function rewind() {
       		reset($this->_container);
    	}

	    public function current() {
	        return current($this->_container);
	    }
	
	    public function key() {
	        return key($this->_container);
	    }
	
	    public function next() {
	        return next($this->_container);
	    }
	
	    public function valid() {
	        return $this->current() !== false;
	    }    
	}