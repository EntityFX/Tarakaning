<?php
	class Language
	{
		private static $_sql;
		
		/**
		 * 
		 * �������� ����� ��� �������� �����
		 * @param int $langCode ��� �����
		 * @param int $moduleId ID ������
		 * @param string $default ����������, ���� ����� �� ������
		 */
		public static function getText($langCode,$moduleId,$default="")
		{
			self::setSql();
			$langCode=(int)$langCode;
			$moduleId=(int)$moduleId;
			self::$_sql->selAllWhere("LocationsText","MOD_ID=$moduleId AND LOC_ID=$langCode");
			$data=self::$_sql->getTable();
			if ($data!=NULL)
			{
				return $data[0]["MOD_TXT"];
			}
			else
			{
				return $default;
			}
		}
		
		private static function setSql()
		{
			if (self::$_sql==null)
			{
				self::$_sql=DBConnector::getInstance()->getDB();
			}
		}
		
		public static function getLangName($code)
		{
			self::setSql();
			$code=(int)$code;
			self::$_sql->selFieldsWhere("Locations","LANG_ID=$code","LANG_NAME");
			$data=self::$_sql->getTable();
			if ($data==null)
			{
				throw new Exception("No language for this code");
			}
			else
			{
				return $data[0]["LANG_NAME"];
			}
		}
	}