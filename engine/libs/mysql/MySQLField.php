<?php
/**
* ���� c ������� MySQLField.
* @package MySQL
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur)
*/
    
    /**
    * ����� ����������� �����
    * @package MySQL
    * @author Solopiy Artem    
    */
    class MySQLField extends MySQLTypeEnumerator
    {               
        /**
        * ��������� � ������������ ���� ����
        */
        const EXCEPTION_WRONG_TYPE="Wrong type of field";
        
        /**
        * ��� ����
        * 
        * @var String
        */
        public $name;
        
        /**
        * ��� ������������ ����
        *  
        * @var MySQLTypeEnumerator
        */
        public $type;
        
        /**
        * ������������ ����� ��� ���������� ����
        * 
        * @var String
        */
        public $max_length="255";
        
        /**
        * �������� NOT NULL
        * 
        * @var Bool
        */
        public $not_null;
        
        /**
        * �������� ��������
        * 
        * @var Bool
        */
        public $primary_key;
        
        /**
        * �������� auto_increment
        * 
        * @var Bool
        */        
        public $auto_inc;
        
        /**
        * �����������
        * @param String $name ��� ����
        * @param MySQLTypeEnumerator $type ��� ����
        * @param Bool $not_null �������� NOT NULL
        * @param Bool $primary_key �������� ��������
        * @param Bool $auto_inc �������� auto_increment 
        * @return MySQLField
        */
        public function __construct($name,$type,$not_null=false,$primary_key=false,$auto_inc=false)
        {
            $this->name=$name;
            switch ($type)
            {
                case MySQLField::INT:
                    $this->type=$type;
                    break;
                case MySQLField::SMALLINT:
                    $this->type=$type;
                    break;
                case MySQLField::FLOAT:
                    $this->type=$type;
                    $this->max_length="8,4";
                    break;
                case MySQLField::DATETIME:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::DATE:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::TIME:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::MEDIUMTEXT:
                    $this->type=$type;
                    break;
                case MySQLField::TEXT:
                    $this->type=$type;
                    break;
                case MySQLField::VARCHAR:
                    $this->type=$type;
                    break;
                case MySQLField::BLOB:
                    $this->type=$type;
                    $this->max_length="";
                    break;
                case MySQLField::MEDIUMBLOB:
                    $this->type=$type;
                    $this->max_length="";
                    break;                    
                default :
                    throw new Exception(MySQLField::EXCEPTION_WRONG_TYPE);
                    break;
            }
            $this->not_null=$not_null;
            $this->primary_key=$primary_key;
            $this->auto_inc=$auto_inc;    
        }
        
        /**
        * ��������� ������ �������� ���� � �������
        * @return String
        */
        public function GetFieldString()
        {
            $str="`".$this->name."` ".$this->type." ";
            if ($this->max_length!="")   
            {
                $str.="(".$this->max_length.") ";    
            }
            if ($this->not_null) $str.="NOT NULL ";    
            else $str.="NULL ";
            if ($this->auto_inc) $str.="auto_increment ";
            return $str;           
        }   
    }
?>