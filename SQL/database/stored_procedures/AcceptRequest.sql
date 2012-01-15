CREATE PROCEDURE AcceptRequest(IN _ProjectID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString     INT;
    DECLARE ItemValue      INT;

    DECLARE sID            INT;
    DECLARE UserID         INT;


    DECLARE CursorCounter  INT DEFAULT 1;
    DECLARE CountItems     INT;
    DECLARE CursorEnd      INT DEFAULT 0;
    DECLARE ItemsCursor CURSOR FOR
    SELECT *
    FROM
        SubscribesToAssign;

    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET CursorEnd = 1;

    SET SymbolPosition = 1;

    CREATE TEMPORARY TABLE ItemsTable(
        ItemID INT
    );

    CREATE TEMPORARY TABLE SubscribesToAssign(
        ID INT,
        UserID INT,
        PRIMARY KEY (ID)
    );

-- Parsing incoming data
    z1:
    WHILE SymbolPosition > 0
    DO
        SELECT locate(';', ItemsList, SymbolPosition)
        INTO
            SymbolPosition;
        SELECT substring(ItemsList, SymbolPosition + 1, locate(';', ItemsList, SymbolPosition + 1) - SymbolPosition - 1)
        INTO
            ItemString;
        SELECT cast(ItemString AS SIGNED)
        INTO
            ItemValue;
        INSERT INTO ItemsTable VALUES (ItemValue);
        IF SymbolPosition = 0 THEN
            LEAVE z1;
        END IF;
        SET SymbolPosition = SymbolPosition + 1;
    END WHILE;

    -- Populate Subscribes to assign
    INSERT INTO SubscribesToAssign
        SELECT 
            SR.SUBSCR_RQST_ID, 
            SR.USER_ID
        FROM
            SUBSCR_RQST SR
        INNER JOIN ItemsTable I
            ON SR.SUBSCR_RQST_ID = I.ItemID
        WHERE
            SR.PROJ_ID = _ProjectID;

    SELECT count(*) INTO CountItems FROM SubscribesToAssign;

    OPEN ItemsCursor;
    START TRANSACTION;

    WHILE CursorEnd = 0
    DO
        FETCH ItemsCursor INTO sID, UserID;
        IF CursorCounter <= CountItems THEN
            DELETE FROM SUBSCR_RQST WHERE SUBSCR_RQST_ID = sID;
            INSERT INTO USER_IN_PROJ VALUES(0,_ProjectID,UserID);
        END IF;
        SET CursorCounter = CursorCounter + 1;
    END WHILE;

    COMMIT;
    CLOSE ItemsCursor;

END