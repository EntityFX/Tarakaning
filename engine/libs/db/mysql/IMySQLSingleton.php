<?php
    /**
    * ������������ ��� ���������� ��������� � MySQL �������
    */
    interface IMySQLSingleton
    {
        /**
        * ���������� ��������� ���������
        * 
        */
        public static function &getInstance($server,$user,$password);
    }
?>
