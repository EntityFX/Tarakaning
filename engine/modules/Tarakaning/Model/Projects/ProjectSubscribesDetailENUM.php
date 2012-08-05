<?php

Loader::LoadSystem('AEnum');

class ProjectSubscribesDetailENUM extends AEnum {

    const NICK_NAME = "NickName";
    const REQUEST_TIME = "RequestTime";

    public function __construct($value = self::NICK_NAME) {
        parent::__construct($value);
    }

}