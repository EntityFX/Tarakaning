CREATE VIEW view_ProjectAndOwnerNick
AS
SELECT 
    P.PROJ_ID  AS `ProjectID`,
    P.PROJ_NM AS `Name`,
    P.DESCR AS `Description`,
    P.USER_ID AS `OwnerID`,
    P.CRT_TM AS `CreateDate`,
    U.NICK AS OwnerNickName
FROM
    PROJ P
LEFT JOIN `USER` U
    ON P.USER_ID = U.USER_ID;