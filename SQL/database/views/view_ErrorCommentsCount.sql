CREATE VIEW view_ErrorCommentsCount AS SELECT
    RC.USER_ID AS UserID,
    RC.RPT_ID AS ReportID,
    E.PROJ_ID AS ProjectID,
    COUNT(RC.ID) AS ItemUserComment
FROM
    (RPT_CMMENT RC
    INNER JOIN ERR_RPT E
        ON ((RC.RPT_ID = E.ID)))
GROUP BY
    RC.RPT_ID,
    RC.USER_ID;