<?php

final class ItemKindENUM extends AEnum {

    const ALL = "All";
    const DEFECT = "Defect";
    const TASK = "Task";

    public function __construct($value) {
        $this->__value = $value;
        if (!$this->check()) {
            $this->__value = self::ALL;
        }
    }

}

