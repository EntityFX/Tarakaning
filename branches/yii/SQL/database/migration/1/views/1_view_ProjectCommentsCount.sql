DROP VIEW IF EXISTS view_ProjectCommentsCount;
CREATE VIEW view_ProjectCommentsCount AS SELECT
    UP.ProjectID AS ProjectID,
    UP.UserID AS UserID,
    IFNULL(SUM(EC.ItemUserComment), 0) AS CountComments
FROM
    view_AllUserProjects UP
    LEFT JOIN view_ErrorCommentsCount EC
        ON UP.ProjectID = EC.ProjectID AND UP.UserID = EC.UserID
GROUP BY
    UP.UserID,
    UP.ProjectID;