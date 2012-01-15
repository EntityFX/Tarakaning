CREATE PROCEDURE DeleteCommentsFromList(IN _UserID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString INT;
    DECLARE ItemValue INT;

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

    CREATE TEMPORARY TABLE CommentsForDelete (
        ItemID INT
    );

    INSERT INTO CommentsForDelete SELECT ITEM_CMMENT_ID FROM 
        ITEM_CMMENT RC
    INNER JOIN  ItemsTable I
        ON RC.ITEM_CMMENT_ID=I.ItemID
    WHERE USER_ID=_UserID;

    DELETE FROM ITEM_CMMENT WHERE ITEM_CMMENT_ID IN (SELECT ItemID FROM CommentsForDelete);

END