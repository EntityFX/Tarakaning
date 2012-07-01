DROP PROCEDURE IF EXISTS EditItem;
CREATE PROCEDURE EditItem(
	IN _ItemID INT, 
	IN _Title VARCHAR(255), 
	IN _PriorityLevel VARCHAR(1), 
	IN _StatusValue VARCHAR(50), 
	IN _AssignedTo INT, 
	IN _HourRequired INT,
	IN _AddHourFact INT,
	IN _Description TEXT, 
	IN _DefectType VARCHAR(50), 
	IN _StepsText TEXT)
BEGIN
    DECLARE ItemProjectID INT;
    DECLARE ItemKind VARCHAR(50);
    DECLARE AssignedProjectID INT;

    SELECT PROJ_ID, KIND INTO ItemProjectID,ItemKind FROM ITEM WHERE ITEM_ID=_ItemID;

    IF _AssignedTo!=NULL THEN
        SET AssignedProjectID=(SELECT PROJ_ID FROM USER_IN_PROJ WHERE USER_ID=_AssignedTo
        UNION
        SELECT PROJ_ID FROM PROJ WHERE USER_ID=_AssignedTo);

        IF ItemProjectID<>AssignedProjectID THEN 
            SET _AssignedTo=NULL;
        END IF;
    END IF;

    IF _Title<>'' THEN
        UPDATE ITEM SET 
            TITLE=_Title, 
            PRTY_LVL=_PriorityLevel,
            STAT=_StatusValue,
            DESCR=_Description,
            ASSGN_TO=_AssignedTo,
			HOUR_REQ=_HourRequired,
			HOUR_FACT=HOUR_FACT+_AddHourFact
        WHERE 
            ITEM_ID=_ItemID;
    
        IF ItemKind='Defect' THEN
            UPDATE ITEM_DEFECT SET DEFECT_TYP=_DefectType, STP_TXT=_StepsText WHERE ITEM_DEFECT_ID=_ItemID;
        END IF;
    END IF;
END