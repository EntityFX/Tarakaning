<?php
/**
 * Description of SimpleArrayDataProvider
 *
 * @author EntityFX
 */
class SimpleArrayDataProvider extends CDataProvider {
    /**
     * @var string sort field 
     */
    private $sortField;
    
    /**
     *
     * @var string direction of sort 
     */
    private $sortDirection;
    
    
    /**
     * @var string the name of key field. Defaults to 'id'. If it's set to false,
     * keys of $rawData array are used.
     */
    public $keyField = 'id';
    public $rawData;

    protected function fetchData() {
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->setItemCount($this->getTotalItemCount());
        }
        return $this->rawData;
    }

    /**
     * Fetches the data item keys from the persistent data storage.
     * @return array list of data item keys.
     */
    protected function fetchKeys() {
        if ($this->keyField === false)
            return array_keys($this->rawData);
        $keys = array();
        foreach ($this->getData() as $i => $data)
            $keys[$i] = is_object($data) ? $data->{$this->keyField} : $data[$this->keyField];
        return $keys;
    }

    /**
     * Calculates the total number of data items.
     * @return integer the total number of data items.
     */
    protected function calculateTotalItemCount() {
        return count($this->rawData);
    }

    /**
     * Constructor.
     * @param array $count Total items within the data provider.
     * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
     */
    public function __construct($count, $config = array()) {
        foreach ($config as $key => $value)
            $this->$key = $value;
        $this->setTotalItemCount((int)$count);
        $this->getPagination()->setItemCount($this->getTotalItemCount());
        list($this->sortField, $this->sortDirection) = explode(' ',$this->getSort()->getOrderBy());
    }
    
    /**
     * Returns field name for sort
     * @return string
     */
    public function getSortField() {
        return $this->sortField;
    }
    
    /**
     * Redturns direction of sort
     * @return string value of DBOrderENUM
     */
    public function getSortDirection() {
        return $this->sortDirection === null ? DBOrderENUM::ASC : DBOrderENUM::DESC;
    }

}
