<?php

final class ItemStatusENUM extends AEnum {

    const IS_NEW = "NEW";
    const IDENTIFIED = "IDENTIFIED";
    const ASSESSED = "ASSESSED";
    const RESOLVED = "RESOLVED";
    const CLOSED = "CLOSED";

    public function __construct($value = self::IS_NEW) {
        parent::__construct($value);
    }

    public function getNormalized() {
        return array(
            self::IS_NEW => "Новый",
            self::IDENTIFIED => "Идентифицирован",
            self::ASSESSED => "В процессе",
            self::RESOLVED => "Решён",
            self::CLOSED => "Закрыт"
        );
    }

    public function getStates(ItemStatusENUM $current, $canClose) {
        $array = $this->getNormalized();
        $currentValue = $current->getValue();
        while ($value = current($array)) {
            $key = key($array);
            $res[$key] = $value;
            if ($currentValue == $key)
                break;
            next($array);
        }
        if ($key != self::CLOSED) {
            $nextValue = next($array);
            if (key($array) == self::CLOSED) {
                if ($canClose)
                    $res[key($array)] = $nextValue;
            }
            else {
                $res[key($array)] = $nextValue;
            }
        }
        return $res;
    }

    public function getLocaleValue() {
        switch ($this->getValue()) {
            case ItemStatusENUM::IS_NEW:
                $res = "Новый";
                break;
            case ItemStatusENUM::IDENTIFIED:
                $res = "Идентифицирован";
                break;
            case ItemStatusENUM::ASSESSED:
                $res = "В процессе";
                break;
            case ItemStatusENUM::RESOLVED:
                $res = "Решён";
                break;
            case ItemStatusENUM::CLOSED:
                $res = "Закрыт";
                break;
        }
        return $res;
    }

    public function getNumberedKeys() {
        $array = $this->getArray();
        foreach ($array as $value) {
            $res[] = $value;
        }
        return $res;
    }

}

?>
