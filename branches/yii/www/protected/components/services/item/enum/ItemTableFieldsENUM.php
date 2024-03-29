<?php

final class ItemTableFieldsENUM extends AEnum {

    const ID = "ITEM_ID";
    const USER_ID = "USER_ID";
    const PROJ_ID = "PROJ_ID";
    const KIND = "KIND";
    const PRTY_LVL = "PRTY_LVL";
    const STAT = "STAT";
    const CRT_TM = "CRT_TM";
    const TITLE = "TITLE";
    const DESCR = "DESCR";
    const ASSGN_TO = "ASSGN_TO";

    public function __construct($value = self::ID) {
        parent::__construct($value);
    }

}

?>