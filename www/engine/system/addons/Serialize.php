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
		static public function SerializeForStoredProcedure($array)
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
		
		/**
		 * 
		 * ����������� ��� IN ��������� � SQL �������
		 * @param array $array
		 */
		static public function serializeForINStatement($array)
		{
			$indexMax=count($array)-1;
			if ($indexMax>=0)
			{
				$str='(';
				foreach($array as $key => $value)
				{
					$str.=$value;
					if ($key<$indexMax) $str.=',';
				}
				$str.=')';
			}
			return $str;
		} 
	}