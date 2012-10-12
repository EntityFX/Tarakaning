<?php

class SubscribesDetailENUM extends AEnum {

    const PROJECT_NAME = "ProjectName";
    const PROJECT_OWNER = "ProjectOwner";
    const REQUEST_TIME = "RequestTime";

    public function __construct($value = self::PROJECT_NAME) {
        parent::__construct($value);
    }

}