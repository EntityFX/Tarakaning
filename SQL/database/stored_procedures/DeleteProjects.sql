DROP PROCEDURE IF EXISTS DeleteProjects;
CREATE PROCEDURE DeleteProjects(IN _UserID INT, IN ItemsList TEXT)
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

    CREATE TEMPORARY TABLE ProjectsForDelete (
        ItemID INT
    );

    INSERT INTO ProjectsForDelete SELECT PROJ_ID FROM 
        PROJ P
    INNER JOIN ItemsTable I
        ON P.PROJ_ID=I.ItemID
    WHERE P.USER_ID=_UserID;

    DELETE FROM PROJ WHERE PROJ_ID IN (SELECT ItemID FROM ProjectsForDelete);

END