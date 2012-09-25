<?php
	class ModuleEditor extends DBConnector
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function addModule($name,$type,$description="")
		{
			if (!$this->checkIfExsistPath($type))
			{
				if (preg_match("/^[A-Z][a-zA-Z0-9\_]*$/", $type)==1)
				{
					$this->_sql->insert(
						"Modules", 
						new ArrayObject(array(
							0,
							htmlspecialchars($name),
							htmlspecialchars($description),
							(string)$type
						))
					);				
				}
				else
				{
					throw new Exception("Path name must have first A-Z char and a-z, A-Z, 0-9, _ symbols");	
				}
			}	
			else
			{
				throw new Exception("Module with type $type already exsist");
			}	
		}
		
		public function deleteModule($type)
		{
			$this->_sql->delete($tableName, "path='$type'");	
		}
		 
		public function checkIfExsist($id)
		{
            $id=(int)$id;
            $countGroups=$this->_sql->countQuery("Modules","moduleId=$id");
            return (Boolean)$countGroups; 			
		}
				
		public function checkIfExsistPath($type)
		{
            $path=(string)$path;
            $countGroups=$this->_sql->countQuery("Modules","path='$type'");
            return (Boolean)$countGroups; 			
		}
		
		public function getAll()
		{
			return $this->_sql->selAll("Modules");	
		}
	}