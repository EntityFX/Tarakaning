<?php
/**
* ���� � ������� ��� ������ � MySQL.
* @package MySQL
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/

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
                throw new Exception(NO_CONNECTION);                
            }
            $this->_server=$server;
            $this->_user=$user;
            $this->_password=$password;
            return true; 
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
        public function CreateTableTemplate($table_name,&$fields)
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
            } else throw new Exception(MySQLquery::EXCEPTION_NO_PRIMARY_KEY);
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
        public function InsertIntoTemplate($table_name,&$values,&$fields=null)
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
                    $fld=$fields_arr[$i];
                    if (is_string($fld) && $char!="`")
                    {
                        $fld="'$fld'";
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
            
            if ($this->debugging || self::$globalDebugging)
            {
                echo $string."\n\r";
            }
            $resource=mysql_query($string);
            if ($resource==false)
            {
                throw new Exception("\n\rENGINE: BAD QUERY. QUERY is $string\n\r");
            }
            $this->_internalResource=$resource;
            return $resource;
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
    }
?>