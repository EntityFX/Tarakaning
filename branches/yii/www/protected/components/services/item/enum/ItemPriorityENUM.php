<?php

final class ItemPriorityENUM extends AEnum {

    const MINIMAL   = 0;
    const NORMAL    = 1;
    const HIGH      = 2;

    public function getNormalized() {
        return array(
            self::MINIMAL   => "Низкий",
            self::NORMAL    => "Обычный",
            self::HIGH      => "Важный",
        );
    }

}

?>
