<?php
/**
* ���� � ������� ��� ������ � MySQL.
* @package MySQL
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/

require_once "engine/config/databaseConsts.php";
require_once "engine/system/fs/TextFile.php";
require_once 'MySQLException.php';

    /**
    * ����� MySQL ��������
    * @package MySQL 
    */
    abstract class MySQLquery
    {
        const EXCEPTION_NO_PRIMARY_KEY="No field with primary key";
        
        /**
        * ����� ���������� �������. ���� true, �� ������� �� ����� ������ 
        * 
        * @var mixed
        */
        public static $globalDebugging;
        
        /**
         * 
         * ���� �������
         * @var string
         */
        private static $_debugPath="engine/log/sql_debug.log";
        
        private $_queryCounter=1;
        
        /**
        * ����� �������. ���� true, �� ������� �� ����� ������
        * 
        * @var mixed
        */
        public $debugging;
        
        /**
        * ��������� ������ ����� �������
        * 
        * @param Resource $query_res ��������� �������
        * @return Array[String]
        */
        private $_internalResource;
         
        /**
        * ������ ����� �������
        * 
        * @var Array[String]
        */
        protected $rows;
        
        /**
        * ����� �������
        * 
        * @var String
        */        
        protected $_server;
        
        /**
        * ��� ������������
        * 
        * @var String
        */
        protected $_user;
        
        /**
        * ������
        * 
        * @var String
        */
        protected $_password;
        
        /**
         * 
         * ������ ����� �����������
         * @var string
         */
        protected $_internalQuery;
        
        private $_debugFile;
        
        private $_startMikrotime;
        
        /**
        * �����������
        * 
        * @param String  $server
        * @param String  $user
        * @param String  $password
        * @return MySQLquery
        */
        protected function __construct($server,$user,$password)
        {
            $this->rows=array();
            try
            {
                mysql_connect($server,$user,$password);
            }
            catch (Exception $e)
            {
                throw new MySQLException($this,NO_CONNECTION);                
            }
            $this->_server=$server;
            $this->_user=$user;
            $this->_password=$password;
            return true; 
        }
        
    	public function __destruct()
		{
        	if ($this->_debugFile!=null)
        	{
        		list($end, $sec) = explode(" ", microtime(true));
        		$this->_debugFile->writeLine("END SQL DEBUG ".date("Y-m-d H:i:s.B")." (end time: ".$end."  �s )");
        		$this->_debugFile->writeNewLine();
        		$this->_debugFile->close();
        	}
		}
		
        /**
        * �������� ������ �����
        * 
        * @param Resource $query_res ������ �������
        * @return Array[Array[String]] 
        */
        public function &GetRows($queryRes=NULL)
        {
            $rows=null;
            if ($queryRes==NULL)
            {
                $query_res=$this->_internalResource;
            }
            else
            {
                $query_res=$queryRes;    
            }
            while ($row=mysql_fetch_assoc($query_res))
            {
                $rows[]=$row;    
            }
            return $rows;
        }
        
        /**
        * ���������� ������ ���������� ����� �������
        * 
        * @param Resource $resourse ��������� �������
        * @return Array[String]
        */
        public function GetFields($resourse)
        {
            $res=null;
            while ($row=mysql_fetch_field($resourse))
            {
                $res[]=$row;    
            }
            return $res;
        }
        /**
        * ���������� ������ CREATE TABLE
        * 
        * @param String $table_name ��� �������
        * @param Array[MySQLField ] $fields ������ ����� MySQLField
        * @return String
        */
        protected function CreateTableTemplate($table_name,&$fields)
        {
            $str="CREATE TABLE `$table_name` (\r\n";
            $is_primary_key=false;
            $primary_key;
            foreach($fields as $val)
            {
                $str.="\t".$val->GetFieldString().",\r\n";
                if (!$is_primary_key && $val->primary_key)
                {
                    $primary_key=$val->name;
                    $is_primary_key=true;    
                }
            }
            if ($is_primary_key)
            {
                $str.= "\tPRIMARY KEY (`$primary_key`)\r\n);";    
            } else throw new MySQLException($this,MySQLquery::EXCEPTION_NO_PRIMARY_KEY);
            return $str;    
        }
        
        /**
        * ������ INSERT INTO
        * 
        * @param String $table_name ��� �������
        * @param Array[Mixed] $values ������ �������� ����� ����������� �������
        * @param Array[MySQLField] $fields ������ ��� ����� ��� ����������� �������. ��-��������� ��� ���� ����� 
        * @return String
        */
        protected function InsertIntoTemplate($table_name,&$values,&$fields=null)
        {
            $values=$this->MakeFieldString($values,"");
            $res="INSERT INTO `$table_name`";
            $flds="";
            if ($fields!=null)
            {
                $flds=$this->MakeFieldString($fields,"`");
                $res.=" ($flds)";
            }
            return $res." VALUES ($values)";
        }
        
        protected function updateTemplate($table_name,$where,&$values)
        {
        	if ($values!=null)
        	{
        		$it=0;
        		$fld="";
        		$fields;
        		foreach($values as $key => $val)
        		{
        			if (is_string($val))
        			{
        				$fld="`$key`"."='".$val."'";			
        			}
        			else if (is_bool($val))
        			{
        				$fld="`$key`"."=".(int)$val;	
        			}
        			else
        			{
        				$fld="`$key`"."=".$val;
        			}	
        			if ($it==0)
                    {
                        $fields.=$fld;
                    } else $fields.=", ".$fld;
                    $it++;
        		}
        		$res="UPDATE `$table_name` SET $fields ";
        		return $res."WHERE $where";	
        	}	
        	return null;	
        }
        
        /**
        * ��������� ������, ����� SQL: VALUES([��������[,��������]])
        * 
        * @param Array[Mixed] $fields_arr ������ ����� MySQLField 
        * @param mixed $char
        * @return String
        */
        protected function MakeFieldString(&$fields_arr,$char="`")
        {
            $fields="";
            $fld="";
            if ($fields_arr!=NULL)
            {
                for($i=0;$i<count($fields_arr);++$i)
                {
                    if (is_string($fields_arr[$i]) && $char!="`")
                    {
                        $fld=mysql_escape_string($fields_arr[$i]);                          
                        $fld="'$fld'";
                    }
                    else if (is_bool($fields_arr[$i]))
                    {
                    	$fld=(int)$fields_arr[$i];	
                    }
                    else
                    {
                        $fld=$fields_arr[$i];
                    }
                    if ($i==0)
                    {
                        $fields.="$char$fld$char";
                    } else $fields.=",$char$fld$char";   
                }
                return $fields;
            }
            else
            {
                return " * ";
            }            
        } 
          
        /**
        * ��������� MySQL-������ ��� ������ SQL
        *      
        * @param String $string SQL-������
        * @throws Exception ��������� � ��������� �������
        * @return Resource
        */
        public function query($string)
        {      
            $this->_internalQuery=$string;     
            if ($this->debugging || self::$globalDebugging)
            {
                $this->debugToFile($string);
                $this->_queryCounter++;
            }
            $resource=mysql_query($string);
            if ($resource==false)
            {
                throw new MySQLException($this,"BAD QUERY: $string");
            }
            $this->_internalResource=$resource;
            return $resource;
        }
        
        private function debugToFile($queryString)
        {
        	if ($this->_debugFile==null)
        	{
        		$this->_debugFile=new TextFile(self::$_debugPath);
        		$this->_debugFile->open("a+");
        		list($this->_startMikrotime, $sec) = explode(" ", microtime(true));
        		$this->_debugFile->writeLine("START SQL DEBUG ".date("Y-m-d H:i:s.B")." (start time: ".$this->_startMikrotime." �s )");
        	}
        	list($newMikrotime,$sec)=explode(" ", microtime(true));
        	$this->_debugFile->writeLine("# ".$this->_queryCounter." (".$newMikrotime." : ".$this->_startMikrotime.")   ".$queryString);
        }
        
        /**
        * ��������� ������ ������� ������ �� ������� ��
        * 
        * @param mixed $resource ������ ��
        * @return array
        */
        public function fetchArr($resource=NULL)
        {
            $res=NULL;
            if ($resource==NULL)
            {
                $res=$this->_internalResource;
            }
            else
            {
                $res=$resource;
            }
            return mysql_fetch_assoc($res);
        }
        
        public function getServerName()
        {
        	return $this->_server;
        }
        
        public function getResource()
        {
        	return $this->_internalResource;
        }    
    }
?>