<?php
    /**
    * Используется для реализации синглтона в MySQL классах
    */
    interface IMySQLSingleton
    {
        /**
        * Возвращает инстанцию синглтона
        * 
        */
        public static function &getInstance($server,$user,$password);
    }
?>
