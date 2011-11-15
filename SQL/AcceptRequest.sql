CREATE DEFINER = 'root'@'localhost'
PROCEDURE Tarakaning.AcceptRequest(IN _ProjectID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString     INT;
    DECLARE ItemValue      INT;

    DECLARE sID            INT;
    DECLARE UserID         INT;

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
        SELECT SR.ID
             , SR.UserID
        FROM
            SubscribesRequest SR
        INNER JOIN ItemsTable I
            ON SR.ID = I.ItemID
        WHERE
            SR.ProjectID = _ProjectID;

    OPEN ItemsCursor;

    WHILE CursorEnd = 0
    DO
        FETCH ItemsCursor INTO sID, UserID;
        IF UserID IS NOT NULL THEN
            SELECT UserID;
            DELETE FROM SubscribesRequest WHERE ID = sID;
            INSERT INTO UsersInProjects VALUES(0,_ProjectID,UserID);
        END IF;
    END WHILE;
    CLOSE ItemsCursor;

END