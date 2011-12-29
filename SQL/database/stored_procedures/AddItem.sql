DROP PROCEDURE IF EXISTS AddItem;
CREATE PROCEDURE AddItem(IN UserID INT, IN ProjectID INT, IN AssignedTo INT, IN PriorityLevel VARCHAR(1), IN StatusValue VARCHAR(50), IN `CreateDateTime` DATETIME, IN Title VARCHAR(255), IN Kind VARCHAR(50), IN Description TEXT, IN ItemType VARCHAR(50), IN StepsText TEXT)
BEGIN
    DECLARE LAST_ID INT;
    INSERT INTO ITEM 
        (
            USER_ID,
            PROJ_ID,
            PRTY_LVL,
            STAT,
            CRT_TM,
            TITLE,
            KIND,
            DESCR,
            ASSGN_TO
        ) 
        VALUES
        (
            UserID,
            ProjectID,
            PriorityLevel,
            StatusValue,
            `CreateDateTime`,
            Title,
            Kind,
            Description,
            AssignedTo
        );

    SET LAST_ID=(SELECT last_insert_id() FROM ITEM LIMIT 0,1);

    IF Kind = 'Defect' THEN
        INSERT INTO ITEM_DEFECT (ITEM_DEFECT_ID,DEFECT_TYP,STP_TXT) 
            VALUES (LAST_ID,ItemType,StepsText);
    END IF; 
END