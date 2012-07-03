DROP VIEW IF EXISTS view_ProjectUsersAndSubscribes;
CREATE VIEW view_ProjectUsersAndSubscribes AS
SELECT 
    P.PROJ_ID AS 'ProjectID'
    , P.PROJ_NM AS ProjectName
    , P.USER_ID AS ProjectOwnerID
    , P.CRT_TM AS CreateDateTime
    , left(P.DESCR, 25) AS Description
    , IFNULL(PSR.CountRequests,0) AS 'CountSubscribeRequests' 
    , IFNULL(PUP.CountUsers,0) AS 'CountUsers'
    , PUP.OwnerNickName AS OwnerNickName
FROM
    PROJ P
    LEFT JOIN
        view_ProjectSubscribeRequests PSR
        ON P.PROJ_ID = PSR.ProjectID
    LEFT JOIN
        view_ProjectUsersCount PUP
        ON P.PROJ_ID = PUP.ProjectID