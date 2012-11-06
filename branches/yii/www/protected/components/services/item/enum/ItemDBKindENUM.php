<?php

final class ItemDBKindENUM extends AEnum {

    const DEFECT = 'Defect';
    const TASK = 'Task';
    
    public function __construct($value = self::TASK) {
        parent::__construct($value);
    }

}

