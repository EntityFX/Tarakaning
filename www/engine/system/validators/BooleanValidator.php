<?
	require_once "IValidator.php";

	require_once "BaseValidator.php";

	class BooleanValidator extends BaseValidator implements IValidator
	{
		const FALSE_VALUE = '0';
		const TRUE_VALUE = '1';

		const ERROR_MESSAGE = "Значение не является булевым";

		public $strict = true;


		public function getError()
		{
			return $this->_isError ? self::ERROR_MESSAGE : false;
		}

		public function validate($value)
		{
			$result = $strict 
					? $value === self::FALSE_VALUE || $value === self::TRUE_VALUE
					: $value == self::FALSE_VALUE || $value == self::TRUE_VALUE;

			$this->_isError = !$result;
			return $result;
		}
	}