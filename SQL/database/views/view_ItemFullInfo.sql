CREATE VIEW view_ItemFullInfo
AS
SELECT 
    I.ITEM_ID AS `ID`, 
    I.KIND AS `Kind`, 
    I.USER_ID AS `UserID`,
    I.ASSGN_TO AS `AssignedTo`,
    I.PROJ_ID AS `ProjectID`,
    I.PRTY_LVL AS `PriorityLevel`,
    I.STAT AS `Status`,
    I.CRT_TM AS `CreateDateTime`,
    I.TITLE AS `Title`,
    IDF.DEFECT_TYP AS `ErrorType`,
    I.DESCR AS `Description`,
    IDF.STP_TXT AS `StepsText`,
    P.PROJ_NM AS `ProjectName`,
    U.NICK AS `NickName`,
    U1.NICK AS `AssignedNickName`
FROM
    ITEM I
    JOIN PROJ P
        ON I.PROJ_ID = P.PROJ_ID
    LEFT JOIN `USER` U
        ON I.USER_ID = U.USER_ID
    LEFT JOIN `USER` U1
        ON I.ASSGN_TO = U1.USER_ID
    LEFT JOIN ITEM_DEFECT IDF
        ON I.ITEM_ID = IDF.ITEM_DEFECT_ID;