CREATE VIEW view_SubscribesDetails AS SELECT
    SR.ID       AS `ID`,
    SR.USER_ID  AS `UserID`,
    SR.PROJ_ID  AS `ProjectID`,
    SR.RQST_TM  AS `RequestTime`,
    P.PROJ_NM   AS `ProjectName`,
    P.USER_ID   AS `OwnerID`,
    u.NICK      AS `ProjectOwnerNick`
FROM
    SUBSCR_RQST SR
    INNER JOIN PROJ P
        ON SR.PROJ_ID = P.PROJ_ID
    LEFT JOIN `USER` U
        ON P.USER_ID = U.USR_ID;