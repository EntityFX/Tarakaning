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
        public static function &creator($server,$user,$password);
    }
?>
