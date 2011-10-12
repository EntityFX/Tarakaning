<?php
	/**
	 * 
	 * Содержит различные алгоритмы сеарилизации
	 * @author Артём
	 *
	 */
	class Serialize
	{
		/**
		 * 
		 * Сериализует для передачи в хранимую процедуру
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