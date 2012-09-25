CREATE VIEW view_UserInProjectErrorsAndComments AS
SELECT 
    P.UserID AS UserID, P.ProjectID AS ProjectID, 
    P.NickName AS NickName, 
    P.NEW AS `NEW`,
    P.`IDENTIFIED` AS `IDENTIFIED`, 
    P.ASSESSED AS `ASSESSED`, 
    P.RESOLVED AS `RESOLVED`, 
    P.CLOSED AS `CLOSED`, 
    P.CountErrors AS CountErrors, 
    P.`Owner` AS `Owner`, 
    count(I.ITEM_ID) AS CountCreated, 
    PC.CountComments AS CountComments
FROM
    view_UserInProjectErrors P
LEFT JOIN ITEM I
    ON P.ProjectID = I.PROJ_ID AND P.UserID = I.USER_ID
JOIN view_ProjectCommentsCount PC
    ON P.ProjectID = PC.ProjectID AND P.UserID = PC.UserID
GROUP BY
    P.ProjectID, 
    P.UserID;