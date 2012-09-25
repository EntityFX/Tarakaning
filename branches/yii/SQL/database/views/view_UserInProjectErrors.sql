CREATE VIEW view_UserInProjectErrors AS
SELECT 
    P.USER_ID AS UserID, 
    P.PROJ_ID AS ProjectID, 
    U.NICK AS NickName, 
    count((
       CASE
       WHEN (I.STAT = _cp1251 'NEW') THEN
           _utf8 'NEW'
       ELSE
           NULL
       END)) AS `NEW`, 
    count((
        CASE
        WHEN (I.STAT = _cp1251 'IDENTIFIED') THEN
           _utf8 'IDENTIFIED'
        ELSE
           NULL
        END)) AS `IDENTIFIED`,
    count((
        CASE
        WHEN (I.STAT = _cp1251 'ASSESSED') THEN
           _utf8 'ASSESSED'
        ELSE
           NULL
        END)) AS `ASSESSED`, 
    count((
        CASE
        WHEN (I.STAT = _cp1251 'RESOLVED') THEN
           _utf8 'RESOLVED'
        ELSE
           NULL
        END)) AS `RESOLVED`, 
    count((
        CASE
        WHEN (I.STAT = _cp1251 'CLOSED') THEN
           _utf8 'CLOSED'
        ELSE
           NULL
        END)) AS `CLOSED`,
    count(I.ITEM_ID) AS CountErrors,
    1 AS `Owner`
FROM
    PROJ P
LEFT JOIN `USER` U
    ON P.USER_ID = U.USER_ID
LEFT JOIN ITEM I
    ON P.PROJ_ID = I.PROJ_ID AND P.USER_ID = I.ASSGN_TO
GROUP BY
    P.PROJ_ID,
    P.USER_ID,
    U.USER_ID, 
    I.ASSGN_TO

UNION

SELECT 
    UP.USER_ID AS UserID, 
    UP.PROJ_ID AS ProjectID, 
    U.NICK AS NickName,
    count((
       CASE
       WHEN (I.STAT = _cp1251 'NEW') THEN
           _utf8 'NEW'
       ELSE
           NULL
       END)) AS `NEW`, 
    count((
        CASE
        WHEN (I.STAT = _cp1251 'IDENTIFIED') THEN
           _utf8 'IDENTIFIED'
        ELSE
           NULL
        END)) AS `IDENTIFIED`,
    count((
        CASE
        WHEN (I.STAT = _cp1251 'ASSESSED') THEN
           _utf8 'ASSESSED'
        ELSE
           NULL
        END)) AS `ASSESSED`, 
    count((
        CASE
        WHEN (I.STAT = _cp1251 'RESOLVED') THEN
           _utf8 'RESOLVED'
        ELSE
           NULL
        END)) AS `RESOLVED`, 
    count((
        CASE
        WHEN (I.STAT = _cp1251 'CLOSED') THEN
           _utf8 'CLOSED'
        ELSE
           NULL
        END)) AS `CLOSED`,
    count(I.ITEM_ID) AS CountErrors,
    0 AS `Owner`
FROM
    USER_IN_PROJ UP
LEFT JOIN `USER` U
    ON UP.USER_ID = U.USER_ID
LEFT JOIN ITEM I
    ON UP.PROJ_ID = I.PROJ_ID AND UP.USER_ID = I.ASSGN_TO
GROUP BY
    UP.PROJ_ID, 
    UP.USER_ID