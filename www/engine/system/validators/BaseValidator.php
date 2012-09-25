<?
	abstract class BaseValidator
	{
		protected $_isError;

		protected $_errorMessage;

		public static function createValidator($validatorName,$params=null)
		{
			$className = ucfirst(strtolower($validatorName)).'Validator';
			$validator = new $className;
			if (is_array($params))
			{
				foreach ($params as $param => $value) {
					$validator->$param = $value;
				}
			}
			return $validator;
		}
	}