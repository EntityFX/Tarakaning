CREATE VIEW view_ProjectUsersCount AS
SELECT 
    P.PROJ_ID AS ProjectID, 
    P.PROJ_NM AS `Name`,
    left(P.DESCR, 25) AS Description, 
    P.USER_ID AS OwnerID, 
    U.NICK AS NickName, 
    P.CRT_TM AS CreateDate, 
    (count(UP.PROJ_ID) + (P.USER_ID IS NOT NULL)) AS CountUsers
FROM
    PROJ P
    LEFT JOIN USER_IN_PROJ UP
        ON UP.PROJ_ID = P.PROJ_ID
    LEFT JOIN USER U
        ON P.USER_ID = U.USER_ID
GROUP BY
    P.PROJ_ID;