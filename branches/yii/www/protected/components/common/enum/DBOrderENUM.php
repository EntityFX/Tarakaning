<?php

final class DBOrderENUM extends AEnum {

    const ASC = "ASC";
    const DESC = "DESC";

    public function __construct($value = self::ASC) {
        $this->__value = $value;
    }

}

?>
