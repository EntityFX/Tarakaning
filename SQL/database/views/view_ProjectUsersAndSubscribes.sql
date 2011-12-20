CREATE VIEW view_ProjectUsersAndSubscribes AS SELECT
    P.PROJ_ID AS ProjectID,
    P.PROJ_NM AS ProjectName,
    LEFT(P.DESCR, 25) AS Description,
    P.USER_ID AS ProjectOwnerID,
    U.NICK AS OwnerNickName,
    P.CRT_TM AS CreateDateTime,
    (COUNT(UP.PROJ_ID) + (CASE WHEN (P.USER_ID IS NOT NULL) THEN 1 ELSE 0 END)) AS CountUsers,
    COUNT(SR.PROJ_ID) AS CountSubscribeRequests
FROM
    PROJ P
    LEFT JOIN `USER` U
        ON P.USER_ID = U.USER_ID
    LEFT JOIN USER_IN_PROJ UP
        ON UP.PROJ_ID = P.PROJ_ID
    LEFT JOIN SUBSCR_RQST SR
        ON SR.PROJ_ID = P.PROJ_ID
GROUP BY
    P.PROJ_ID;