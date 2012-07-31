DROP PROCEDURE IF EXISTS DeleteItemsFromList;
CREATE PROCEDURE DeleteItemsFromList(IN _UserID INT, IN _ProjectID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString INT;
    DECLARE ItemValue INT;

    DECLARE _OwnerID INT;

    SET SymbolPosition=1;

    CREATE TEMPORARY TABLE ItemsTable (
        ItemID INT
    );

    -- Parsing incoming data
    z1: WHILE SymbolPosition>0 DO
        SELECT LOCATE(';',ItemsList,SymbolPosition) INTO SymbolPosition;
        SELECT SUBSTRING(ItemsList,SymbolPosition+1,LOCATE(';',ItemsList,SymbolPosition+1)-SymbolPosition-1) INTO ItemString;
        SELECT CAST(ItemString AS SIGNED ) INTO ItemValue;
        INSERT INTO ItemsTable VALUES (ItemValue);
        IF SymbolPosition=0 THEN 
            LEAVE z1;
        END IF;
        SET SymbolPosition=SymbolPosition+1;
    END WHILE;

    SELECT USER_ID INTO _OwnerID FROM PROJ WHERE PROJ_ID=_ProjectID;

    CREATE TEMPORARY TABLE ItemsForDelete (
        ItemID INT
    );

    /*
    Is current user project owner
    */
    IF _UserID=_OwnerID THEN
        INSERT INTO ItemsForDelete (ItemID) (SELECT 
                I.ITEM_ID
            FROM 
                ITEM I
            INNER JOIN ItemsTable T
                ON I.ITEM_ID=`T`.ItemID
            WHERE I.PROJ_ID=_ProjectID);

    ELSE
        INSERT INTO ItemsForDelete (ItemID) (SELECT 
                I.ITEM_ID
            FROM 
                ITEM I
            INNER JOIN ItemsTable T
                ON I.ITEM_ID=`T`.ItemID
            WHERE I.USER_ID=_UserID AND I.PROJ_ID=_ProjectID);
    END IF;

    DELETE FROM ITEM WHERE ITEM_ID IN (SELECT ItemID FROM ItemsForDelete);

END;