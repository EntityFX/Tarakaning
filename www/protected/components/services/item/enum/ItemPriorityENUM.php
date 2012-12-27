<?php

final class ItemPriorityENUM extends AEnum {

    const MINIMAL   = 0;
    const NORMAL    = 1;
    const HIGH      = 2;
    
    public function __construct($value = self::NORMAL) {
        parent::__construct($value);
    }
}

?>
