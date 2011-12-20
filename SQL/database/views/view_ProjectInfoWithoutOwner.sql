CREATE VIEW view_ProjectInfoWithoutOwner AS SELECT
    P.ProjectID AS ProjectID,
    P.ProjectName AS ProjectName,
    P.Description AS Description,
    P.ProjectOwnerID AS ProjectOwnerID,
    P.OwnerNickName AS OwnerNickName,
    P.CreateDateTime AS CreateDateTime,
    P.CountSubscribeRequests AS CountSubscribeRequests,
    P.CountUsers AS CountUsers,
    P.`NEW` AS `NEW`,
    P.`IDENTIFIED` AS `IDENTIFIED`,
    P.ASSESSED AS ASSESSED,
    P.RESOLVED AS RESOLVED,
    P.CLOSED AS CLOSED,
    U.USER_ID AS UserID
FROM
    USER_IN_PROJ U
    JOIN view_ProjectAndErrors P
        ON P.ProjectID = U.PROJ_ID;