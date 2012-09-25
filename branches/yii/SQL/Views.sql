--
-- Описание для представления alluserprojects
--
DROP VIEW IF EXISTS alluserprojects CASCADE;
CREATE VIEW alluserprojects
AS
SELECT `P`.`ProjectID` AS `ProjectID`
     , `P`.`Name` AS `Name`
     , `P`.`OwnerID` AS `UserID`
     , `U`.`NickName` AS `NickName`
     , _utf8 '1' AS `My`
FROM
    (`Projects` `P`
LEFT JOIN `Users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)))
UNION
SELECT `UP`.`ProjectID` AS `ProjectID`
     , `P`.`Name` AS `Name`
     , `UP`.`UserID` AS `UserID`
     , `U`.`NickName` AS `NickName`
     , _utf8 '0' AS `My`
FROM
    ((`UsersInProjects` `UP`
JOIN `Projects` `P`
ON ((`UP`.`ProjectID` = `P`.`ProjectID`)))
LEFT JOIN `Users` `U`
ON ((`UP`.`UserID` = `U`.`UserID`)));

--
-- Описание для представления commentsdetail
--
DROP VIEW IF EXISTS commentsdetail CASCADE;
CREATE VIEW commentsdetail
AS
SELECT `R`.`ID` AS `ID`
     , `R`.`ReportID` AS `ReportID`
     , `R`.`UserID` AS `UserID`
     , `R`.`Time` AS `Time`
     , `R`.`Comment` AS `Comment`
     , `U`.`NickName` AS `NickName`
FROM
    (`ReportComment` `R`
LEFT JOIN `Users` `U`
ON ((`R`.`UserID` = `U`.`UserID`)));

--
-- Описание для представления errorcommentscount
--
DROP VIEW IF EXISTS errorcommentscount CASCADE;
CREATE VIEW errorcommentscount
AS
SELECT `RC`.`UserID` AS `UserID`
     , `RC`.`ReportID` AS `ReportID`
     , `E`.`ProjectID` AS `ProjectID`
     , count(`RC`.`ID`) AS `ItemUserComment`
FROM
    (`ReportComment` `RC`
JOIN `ErrorReport` `E`
ON ((`RC`.`ReportID` = `E`.`ID`)))
GROUP BY
    `RC`.`ReportID`
  , `RC`.`UserID`;

--
-- Описание для представления errorreportsinfo
--
DROP VIEW IF EXISTS errorreportsinfo CASCADE;
CREATE VIEW errorreportsinfo
AS
SELECT `E`.`ID` AS `ID`
     , `E`.`Kind` AS `Kind`
     , `E`.`UserID` AS `UserID`
     , `E`.`AssignedTo` AS `AssignedTo`
     , `E`.`ProjectID` AS `ProjectID`
     , `E`.`PriorityLevel` AS `PriorityLevel`
     , `E`.`Status` AS `Status`
     , `E`.`Time` AS `Time`
     , `E`.`Title` AS `Title`
     , `D`.`DefectType` AS `ErrorType`
     , `E`.`Description` AS `Description`
     , `D`.`StepsText` AS `StepsText`
     , `P`.`Name` AS `ProjectName`
     , `U`.`NickName` AS `NickName`
     , `U1`.`NickName` AS `AssignedNickName`
FROM
    ((((`ErrorReport` `E`
JOIN `Projects` `P`
ON ((`E`.`ProjectID` = `P`.`ProjectID`)))
LEFT JOIN `Users` `U`
ON ((`E`.`UserID` = `U`.`UserID`)))
LEFT JOIN `Users` `U1`
ON ((`E`.`AssignedTo` = `U1`.`UserID`)))
LEFT JOIN `DefectItem` `D`
ON ((`E`.`ID` = `D`.`ID`)));

--
-- Описание для представления projectcomentscount
--
DROP VIEW IF EXISTS projectcomentscount CASCADE;
CREATE VIEW projectcomentscount
AS
SELECT `up`.`ProjectID` AS `ProjectID`
     , `up`.`UserID` AS `UserID`
     , ifnull(sum(`ec`.`ItemUserComment`), 0) AS `CountComments`
FROM
    (`alluserprojects` `up`
LEFT JOIN `errorcommentscount` `ec`
ON (((`up`.`ProjectID` = `ec`.`ProjectID`) AND (`up`.`UserID` = `ec`.`UserID`))))
GROUP BY
    `up`.`UserID`
  , `up`.`ProjectID`;

--
-- Описание для представления projectsinfoview
--
DROP VIEW IF EXISTS projectsinfoview CASCADE;
CREATE VIEW projectsinfoview
AS
SELECT `P`.`ProjectID` AS `ProjectID`
     , `P`.`Name` AS `Name`
     , left(`P`.`Description`, 25) AS `Description`
     , `P`.`OwnerID` AS `OwnerID`
     , `U`.`NickName` AS `NickName`
     , `P`.`CreateDate` AS `CreateDate`
     , (count(`UP`.`ProjectID`) + (`P`.`OwnerID` IS NOT NULL)) AS `CountUsers`
FROM
    ((`Projects` `P`
LEFT JOIN `Users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)))
LEFT JOIN `UsersInProjects` `UP`
ON ((`UP`.`ProjectID` = `P`.`ProjectID`)))
GROUP BY
    `P`.`ProjectID`;

--
-- Описание для представления projectanderrorsview
--
DROP VIEW IF EXISTS projectanderrorsview CASCADE;
CREATE VIEW projectanderrorsview
AS
SELECT `p`.`ProjectID` AS `ProjectID`
     , `p`.`Name` AS `Name`
     , `p`.`Description` AS `Description`
     , `p`.`OwnerID` AS `OwnerID`
     , `p`.`NickName` AS `NickName`
     , `p`.`CreateDate` AS `CreateDate`
     , 0 AS `CountRequests`
     , `p`.`CountUsers` AS `CountUsers`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'NEW') THEN
           _utf8 'NEW'
       ELSE
           NULL
       END)) AS `NEW`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'IDENTIFIED') THEN
           _utf8 'IDENTIFIED'
       ELSE
           NULL
       END)) AS `IDENTIFIED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'ASSESSED') THEN
           _utf8 'ASSESSED'
       ELSE
           NULL
       END)) AS `ASSESSED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'RESOLVED') THEN
           _utf8 'RESOLVED'
       ELSE
           NULL
       END)) AS `RESOLVED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'CLOSED') THEN
           _utf8 'CLOSED'
       ELSE
           NULL
       END)) AS `CLOSED`
FROM
    (`projectsinfoview` `p`
LEFT JOIN `ErrorReport` `E`
ON ((`E`.`ProjectID` = `p`.`ProjectID`)))
GROUP BY
    `p`.`ProjectID`;

--
-- Описание для представления projectsinfowithoutmeview
--
DROP VIEW IF EXISTS projectsinfowithoutmeview CASCADE;
CREATE VIEW projectsinfowithoutmeview
AS
SELECT `p`.`ProjectID` AS `ProjectID`
     , `p`.`Name` AS `Name`
     , `p`.`Description` AS `Description`
     , `p`.`OwnerID` AS `OwnerID`
     , `p`.`NickName` AS `NickName`
     , `p`.`CreateDate` AS `CreateDate`
     , `p`.`CountRequests` AS `CountRequests`
     , `p`.`CountUsers` AS `CountUsers`
     , `p`.`NEW` AS `NEW`
     , `p`.`IDENTIFIED` AS `IDENTIFIED`
     , `p`.`ASSESSED` AS `ASSESSED`
     , `p`.`RESOLVED` AS `RESOLVED`
     , `p`.`CLOSED` AS `CLOSED`
     , `U`.`UserID` AS `UserID`
FROM
    (`UsersInProjects` `U`
JOIN `projectanderrorsview` `p`
ON ((`p`.`ProjectID` = `U`.`ProjectID`)));

--
-- Описание для представления projectsubscribesdetails
--
DROP VIEW IF EXISTS projectsubscribesdetails CASCADE;
CREATE VIEW projectsubscribesdetails
AS
SELECT `SR`.`ID` AS `ID`
     , `SR`.`UserID` AS `UserID`
     , `SR`.`ProjectID` AS `ProjectID`
     , `SR`.`RequestTime` AS `RequestTime`
     , `U`.`NickName` AS `NickName`
FROM
    (`SubscribesRequest` `SR`
LEFT JOIN `Users` `U`
ON ((`SR`.`UserID` = `U`.`UserID`)));

--
-- Описание для представления projectswithusername
--
DROP VIEW IF EXISTS projectswithusername CASCADE;
CREATE VIEW projectswithusername
AS
SELECT `P`.`ProjectID` AS `ProjectID`
     , `P`.`Name` AS `Name`
     , `P`.`Description` AS `Description`
     , `P`.`OwnerID` AS `OwnerID`
     , `P`.`CreateDate` AS `CreateDate`
     , `U`.`NickName` AS `NickName`
FROM
    (`Projects` `P`
LEFT JOIN `Users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)));

--
-- Описание для представления projectusersinfo
--
DROP VIEW IF EXISTS projectusersinfo CASCADE;
CREATE VIEW projectusersinfo
AS
SELECT `P`.`OwnerID` AS `UserID`
     , `P`.`ProjectID` AS `ProjectID`
     , `U`.`NickName` AS `NickName`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'NEW') THEN
           _utf8 'NEW'
       ELSE
           NULL
       END)) AS `NEW`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'IDENTIFIED') THEN
           _utf8 'IDENTIFIED'
       ELSE
           NULL
       END)) AS `IDENTIFIED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'ASSESSED') THEN
           _utf8 'ASSESSED'
       ELSE
           NULL
       END)) AS `ASSESSED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'RESOLVED') THEN
           _utf8 'RESOLVED'
       ELSE
           NULL
       END)) AS `RESOLVED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'CLOSED') THEN
           _utf8 'CLOSED'
       ELSE
           NULL
       END)) AS `CLOSED`
     , count(`E`.`ID`) AS `CountErrors`
     , 1 AS `Owner`
FROM
    ((`Projects` `P`
LEFT JOIN `Users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)))
LEFT JOIN `ErrorReport` `E`
ON (((`P`.`ProjectID` = `E`.`ProjectID`) AND (`P`.`OwnerID` = `E`.`AssignedTo`))))
GROUP BY
    `P`.`ProjectID`
  , `P`.`OwnerID`
  , `U`.`UserID`
  , `E`.`AssignedTo`
UNION
SELECT `UP`.`UserID` AS `UserID`
     , `UP`.`ProjectID` AS `ProjectID`
     , `U`.`NickName` AS `NickName`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'NEW') THEN
           _utf8 'NEW'
       ELSE
           NULL
       END)) AS `NEW`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'IDENTIFIED') THEN
           _utf8 'IDENTIFIED'
       ELSE
           NULL
       END)) AS `IDENTIFIED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'ASSESSED') THEN
           _utf8 'ASSESSED'
       ELSE
           NULL
       END)) AS `ASSESSED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'RESOLVED') THEN
           _utf8 'RESOLVED'
       ELSE
           NULL
       END)) AS `RESOLVED`
     , count((
       CASE
       WHEN (`E`.`Status` = _cp1251 'CLOSED') THEN
           _utf8 'CLOSED'
       ELSE
           NULL
       END)) AS `CLOSED`
     , count(`E`.`ID`) AS `CountErrors`
     , 0 AS `Owner`
FROM
    ((`UsersInProjects` `UP`
LEFT JOIN `Users` `U`
ON ((`UP`.`UserID` = `U`.`UserID`)))
LEFT JOIN `ErrorReport` `E`
ON (((`UP`.`ProjectID` = `E`.`ProjectID`) AND (`UP`.`UserID` = `E`.`AssignedTo`))))
GROUP BY
    `UP`.`ProjectID`
  , `UP`.`UserID`;

--
-- Описание для представления projectusersinfofull
--
DROP VIEW IF EXISTS projectusersinfofull CASCADE;
CREATE VIEW projectusersinfofull
AS
SELECT `p`.`UserID` AS `UserID`
     , `p`.`ProjectID` AS `ProjectID`
     , `p`.`NickName` AS `NickName`
     , `p`.`NEW` AS `NEW`
     , `p`.`IDENTIFIED` AS `IDENTIFIED`
     , `p`.`ASSESSED` AS `ASSESSED`
     , `p`.`RESOLVED` AS `RESOLVED`
     , `p`.`CLOSED` AS `CLOSED`
     , `p`.`CountErrors` AS `CountErrors`
     , `p`.`Owner` AS `Owner`
     , count(`E`.`ID`) AS `CountCreated`
     , `pc`.`CountComments` AS `CountComments`
FROM
    ((`projectusersinfo` `p`
LEFT JOIN `ErrorReport` `E`
ON (((`p`.`ProjectID` = `E`.`ProjectID`) AND (`p`.`UserID` = `E`.`UserID`))))
JOIN `projectcomentscount` `pc`
ON (((`p`.`ProjectID` = `pc`.`ProjectID`) AND (`p`.`UserID` = `pc`.`UserID`))))
GROUP BY
    `p`.`ProjectID`
  , `p`.`UserID`;

--
-- Описание для представления subscribesdetails
--
DROP VIEW IF EXISTS subscribesdetails CASCADE;
CREATE VIEW subscribesdetails
AS
SELECT `SR`.`ID` AS `ID`
     , `SR`.`UserID` AS `UserID`
     , `SR`.`ProjectID` AS `ProjectID`
     , `SR`.`RequestTime` AS `RequestTime`
     , `P`.`Name` AS `ProjectName`
     , `P`.`OwnerID` AS `OwnerID`
     , `U`.`NickName` AS `ProjectOwner`
FROM
    ((`SubscribesRequest` `SR`
JOIN `Projects` `P`
ON ((`SR`.`ProjectID` = `P`.`ProjectID`)))
LEFT JOIN `Users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)));
