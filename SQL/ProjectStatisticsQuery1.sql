SELECT
    `P`.`ProjectID` AS `ProjectID`,
    `P`.`Name` AS `Name`,
    LEFT(`P`.`Description`, 25) AS `Description`,
    `P`.`OwnerID` AS `OwnerID`,
    `P`.`CreateDate` AS `CreateDate`,
    `U`.`NickName` AS `NickName`,
    (SELECT COUNT(*) FROM usersinprojects WHERE ProjectID = P.ProjectID ) + CASE WHEN (`P`.`OwnerID` IS NOT NULL) THEN 1 ELSE 0 END AS CountUsers,
    (SELECT COUNT(*) FROM errorreport WHERE ProjectID = P.ProjectID) AS CountErrors,
    (SELECT COUNT(*) FROM errorreport WHERE `Status` = _cp1251 'NEW' AND ProjectID = P.ProjectID) AS `NEW`,
    (SELECT COUNT(*) FROM errorreport WHERE `Status` = _cp1251 'IDENTIFIED' AND ProjectID = P.ProjectID) AS `IDENTIFIED`,
    (SELECT COUNT(*) FROM errorreport WHERE `Status` = _cp1251 'ASSESSED' AND ProjectID = P.ProjectID) AS `ASSESSED`,
    (SELECT COUNT(*) FROM errorreport WHERE `Status` = _cp1251 'RESOLVED' AND ProjectID = P.ProjectID) AS `RESOLVED`,
    (SELECT COUNT(*) FROM errorreport WHERE `Status` = _cp1251 'CLOSED' AND ProjectID = P.ProjectID) AS `CLOSED`,
    (SELECT COUNT(*) FROM subscribesrequest WHERE ProjectID = P.ProjectID ) AS CountSubscribes
FROM
    `projects` `P`
    LEFT JOIN `users` `U`
        ON `P`.`OwnerID` = `U`.`UserID`
