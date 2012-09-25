<?
	require_once "IValidator.php";

	require_once "BaseValidator.php";

	class EmailValidator extends BaseValidator implements IValidator
	{
		const EMAIL_PATTERN = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

		public function getError()
		{
			return $this->_isError ? $this->_errorMessage : false;
		}

		public function validate($value)
		{
			$result = true;
			if (!(strlen($value)<=255 && preg_match(self::EMAIL_PATTERN, $value)))	
			{
				$this->_errorMessage = "Неверная почта";
				$result = false;
			}
			$this->_isError = !$result;
			return $result;
		}
	}