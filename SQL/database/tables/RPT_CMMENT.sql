CREATE TABLE ITEM_CMMENT (
    ITEM_CMMENT_ID      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    ITEM_ID             INT(11) NOT NULL,
    USER_ID             INT(11) UNSIGNED DEFAULT NULL,
    CRT_TM              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CMMENT              VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (ITEM_CMMENT_ID),
    INDEX IX__ITEM_CMMENT__ITEM_ID (ITEM_ID),
    INDEX IX__ITEM_CMMENT__USER_ID (USER_ID),
    CONSTRAINT FK__ITEM_CMMENT__ITEM__ITEM_ID FOREIGN KEY (ITEM_ID)
        REFERENCES ITEM (ITEM_ID) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FK__ITEM_CMMENT__USER__USER_ID FOREIGN KEY (USER_ID)
        REFERENCES `USER` (USER_ID)
)
ENGINE = INNODB
