<?php

final class ItemCommentsENUM extends AEnum {

    const NICK_NAME = "NickName";
    const TIME = "Time";
    const COMMENT = "Comment";

    public function __construct($value = self::TIME) {
        parent::__construct($value);
    }

}

?>