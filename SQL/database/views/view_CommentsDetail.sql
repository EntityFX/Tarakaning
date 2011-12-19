CREATE VIEW view_CommentsDetail AS SELECT
    `R`.`ID` AS `ID`,
    `R`.RPT_ID AS `ReportID`,
    `R`.USER_ID AS `UserID`,
    `R`.CRT_TM AS `Time`,
    `R`.CMMENT AS `Comment`,
    `U`.NICK AS `Nick`
FROM
    (RPT_CMMENT `R`
    LEFT JOIN USER `U`
        ON ((`R`.USER_ID = `U`.USER_ID)));