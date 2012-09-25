<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
	class Orderer
	{
		private $_orderName;
		
		private $_direction;
		
		private $_supportedFields;
		
		private $_getName;
		
		public function __construct(AEnum $supportedFieldsEnum,$getName='orderBy')
		{
		    $defaultField=$supportedFieldsEnum->getValue();
		    $this->_getName=$getName;
			if (isset($_GET[$getName]))
            {
                $getVal=$_GET[$getName];
            }
            else
            {
                $getVal=$defaultField.';'.'asc';
                $_GET[$getName]=$getVal;   
            }
            list($this->_orderName,$this->_direction)=explode(';', $getVal);
            $this->_supportedFields=$supportedFieldsEnum->getArray();
		    if (!in_array($this->_orderName, $this->_supportedFields))
            {
                $this->_orderName=$defaultField;
            }
		}
		
		public function getOrderField()
		{
			return $this->_orderName;
		}
		
		public function getOrder()
		{
			switch (strtoupper($this->_direction))
			{
				case "ASC":
					return "ASC";
					break;
				case "DESC":
					return "DESC";
					break;
				default: 
					return "ASC";
			}
		}
		
		public function getMySQLOrderDirection()
		{
			return new MySQLOrderENUM($this->getOrder());
		}
		
		public function getNewUrls()
		{
			foreach($this->_supportedFields as $value)
			{
				$order=false;
				if ($this->_orderName==$value)
				{
					$order=true;
					$dir=$this->_direction=='asc'?'desc':'asc';
				}
				else 
				{
					$dir='asc';
				}
				$arr[$value]=array(
					'url' => $this->getLinkParamsString($value.';'.$dir),
					'direction' => $dir,
					'order' => $order
				);
			}
			return $arr;
		}
		
	    protected function getLinkParamsString($val)
        {
            if ($_GET!=null)
            {
                $first=0;
                foreach($_GET as $getParamKey => $getParamVal)
                {
                	if ($first!=0)
                    {
                        $str.="&amp;";
                    }
                    $first++;
                    if ($getParamKey!='')
                    {
	                    if ($getParamKey==$this->_getName)
	                    {
	                    	$str.=$getParamKey."=".$val;     
	                    }
	                    else
	                    {
	                        $str.=$getParamKey."=".$getParamVal; 
	                    }
                    }
                }
            	$str.=$this->_idTag !="" ? "#". $this->_idTag : "";
                return "?$str";
            }
        }
	}