SELECT
    P3.*,
    COUNT(`SR`.`ProjectID`) AS `SubscribesRequest`
FROM
    (SELECT
        P2.*,
        COUNT(e.projectid) AS CountErrors,
        COUNT((CASE WHEN (`E`.`Status` = _cp1251 'NEW') THEN 1 ELSE NULL END)) AS `NEW`,
        COUNT((CASE WHEN (`E`.`Status` = _cp1251 'IDENTIFIED') THEN 1 ELSE NULL END)) AS `IDENTIFIED`,
        COUNT((CASE WHEN (`E`.`Status` = _cp1251 'ASSESSED') THEN 1 ELSE NULL END)) AS `ASSESSED`,
        COUNT((CASE WHEN (`E`.`Status` = _cp1251 'RESOLVED') THEN 1 ELSE NULL END)) AS `RESOLVED`,
        COUNT((CASE WHEN (`E`.`Status` = _cp1251 'CLOSED') THEN 1 ELSE NULL END)) AS `CLOSED`
    FROM
        (SELECT
            P1.*,
            (COUNT(`UP`.`ProjectID`) + (CASE WHEN (`P1`.`OwnerID` IS NOT NULL) THEN 1 ELSE 0 END)) AS `CountUsers`
        FROM
            (SELECT
                `P`.`ProjectID` AS `ProjectID`,
                `P`.`Name` AS `Name`,
                LEFT(`P`.`Description`, 25) AS `Description`,
                `P`.`OwnerID` AS `OwnerID`,
                `P`.`CreateDate` AS `CreateDate`,
                `U`.`NickName` AS `NickName`
            FROM
                `projects` `P`
                LEFT JOIN `users` `U`
                    ON `P`.`OwnerID` = `U`.`UserID`) P1
            LEFT JOIN `usersinprojects` `UP`
                ON `UP`.`ProjectID` = `P1`.`ProjectID`
        GROUP BY
            `P1`.`ProjectID`) AS P2
        LEFT JOIN `errorreport` `E`
            ON `E`.`ProjectID` = `p2`.`ProjectID`
    GROUP BY
        `P2`.`ProjectID`) AS P3
    LEFT JOIN `subscribesrequest` `SR`
        ON `SR`.`ProjectID` = `P3`.`ProjectID`
GROUP BY
    `P3`.`ProjectID`