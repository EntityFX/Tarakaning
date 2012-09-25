CREATE VIEW view_ProjectAndErrors AS SELECT
    P.ProjectID AS ProjectID,
    P.ProjectName AS ProjectName,
    P.Description AS Description,
    P.ProjectOwnerID AS ProjectOwnerID,
    P.OwnerNickName AS OwnerNickName,
    P.CreateDateTime AS CreateDateTime,
    P.CountSubscribeRequests AS CountSubscribeRequests,
    P.CountUsers AS CountUsers,
    COUNT((CASE WHEN (I.STAT = _cp1251 'NEW') THEN _utf8 'NEW' ELSE NULL END)) AS `NEW`,
    COUNT((CASE WHEN (I.STAT = _cp1251 'IDENTIFIED') THEN _utf8 'IDENTIFIED' ELSE NULL END)) AS `IDENTIFIED`,
    COUNT((CASE WHEN (I.STAT = _cp1251 'ASSESSED') THEN _utf8 'ASSESSED' ELSE NULL END)) AS ASSESSED,
    COUNT((CASE WHEN (I.STAT = _cp1251 'RESOLVED') THEN _utf8 'RESOLVED' ELSE NULL END)) AS RESOLVED,
    COUNT((CASE WHEN (I.STAT = _cp1251 'CLOSED') THEN _utf8 'CLOSED' ELSE NULL END)) AS CLOSED
FROM
    (view_ProjectUsersAndSubscribes P
    LEFT JOIN ITEM I
        ON ((I.PROJ_ID = P.ProjectID)))
GROUP BY
    P.ProjectID