CREATE TABLE PROJ (
    PROJ_ID         INT(11) NOT NULL AUTO_INCREMENT,
    PROJ_NM         VARCHAR(100) NOT NULL,
    DESCR           TINYTEXT DEFAULT NULL,
    USER_ID         INT(11) UNSIGNED DEFAULT NULL,
    CRT_TM          DATETIME NOT NULL,
    PRIMARY KEY (PROJ_ID),
    INDEX IX__PROJ__USER_ID (USER_ID),
    INDEX IX__PROJ__PROJ_NM (PROJ_NM),
    CONSTRAINT FK__PROJ__USER__USER_ID FOREIGN KEY (USER_ID)
        REFERENCES `USER` (USER_ID) ON DELETE SET NULL ON UPDATE SET NULL
)
ENGINE = INNODB;