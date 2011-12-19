CREATE TABLE DEFECT (
    ID INT(11) NOT NULL,
    DEFECT_TYP ENUM('Crash', 'Cosmetic', 'Error Handling', 'Functional', 'Minor', 'Major', 'Setup', 'Block') NOT NULL,
    STP_TXT TEXT NOT NULL,
    PRIMARY KEY (ID),
    CONSTRAINT FK_DefectItem_ErrorReport_ID FOREIGN KEY (ID)
    REFERENCES ERR_RPT (ID) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB