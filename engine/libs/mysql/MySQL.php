<?php
/**
* ���� � �������� ��� ������ � MySQL.
* @package MySQL
* @author Solopiy Artem
* @version 0.9 Beta
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
	final class MySQL extends MySQLquery implements IMySQLSingleton
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
		public static function &creator($server,$user,$password)
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
				throw new Exception("MySQL ERROR: Can't connect to DB");
			}
			$this->base_selected=true;
			return true;
		}
		/**
		* ������� �� �� �������
		* 
		* @param String $table_name ��� ��
		*/
		public function selAll($table_name)
		{
			$query_res=$this->queryExecute("SELECT * FROM `$table_name`");
			$this->rows=$this->getRows($query_res);
		}    
		/**
		* ������� �� �� ������� � WHERE
		* 
		* @param String $table_name ��� �������
		* @param String $where WHERE ""
		*/
		public function selAllWhere($table_name,$where)
		{
			$query_res=$this->queryExecute("SELECT * FROM `$table_name` WHERE $where");
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
			$query_res=$this->queryExecute("SELECT $fields FROM `$table_name`");
			$this->rows=$this->GetRows($query_res);    
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
			$query_res=$this->queryExecute("SELECT $fields FROM `$table_name`");
			$this->rows=$this->getRows($query_res);    
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
			$query_res=$this->queryExecute("SELECT $fields FROM `$table_name` WHERE $where");
			$this->rows=$this->getRows($query_res);    
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
			return $this->queryExecute("SELECT $fields FROM `$tableName` WHERE $where");   
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
		public function &getTable()
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
			   throw new Exception(MySQL::NO_DB_SELECTION);
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
			   throw new Exception(MySQL::NO_DB_SELECTION);
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
			   throw new Exception(MySQL::NO_DB_SELECTION);
			}
			else 
			{
				$query_res=$this->query($query);
				if (!$query_res)
				throw new Exception(MySQL::NO_QUERY." $query_res");
			}
			return $query_res;
		}
		
	} 
	
	/**TEST*
	$field[]=new MySQLField("id",MySQLField::INT,true,true,true);
	$f=new MySQLField("flag",MySQLField::VARCHAR,true,false);
	$f->max_length=50;
	$field[]=&$f;
	$field[]=new MySQLField("time",MySQLField::DATETIME); 
	$field[]=new MySQLField("image",MySQLField::BLOB);
	$field[]=new MySQLField("flo",MySQLField::FLOAT);
	$sql=new MySQL("localhost","root","");
	$sql->SelectDB("idelmedia");
	//$val=array("0","'hello'","NOW()","''","3.14");
	$val=array("0","'hello'","89");
	$fld=array("id","flag","flo");
	//echo $sql->InsertIntoTemplate("s",$val,$fld);
	//$sql->CreateTable("s",$field);
	$sql->Insert("s",$val,$fld);
	//$sql->DropTable("s");
	//$sql->SelAllWhere("users","`login`='1'");
	//var_dump($sql->getTable());
	$tables=$sql->getTableList();
	for($tbls_c=0;$tbls_c<count($tables);++$tbls_c)
	{
		echo "<table border=\"1\"><thead>"; 
		echo "<caption>".$tables[$tbls_c][0]."</caption>";
		echo "<tr>";
		$field_arr=$sql->getFieldList($tables[$tbls_c][0]);
		foreach($field_arr as $field_obj)
		{
			if ($field_obj->primary_key)
			echo  "<th><i>".$field_obj->name."</i></th>";
			else echo  "<th>".$field_obj->name."</th>"; 
		}
		echo "</tr>";
		echo "</thead><tbody>";
		$sql->SelAll($tables[$tbls_c][0]);
		$list=&$sql->getList();
		for($i=0;$i<$list->Count();++$i)
		{
			$l=$list->getElement($i);
			echo "<tr>";
			for($j=0;$j<count($l);++$j)
			{
				echo "<td>".htmlspecialchars($l[$j])."</td>";    
			}
			echo "</tr>";
		}
		echo "</tbody></table>";
	}*/
?>
