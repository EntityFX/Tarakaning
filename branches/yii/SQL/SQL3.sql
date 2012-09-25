CREATE VIEW ProjectUsersInfo AS 
SELECT
    UsersInProjects.ProjectID,
    UsersInProjects.UserID,
    Users.NickName,
    COUNT(ErrorReport.UserID) AS 'CountReports',
    COUNT(ReportComment.UserID) AS 'CountComments',
    COUNT(CASE WHEN ErrorReport.Status = 'NEW' THEN 'NEW' ELSE NULL END) AS New,
    COUNT(CASE WHEN ErrorReport.Status = 'CONFIRMED' THEN 'CONFIRMED' ELSE NULL END) AS Confirmed,
    COUNT(CASE WHEN ErrorReport.Status = 'ASSIGNED' THEN 'ASSIGNED' ELSE NULL END) AS Assigned,
    COUNT(CASE WHEN ErrorReport.Status = 'SOLVED' THEN 'SOLVED' ELSE NULL END) AS Solved,
    COUNT(CASE WHEN ErrorReport.Status = 'CLOSED' THEN 'CLOSED' ELSE NULL END) AS Closed
FROM
    UsersInProjects
    LEFT OUTER JOIN ErrorReport
        ON UsersInProjects.UserID = ErrorReport.UserID AND UsersInProjects.ProjectID = ErrorReport.ProjectID
    LEFT OUTER JOIN Users
        ON UsersInProjects.UserID = Users.UserID
    LEFT OUTER JOIN ReportComment
        ON ReportComment.ReportID = ErrorReport.ID
GROUP BY
    ErrorReport.UserID,
    Users.UserID,
    ReportComment.UserID,
    UsersInProjects.RecordID