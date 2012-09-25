<?
	require_once "IValidator.php";

	require_once "BaseValidator.php";

	class RangeValidator extends BaseValidator implements IValidator
	{
		public $min = 0;

		public $max = 100;


		public function getError()
		{
			return $this->_isError ? $this->_errorMessage : false;
		}

		public function validate($value)
		{
			$result = true;
			if ($value < $this->min)	
			{
				$this->_errorMessage = "�������� �� ������ ���� ������ {$this->min}";
				$result = false;
			}
			if ($value > $this->max)	
			{
				$this->_errorMessage = "�������� �� ������ ��������� {$this->max}";
				$result = false;
			}
			$this->_isError = !$result;
			return $result;
		}
	}