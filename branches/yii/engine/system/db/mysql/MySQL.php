<?php
/**
* ���� � �������� ��� ������ � MySQL.
* @package MySQL
* @author Solopiy Artem
* @version 1.0 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/

	/**
	* ���������� ���� � �������  MySQLTypeEnumerator
	* @filesource engine/libs/mysql/MySQLTypeEnumerator.php 
	*/
	require_once "MySQLTypeEnumerator.php";

	/**
	* ���������� ���� � �������  MySQLField
	* @filesource engine/libs/mysql/MySQLField.php 
	*/
	require_once "MySQLField.php";
	
	/**
	* ���������� ���� � �������  MySQLquery
	* @filesource engine/libs/mysql/MySQLquery.php 
	*/
	require_once "MySQLquery.php";
    
    /**
    * ���������� ���� � �������  MySQLOrderENUM
    * @filesource engine/libs/mysql/MySQLOrderENUM.php
    */
    require_once "MySQLOrderENUM.php";
	
	/**
	* ���������� ����� � ����������� IMySQLSingleton.php
	* @filesource engine/libs/mysql/IMySQLSingleton.php
	*/
	require_once "IMySQLSingleton.php";
		 
	/**
	* ����� MySQL. �������� MySQL-��������. ������������� ����� ���������� ������� ������� � ��
	* @package MySQL 
	* @author Solopiy Artem
	* @final
	*/
	class MySQL extends MySQLquery implements IMySQLSingleton
	{
		/**
		* �������� � ���� ������������ ��������� �������� ������
		* 
		* @var MySQL
		*/
		protected static $_instance;
		/**
		* ����. ������� �� ��
		* 
		* @var Bool
		*/
		private $base_selected;
		/**
		* ������ ��
		* 
		* @var Array[String]
		*/
		private $data_bases;
		/**
		* ��� ��
		* 
		* @var String
		*/
		private $db_name;
        
        /**
        * �������� �����
        * 
        * @var Boolean
        */
        private $_limited;
        
        /**
        * ������ ������
        * 
        * @var Integer
        */
        private $_limitFrom;
        
        /**
        * ������ ������
        * 
        * @var Integer
        */
        private $_limitSize;
        
        /**
        * �����������
        * 
        * @var Boolean
        */
        private $_ordered;
        
        /**
        * ����������� ����
        * 
        * @var String;
        */
        private $_orderField;
        
        /**
        * ����������� �����������
        * 
        * @var MySQLOrderENUM
        */
        private $_orderDirection;

		/**
		* ��������� � ���, ��� �� ������� ��
		*/
		const NO_DB_SELECTION="Database didn't selected";
		/**
		* ��� ����������� � ��
		*/
		const NO_CONNECTION="No MySQL connection";
		/**
		* ������������ ������
		*/
		const NO_QUERY="BAD MySQL query";
		
		/**
		* ������, ���� ���������� ������������ ��������� MyQSL
		* 
		* @param string $server
		* @param string $user
		* @param string $password
		*/
		public static function &getInstance($server,$user,$password)
		{
			if (self::$_instance==NULL)
			{
				self::$_instance=new MySQL($server,$user,$password);
			}
			return self::$_instance; 
		}     
		
		/**
		* �����������. ������������ � ��
		*   
		* @param string $server ����� �������
		* @param string $user ��� ������������
		* @param string $password ������
		* @return MySQL
		*/
		protected function __construct($server,$user,$password)
		{
			parent::__construct($server,$user,$password);
		}
		/**
		* ����� ��
		* 
		* @param Bool $db_name ��� ��
		*/
		public function selectDB($db_name)
		{
			try
			{
				mysql_select_db($db_name);
				$this->db_name=$db_name;
			}
			catch (Exception $e)
			{
				throw new MySQLException($this, "MySQL ERROR: Can't connect to DB");
			}
			$this->base_selected=true;
			return true;
		}
        
        /**
        * ���������� �����
        * 
        * @param Integer $from
        * @param Integer $size
        */
        public function setLimit($from,$size)
        {
            $this->_limited=true;
            $this->_limitFrom=(int)$from;
            $this->_limitSize=(int)$size;
        }
        
        /**
        * ���������� �� �����
        * 
        * @return Boolean 
        */
        public function isLimit()
        {
            return $this->_limited;
        }
        
        /**
        * ����� ������
        * 
        */
        public function clearLimit()
        {
            $this->_limited=false;     
            $this->_limitFrom=0;
            $this->_limitSize=100;
        }
        
        public function setOrder(AEnum $orderedField, MySQLOrderENUM $direction)
        {
            $this->_ordered=true;
            $this->_orderField=$orderedField->getValue();
            $this->_orderDirection=$direction->getValue();
        }
        
        public function clearOrder()
        {
            $this->_ordered=false;
        }
        
        public function isOrdered()
        {
            return $this->_ordered;
        }
        
        private function getPostfix()
        {
            $str="";
            if ($this->_ordered)
            {
                $str.=" ORDER BY ".$this->_orderField." ".$this->_orderDirection;
            }
            if ($this->_limited)
            {
                $str.=" LIMIT ".$this->_limitFrom.", ".$this->_limitSize;
            }
            return $str;
        }
        
		/**
		* ������� �� �� �������
		* 
		* @param String $table_name ��� ��
		*/
		public function selAll($table_name)
		{
			$query_res=$this->queryExecute("SELECT * FROM `$table_name`".$this->getPostfix());
			$this->rows=&$this->getRows($query_res);
		}    
		/**
		* ������� �� �� ������� � WHERE
		* 
		* @param String $table_name ��� �������
		* @param String $where WHERE ""
		*/
		public function selAllWhere($table_name,$where)
		{
            $query_res=$this->queryExecute("SELECT * FROM `$table_name` WHERE $where".$this->getPostfix());
			$this->rows=&$this->getRows($query_res);    
		}
		
		/**
		* ������� �� �� �������, ���� ����������� � ������� 
		* 
		* @param String $table_name ��� ������� 
		* @param Array[String] $fields_arr ������ ����� (����� �����, ������� ����� �������)
		*/
		public function selFieldsA($table_name,$fields_arr)
		{
			$fields=$this->MakeFieldString($fields_arr);
			$query_res=$this->queryExecute("SELECT $fields FROM `$table_name`".$this->getPostfix());
			$this->rows=&$this->GetRows($query_res);    
		}
		/**
		* ������� �� �� �������, ���� ����������� ����� ��������� ���������� 
		* 
		* @param String $table_name ��� �������
		* @param String [...] ����� �����. ������������� �������� �������.
		*/
		public function selFields($table_name)
		{
			$args = func_get_args();
			$fields_arr=array_slice($args,1);
			$fields=$this->MakeFieldString($fields_arr);
			$query_res=$this->queryExecute("SELECT $fields FROM `$table_name`".$this->getPostfix());
			$this->rows=&$this->getRows($query_res);    
		}
		/**
		* ������� �� �� ������� � �������� 
		* 
		* @param String $table_name ��� �������
		* @param Array $fields_arr ������ ����� (����� �����, ������� ����� �������)    
		* @param String $where ������� �������
		*/
		public function selFieldsWhereA($table_name,$fields_arr,$where)
		{
			$fields=$this->MakeFieldString($fields_arr);
			$query_res=$this->queryExecute("SELECT $fields FROM `$table_name` WHERE $where".$this->getPostfix());
			$this->rows=&$this->getRows($query_res);    
		} 
		/**
		* ������� �� �� ������� � ��������
		*        
		* @param String $table_name ��� �������
		* @param String $where ������� �������
		* @param String [...] ����� �����. ������������� �������� �������. 
		*/
		public function selFieldsWhere($tableName,$where)
		{
			$args = func_get_args();
			$fields_arr=array_slice($args,2);
			$fields=$this->MakeFieldString($fields_arr);            
			$query_res=$this->queryExecute("SELECT $fields FROM `$tableName` WHERE $where".$this->getPostfix());
			$this->rows=&$this->getRows($query_res);   
		}
		/**
		* ������� �������
		* 
		* @param String $table_name ��� �������
		* @param Array[MySQLField] $fields ������� �����
		*/
		public function createTable($table_name,&$fields)
		{
			$template=$this->CreateTableTemplate($table_name,$fields);
			$this->queryExecute($template);
		}
		/**
		* ������� � ������� ������
		* 
		* @param String $table_name
		* @param mixed $values
		* @param mixed $fields
		* @return Resource
		*/
		public function insert($table_name,&$values,&$fields=null)
		{
			$query=$this->InsertIntoTemplate($table_name,$values,$fields);
			return $this->queryExecute($query);   
		}
		
		/**
		 * 
		 * ���������� ������ � �������
		 * @param unknown_type $table_name
		 * @param unknown_type $values
		 * @param unknown_type $fields
		 */
		public function update($table_name, $where, &$newFieldValues)
		{
			$query=$this->updateTemplate($table_name, $where, $newFieldValues);
			return $this->queryExecute($query);  	
		}
		
		/**
		* ������� �������
		* 
		* @param String $table_name ��� �������
		*/
		public function dropTable($table_name)
		{
			$this->queryExecute("DROP TABLE `$table_name`");
		}
		
		/**
		* ��������� ����� ������� � �������. 
		* 
		* @param String $table_name ��� �������
		* @param String $where ������ � WHERE
		* @return Integer
		*/
		public function countQuery($table_name,$where="")
		{
			if ($where=="")
			{
				$query="SELECT COUNT(*) as `cnt` FROM `$table_name`";   
			}
			else
			{
				$query="SELECT COUNT(*) as `cnt` FROM `$table_name` WHERE $where";
			}
			$res=$this->queryExecute($query);
			$arr=$this->GetRows($res);
			return (int)$arr[0]["cnt"];    
		}
		
		/**
		* �������� ������� �� �������
		* 
		* @param String $tableName ��� �������
		* @param String $where �������
		*/
		public function delete($tableName,$where)
		{
			$this->queryExecute("DELETE FROM `$tableName` WHERE $where");
		}
		
		/**
		* �������� ������ ����� �� ������� ����� ���������� �������
		*  
		* @return Array[Array[String]]
		*/
		public function &getResultRows()
		{
			return $this->rows;    
		}
		/**
		* �������� ������ ������
		* 
		* @throws Exception ������ � ���, ��� �� ���� ������� ��
		* @return Array[Array[String]] 
		*/
		public function getTableList()
		{
			if (!$this->base_selected) 
			{
			   throw new MySQLException($this, MySQL::NO_DB_SELECTION);
			}
			else 
			{
				return $this->getRows(mysql_listtables($this->db_name));
			}
		}
		/**
		* �������� ������ ����� �������
		* 
		* @param String $table_name ��� �������
		* @throws Exception � ���, ��� �� ������� ��
		* @return Array[Array[String]]
		*/
		public function getFieldList($table_name)
		{
			if (!$this->base_selected) 
			{
			   throw new MySQLException($this, MySQL::NO_DB_SELECTION);
			}
			else 
			{
				return $this->getFields(mysql_list_fields($this->db_name,$table_name));
			}                
		}
		/**
		* ��������� ������ � ��
		*         
		* @param String $query ��������� ������
		* @throws Exception �� ������� ��
		* @throws Exception �������� SQL-������
		* @return Resource
		*/
		private function queryExecute($query)
		{
			$query_res=null;
			if (!$this->base_selected) 
			{
			   throw new MySQLException($this, MySQL::NO_DB_SELECTION);
			}
			else 
			{
                $query_res=$this->query($query);
				if (!$query_res)
				throw new MySQLException($this, MySQL::NO_QUERY." $query_res");
			}
			return $query_res;
		}
		
		public function getDBName()
		{
			return $this->db_name;
		}
		
		/**
		 * 
		 * �������� �������� ���������
		 * @param string $name ��� �������� ���������
		 * @param array|mixed $paramsArray ���������
		 */
		public function call($name, ArrayObject &$paramsArray) 
	    {
	    	if (is_null($paramsArray)) 
	        {
	        	$params="";
	        }
	        else 
	        {
	        	$length=$paramsArray->count();
	        	$current=0;
	        	while ($current<$length-1)
	        	{
	        		if (is_string($paramsArray[$current]))
	        		{
	        			$val='\''.addslashes($paramsArray[$current]).'\'';
	        		}
	        		else if (is_bool(is_string($paramsArray[$current])))
	        		{
	        			$val=(int)$paramsArray[$current];
	        		}
	        		else 
	        		{
	        			$val=$paramsArray[$current];
	        		}
	        		$params.=$val.', ';
	        		$current++;
	        	}
        	    if (is_string($paramsArray[$current]))
        		{
        			$val='\''.addslashes($paramsArray[$current]).'\'';
        		}
        		else if (is_bool(is_string($paramsArray[$current])))
        		{
        			$val=(int)$paramsArray[$current];
        		}
        		else 
        		{
        			$val=$paramsArray[$current];
        		}
	        	$params.=$val;
	        }
	        $query_res=$this->queryExecute("CALL $name($params)");
	    }
	    
		public function getLastID() 
		{
			$this->queryExecute("SELECT last_insert_id() AS id");
			$arr = &$this->getRows($query_res);
			return (int)$arr[0]['id'];
		}
		
	} 
	
?>
