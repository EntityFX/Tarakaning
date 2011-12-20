CREATE VIEW view_ErrorCommentsCount AS 
SELECT
    IC.USER_ID AS UserID,
    IC.ITEM_ID AS ReportID,
    I.PROJ_ID AS ProjectID,
    COUNT(IC.ITEM_CMMENT_ID) AS ItemUserComment
FROM
    ITEM_CMMENT IC
    INNER JOIN ITEM I
        ON IC.ITEM_ID = I.ITEM_ID
GROUP BY
    IC.ITEM_ID,
    IC.USER_ID;