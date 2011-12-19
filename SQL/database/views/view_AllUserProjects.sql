CREATE VIEW view_AllUserProjects AS SELECT
    `P`.PROJ_ID AS `ProjectID`,
    `P`.PROJ_NM AS `Name`,
    `P`.USER_ID AS `UserID`,
    `U`.NICK AS `Nick`,
    _utf8 '1' AS `My`
FROM
    (PROJ `P`
    LEFT JOIN `USER` `U`
        ON ((`P`.PROJ_ID = `U`.USER_ID)))
UNION
SELECT
    `UP`.PROJ_ID AS `ProjectID`,
    `P`.PROJ_NM AS `Name`,
    `UP`.USER_ID AS `UserID`,
    `U`.NICK AS `Nick`,
    _utf8 '0' AS `My`
FROM
    ((USER_IN_PROJ `UP`
    JOIN PROJ `P`
        ON ((`UP`.PROJ_ID = `P`.PROJ_ID)))
    LEFT JOIN `USER` `U`
        ON ((`UP`.USER_ID = `U`.USER_ID)));