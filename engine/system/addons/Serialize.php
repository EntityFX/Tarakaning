<?php
	/**
	 * 
	 * �������� ��������� ��������� ������������
	 * @author ����
	 *
	 */
	class Serialize
	{
		/**
		 * 
		 * ����������� ��� �������� � �������� ���������
		 * @param array $array
		 */
		public static function SerializeForStoredProcedure($array)
		{
			if ($array!=null)
			{
				foreach($array as $key => $value)
				{
					$str.=$key.';';
				}
			}
			return $str;
		}
	}