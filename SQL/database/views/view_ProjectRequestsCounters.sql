CREATE VIEW view_ProjectRequestsCounters
AS
SELECT 
    P.ProjectID AS ProjectID, 
    P.`Name` AS `Name`, 
    P.Description AS Description, 
    P.OwnerID AS OwnerID, 
    P.OwnerNickName AS OwnerNickName, 
    P.CreateDate AS CreateDate, 
    P.CountUsers AS CountUsers, 
    count(S.PROJ_ID) AS CountRequests
FROM
    view_ProjectUsersCount P
    LEFT JOIN SUBSCR_RQST S
        ON P.ProjectID = S.PROJ_ID
GROUP BY
    P.ProjectID;