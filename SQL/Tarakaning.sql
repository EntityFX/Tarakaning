-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 5.0.48.1
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 14.09.2011 1:23:49
-- Версия сервера: 5.0.45-community-nt
-- Версия клиента: 4.1

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE Tarakaning;

--
-- Описание для таблицы ErorrReportHistory
--
DROP TABLE IF EXISTS ErorrReportHistory;
CREATE TABLE ErorrReportHistory(
    ID INT(11) NOT NULL AUTO_INCREMENT,
    ErrorReportID INT(11) NOT NULL,
    UserID INT(11) UNSIGNED NOT NULL,
    OldStatus ENUM('NEW', 'ASSIGNED', 'CONFIRMED', 'SOLVED', 'CLOSED') NOT NULL,
    OldTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Description TEXT DEFAULT NULL,
    PRIMARY KEY (ID),
    INDEX FK_ErorrReportHistory_ErrorReport_ID (ErrorReportID),
    INDEX FK_ErorrReportHistory_Users_UserID (UserID),
    CONSTRAINT FK_ErorrReportHistory_ErrorReport_ID FOREIGN KEY (ErrorReportID)
    REFERENCES errorreport (ID) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT FK_ErorrReportHistory_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users (UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы ErrorReport
--
DROP TABLE IF EXISTS ErrorReport;
CREATE TABLE ErrorReport(
    ID INT(11) NOT NULL AUTO_INCREMENT,
    UserID INT(11) UNSIGNED NOT NULL,
    ProjectID INT(11) NOT NULL,
    PriorityLevel ENUM('0', '1', '2') NOT NULL,
    Status ENUM('NEW', 'ASSESSED', 'IDENTIFIED', 'RESOLVED', 'CLOSED') NOT NULL,
    `Time` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
    Title VARCHAR(150) NOT NULL,
    ErrorType ENUM('Crash', 'Cosmetic', 'Error Handling', 'Functional', 'Minor', 'Major', 'Setup', 'Block') NOT NULL,
    Description TEXT NOT NULL,
    StepsText TEXT NOT NULL,
    PRIMARY KEY (ID),
    INDEX FK_ErrorReport_Projects_ProjectID (ProjectID),
    INDEX FK_ErrorReport_Users_UserID (UserID),
    CONSTRAINT FK_ErrorReport_Projects_ProjectID FOREIGN KEY (ProjectID)
    REFERENCES projects (ProjectID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 40
AVG_ROW_LENGTH = 481
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы MainMenu
--
DROP TABLE IF EXISTS MainMenu;
CREATE TABLE MainMenu(
    id INT(11) NOT NULL AUTO_INCREMENT,
    url VARCHAR(255) DEFAULT NULL,
    title VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 30
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы Modules
--
DROP TABLE IF EXISTS Modules;
CREATE TABLE Modules(
    moduleId INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID модуля',
    name VARCHAR(100) NOT NULL COMMENT 'Заголовок модуля',
    descr TINYTEXT NOT NULL COMMENT 'Описание модуля',
    path VARCHAR(100) NOT NULL COMMENT 'Путь к модулю',
    PRIMARY KEY (moduleId),
    UNIQUE INDEX path (path)
)
ENGINE = MYISAM
AUTO_INCREMENT = 14
AVG_ROW_LENGTH = 45
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица - список модулей';

--
-- Описание для таблицы Projects
--
DROP TABLE IF EXISTS Projects;
CREATE TABLE Projects(
    ProjectID INT(11) NOT NULL AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Description TINYTEXT DEFAULT NULL,
    OwnerID INT(11) UNSIGNED DEFAULT NULL,
    CreateDate DATETIME NOT NULL,
    PRIMARY KEY (ProjectID),
    INDEX fk_Projects_Users1 (OwnerID),
    UNIQUE INDEX Name (Name),
    CONSTRAINT fk_Projects_Users1 FOREIGN KEY (OwnerID)
    REFERENCES users (UserID) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE = INNODB
AUTO_INCREMENT = 24
AVG_ROW_LENGTH = 744
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы ReportComment
--
DROP TABLE IF EXISTS ReportComment;
CREATE TABLE ReportComment(
    ID INT(11) NOT NULL,
    ReportID INT(11) NOT NULL,
    UserID INT(11) UNSIGNED DEFAULT NULL,
    `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `Comment` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (ID),
    INDEX FK_ReportComment_ErrorReport_ID (ReportID),
    INDEX FK_ReportComment_Users_UserID (UserID),
    CONSTRAINT FK_ReportComment_ErrorReport_ID FOREIGN KEY (ReportID)
    REFERENCES errorreport (ID) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT FK_ReportComment_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users (UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 16384
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы ReportsUsersHandling
--
DROP TABLE IF EXISTS ReportsUsersHandling;
CREATE TABLE ReportsUsersHandling(
    ID INT(11) NOT NULL AUTO_INCREMENT,
    ReportID INT(11) NOT NULL,
    UserID INT(11) UNSIGNED DEFAULT NULL,
    PRIMARY KEY (ID),
    INDEX FK_ReportsUsersHandling_Users_UserID (UserID),
    INDEX IX_ReportsUsersHandling_ReportID (ReportID),
    UNIQUE INDEX ReportID (ReportID),
    CONSTRAINT FK_ReportsUsersHandling_ErrorReport_ID FOREIGN KEY (ReportID)
    REFERENCES errorreport (ID) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT FK_ReportsUsersHandling_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users (UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы SubscribesRequest
--
DROP TABLE IF EXISTS SubscribesRequest;
CREATE TABLE SubscribesRequest(
    ID INT(11) NOT NULL AUTO_INCREMENT,
    UserID INT(10) UNSIGNED DEFAULT NULL,
    ProjectID INT(11) DEFAULT NULL,
    PRIMARY KEY (ID),
    INDEX fk_SubscribesRequest_Projects1 (ProjectID),
    INDEX fk_SubscribesRequest_Users1 (UserID),
    UNIQUE INDEX UK_SubscribesRequest (ProjectID, UserID),
    CONSTRAINT fk_SubscribesRequest_Projects1 FOREIGN KEY (ProjectID)
    REFERENCES projects (ProjectID) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_SubscribesRequest_Users1 FOREIGN KEY (UserID)
    REFERENCES users (UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 13
AVG_ROW_LENGTH = 2340
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы TextModule
--
DROP TABLE IF EXISTS TextModule;
CREATE TABLE TextModule(
    textID INT(11) NOT NULL,
    `data` LONGTEXT DEFAULT NULL,
    headIntegrators MEDIUMTEXT DEFAULT NULL,
    PRIMARY KEY (textID)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 10780
CHARACTER SET cp1251
COLLATE cp1251_general_ci
ROW_FORMAT = DYNAMIC;

--
-- Описание для таблицы URL
--
DROP TABLE IF EXISTS URL;
CREATE TABLE URL(
    id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id раздела',
    link VARCHAR(255) NOT NULL DEFAULT '/' COMMENT 'Адрес раздела',
    title VARCHAR(100) NOT NULL COMMENT 'Заголовок',
    title_tag VARCHAR(255) NOT NULL COMMENT 'Для тэга title',
    module INT(11) NOT NULL DEFAULT 1 COMMENT 'Тип модуля',
    position INT(11) NOT NULL COMMENT 'Позиция в админке',
    pid INT(11) NOT NULL DEFAULT 1 COMMENT 'ID родительского раздела',
    use_parameters TINYINT(4) NOT NULL COMMENT 'Использовать параметры',
    PRIMARY KEY (id),
    INDEX IX_URL_module (module),
    INDEX IX_URL_pid (pid)
)
ENGINE = MYISAM
AUTO_INCREMENT = 72
AVG_ROW_LENGTH = 43
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица URL адресов и соответствующих модулей';

--
-- Описание для таблицы Users
--
DROP TABLE IF EXISTS Users;
CREATE TABLE Users(
    UserID INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    NickName VARCHAR(32) NOT NULL,
    Name VARCHAR(50) DEFAULT NULL,
    Surname VARCHAR(50) DEFAULT NULL,
    SecondName VARCHAR(50) DEFAULT NULL,
    PasswordHash VARCHAR(32) NOT NULL,
    UserType TINYINT(1) NOT NULL,
    Active TINYINT(1) NOT NULL,
    Email VARCHAR(255) DEFAULT NULL,
    LANG_ID INT(11) DEFAULT NULL,
    DefaultProjectID INT(11) DEFAULT NULL,
    PRIMARY KEY (UserID),
    UNIQUE INDEX NickName (NickName)
)
ENGINE = INNODB
AUTO_INCREMENT = 15
AVG_ROW_LENGTH = 1638
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы UsersInProjects
--
DROP TABLE IF EXISTS UsersInProjects;
CREATE TABLE UsersInProjects(
    RecordID INT(11) NOT NULL AUTO_INCREMENT,
    ProjectID INT(11) NOT NULL,
    UserID INT(11) UNSIGNED DEFAULT NULL,
    PRIMARY KEY (RecordID),
    INDEX IX_UsersInProjects_ProjectID (ProjectID),
    UNIQUE INDEX UK_UsersInProjects (UserID, ProjectID),
    CONSTRAINT FK_UsersInProjects_Projects_ProjectID FOREIGN KEY (ProjectID)
    REFERENCES projects (ProjectID) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT FK_UsersInProjects_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users (UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 22
AVG_ROW_LENGTH = 1489
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DELIMITER $$

--
-- Описание для процедуры GetMyProjectsInfo
--
DROP PROCEDURE IF EXISTS GetMyProjectsInfo$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE GetMyProjectsInfo(IN ProjectID INT)
BEGIN
    SELECT PAC.*
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
        (SELECT PA.*
              , count(S.ProjectID) AS CountRequests
         FROM
             (SELECT P.ProjectID
                   , P.Name AS ProjectName
                   , U.NickName
                   , P.OwnerID
                   , P.CreateDate
                   , count(UP.ProjectID) AS CountUsers
              FROM
                  Projects P
              LEFT JOIN UsersInProjects UP
              ON P.ProjectID = UP.ProjectID
              LEFT JOIN Users U
              ON P.OwnerID = U.UserID
              WHERE
                  P.OwnerID = ProjectID
              GROUP BY
                  P.ProjectID) AS PA
         LEFT JOIN SubscribesRequest AS S
         ON PA.ProjectID = S.ProjectID
         GROUP BY
             PA.ProjectID) AS PAC
    LEFT JOIN ErrorReport E
    ON PAC.ProjectID = E.ProjectID
    GROUP BY
        PAC.ProjectID;
END
$$

--
-- Описание для процедуры getProjectReportsInfo
--
DROP PROCEDURE IF EXISTS getProjectReportsInfo$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE getProjectReportsInfo(IN usrID INT)
BEGIN
    (SELECT Projects.ProjectID
          , Projects.`Name`
          , OwnerID
          , left(Projects.Description, 25) AS 'Description'
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'NEW') THEN
                _utf8 'NEW'
            ELSE
                NULL
            END)) AS `New`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'CONFIRMED') THEN
                _utf8 'CONFIRMED'
            ELSE
                NULL
            END)) AS `Confirmed`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'ASSIGNED') THEN
                _utf8 'ASSIGNED'
            ELSE
                NULL
            END)) AS `Assigned`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'SOLVED') THEN
                _utf8 'SOLVED'
            ELSE
                NULL
            END)) AS `Solved`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'CLOSED') THEN
                _utf8 'CLOSED'
            ELSE
                NULL
            END)) AS `Closed`
          , Projects.CreateDate
     FROM
         Projects
     LEFT JOIN `errorreport`
     ON `errorreport`.`ProjectID` = `projects`.`ProjectID`
     WHERE
         Projects.OwnerID = usrID
     GROUP BY
         `projects`.`ProjectID`)

    UNION

    (SELECT Projects.ProjectID
          , Projects.`Name`
          , OwnerID
          , left(Projects.Description, 25) AS 'Description'
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'NEW') THEN
                _utf8 'NEW'
            ELSE
                NULL
            END)) AS `New`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'CONFIRMED') THEN
                _utf8 'CONFIRMED'
            ELSE
                NULL
            END)) AS `Confirmed`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'ASSIGNED') THEN
                _utf8 'ASSIGNED'
            ELSE
                NULL
            END)) AS `Assigned`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'SOLVED') THEN
                _utf8 'SOLVED'
            ELSE
                NULL
            END)) AS `Solved`
          , count((
            CASE
            WHEN (`errorreport`.`Status` = _cp1251 'CLOSED') THEN
                _utf8 'CLOSED'
            ELSE
                NULL
            END)) AS `Closed`
          , Projects.CreateDate
     FROM
         Projects
     LEFT JOIN `errorreport`
     ON `errorreport`.`ProjectID` = `projects`.`ProjectID`
     WHERE
         Projects.ProjectID IN (SELECT ProjectID
                                FROM
                                    UsersInProjects
                                WHERE
                                    UserID = usrID)
     GROUP BY
         `projects`.`ProjectID`);
END
$$

--
-- Описание для процедуры GetProjectsInfo
--
DROP PROCEDURE IF EXISTS GetProjectsInfo$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE GetProjectsInfo()
BEGIN
    SELECT PAC.*
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
        (SELECT PA.*
              , count(S.ProjectID) AS CountRequests
         FROM
             (SELECT P.ProjectID
                   , P.Name AS ProjectName
                   , U.NickName
                   , P.OwnerID
                   , P.CreateDate
                   , count(UP.ProjectID) AS CountUsers
              FROM
                  Projects P
              LEFT JOIN UsersInProjects UP
              ON P.ProjectID = UP.ProjectID
              LEFT JOIN Users U
              ON P.OwnerID = U.UserID
              GROUP BY
                  P.ProjectID) AS PA
         LEFT JOIN SubscribesRequest AS S
         ON PA.ProjectID = S.ProjectID
         GROUP BY
             PA.ProjectID) AS PAC
    LEFT JOIN ErrorReport E
    ON PAC.ProjectID = E.ProjectID
    GROUP BY
        PAC.ProjectID;

END
$$

DELIMITER ;

--
-- Описание для представления errorreportsinfo
--
DROP VIEW IF EXISTS errorreportsinfo CASCADE;
CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW errorreportsinfo
AS
SELECT `E`.`ID` AS `ID`
     , `E`.`UserID` AS `UserID`
     , `E`.`ProjectID` AS `ProjectID`
     , `E`.`PriorityLevel` AS `PriorityLevel`
     , `E`.`Status` AS `Status`
     , `E`.`Time` AS `Time`
     , `E`.`Title` AS `Title`
     , `E`.`ErrorType` AS `ErrorType`
     , `E`.`Description` AS `Description`
     , `E`.`StepsText` AS `StepsText`
     , `P`.`Name` AS `ProjectName`
     , `U`.`NickName` AS `NickName`
FROM
    ((`errorreport` `E`
JOIN `projects` `P`
ON ((`E`.`ProjectID` = `P`.`ProjectID`)))
LEFT JOIN `users` `U`
ON ((`E`.`UserID` = `U`.`UserID`)));

--
-- Описание для представления projectanderrorsview
--
DROP VIEW IF EXISTS projectanderrorsview CASCADE;
CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW projectanderrorsview
AS
SELECT `p`.`ProjectID` AS `ProjectID`
     , `p`.`Name` AS `Name`
     , `p`.`Description` AS `Description`
     , `p`.`OwnerID` AS `OwnerID`
     , `p`.`NickName` AS `NickName`
     , `p`.`CreateDate` AS `CreateDate`
     , `p`.`CountRequests` AS `CountRequests`
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
    (`projectsinfoview` `P`
LEFT JOIN `errorreport` `E`
ON ((`E`.`ProjectID` = `p`.`ProjectID`)))
GROUP BY
    `p`.`ProjectID`;

--
-- Описание для представления projectsinfoview
--
DROP VIEW IF EXISTS projectsinfoview CASCADE;
CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW projectsinfoview
AS
SELECT `P`.`ProjectID` AS `ProjectID`
     , `P`.`Name` AS `Name`
     , left(`P`.`Description`, 25) AS `Description`
     , `P`.`OwnerID` AS `OwnerID`
     , `U`.`NickName` AS `NickName`
     , `P`.`CreateDate` AS `CreateDate`
     , count(`S`.`ProjectID`) AS `CountRequests`
     , count(`UP`.`ProjectID`) AS `CountUsers`
FROM
    (((`projects` `P`
LEFT JOIN `subscribesrequest` `S`
ON ((`S`.`ProjectID` = `P`.`ProjectID`)))
LEFT JOIN `users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)))
LEFT JOIN `usersinprojects` `UP`
ON ((`UP`.`ProjectID` = `P`.`ProjectID`)))
GROUP BY
    `P`.`ProjectID`;

--
-- Описание для представления projectsinfowithoutmeview
--
DROP VIEW IF EXISTS projectsinfowithoutmeview CASCADE;
CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW projectsinfowithoutmeview
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
    (`usersinprojects` `U`
JOIN `projectanderrorsview` `P`
ON ((`p`.`ProjectID` = `U`.`ProjectID`)));

--
-- Описание для представления projectswithusername
--
DROP VIEW IF EXISTS projectswithusername CASCADE;
CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW projectswithusername
AS
SELECT `P`.`ProjectID` AS `ProjectID`
     , `P`.`Name` AS `Name`
     , `P`.`Description` AS `Description`
     , `P`.`OwnerID` AS `OwnerID`
     , `P`.`CreateDate` AS `CreateDate`
     , `U`.`NickName` AS `NickName`
FROM
    (`projects` `P`
LEFT JOIN `users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)));

--
-- Описание для представления projectusersinfo
--
DROP VIEW IF EXISTS projectusersinfo CASCADE;
CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW projectusersinfo
AS
SELECT `P`.`OwnerID` AS `UserID`
     , `P`.`ProjectID` AS `ProjectID`
     , `U`.`NickName` AS `NickName`
     , count(`E`.`ID`) AS `CountErrors`
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
    ((`projects` `P`
JOIN `users` `U`
ON ((`P`.`OwnerID` = `U`.`UserID`)))
LEFT JOIN `errorreport` `E`
ON (((`P`.`OwnerID` = `E`.`UserID`) AND (`P`.`ProjectID` = `E`.`ProjectID`))))
GROUP BY
    `P`.`ProjectID`
UNION
SELECT `UP`.`UserID` AS `UserID`
     , `UP`.`ProjectID` AS `ProjectID`
     , `U`.`NickName` AS `NickName`
     , count(`E`.`ID`) AS `CountErrors`
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
    ((`usersinprojects` `UP`
LEFT JOIN `users` `U`
ON ((`UP`.`UserID` = `U`.`UserID`)))
LEFT JOIN `errorreport` `E`
ON (((`UP`.`UserID` = `E`.`UserID`) AND (`UP`.`ProjectID` = `E`.`ProjectID`))))
GROUP BY
    `UP`.`UserID`;

-- 
-- Вывод данных для таблицы ErorrReportHistory
--
-- Таблица не содержит данных

-- 
-- Вывод данных для таблицы ErrorReport
--
INSERT INTO ErrorReport VALUES 
  (2, 3, 2, '1', 'CLOSED', '2011-02-06 18:05:52', 'Возник BSOD', 'Crash', 'При попытке вызвать экран, вышла критическая ошибка', ''),
  (3, 3, 3, '1', 'NEW', '2011-02-17 19:57:46', '', 'Major', 'dsvfdgfdgdfg', 'sdfasdfadsf'),
  (4, 3, 1, '1', 'ASSESSED', '2011-02-17 19:58:23', 'dfdsfd', 'Error Handling', 'dsfsdf', 'dsfasdfasdfsdf'),
  (5, 3, 2, '2', 'IDENTIFIED', '2011-02-17 20:12:23', 'sdfsd', 'Functional', 'sdfsadfasdfsdaf', 'asdfasdfasdfdsfasdfasdfasdf'),
  (6, 13, 1, '1', 'NEW', '2011-02-24 23:19:28', 'Взрыв', 'Crash', '', ''),
  (7, 3, 7, '2', 'NEW', '2011-02-24 23:20:08', 'Уничтожение', 'Error Handling', '', ''),
  (8, 3, 3, '0', 'IDENTIFIED', '2011-02-24 23:20:24', 'Закрытие', 'Error Handling', '', ''),
  (9, 1, 3, '2', 'RESOLVED', '2011-02-26 23:31:53', '', 'Crash', '', ''),
  (10, 1, 2, '1', 'CLOSED', '2011-02-26 23:40:22', '', 'Crash', '', ''),
  (11, 3, 6, '1', 'NEW', '2011-02-27 03:32:03', 'fsdfsdf', 'Crash', '', ''),
  (12, 1, 6, '2', 'ASSESSED', '2011-02-27 03:34:34', 'sdfdsf', 'Crash', '', ''),
  (13, 3, 11, '0', 'NEW', '2011-02-28 00:41:17', 'Искривление зеркала', 'Crash', '', ''),
  (14, 13, 1, '2', 'RESOLVED', '2011-09-02 01:08:44', 'OK', 'Error Handling', '', ''),
  (15, 13, 1, '2', 'NEW', '2011-09-04 14:14:54', '', 'Major', '', ''),
  (16, 13, 1, '2', 'NEW', '2011-09-04 14:26:32', '', 'Major', '', ''),
  (17, 13, 1, '2', 'NEW', '2011-09-04 14:27:02', '', 'Major', '', ''),
  (18, 13, 1, '2', 'NEW', '2011-09-04 14:44:24', 'Заголовок', 'Block', '', ''),
  (19, 13, 1, '2', 'ASSESSED', '2011-09-04 14:51:07', 'Заголовок', 'Block', '', ''),
  (20, 13, 1, '2', 'NEW', '2011-09-04 14:53:52', 'Заголовок', 'Block', '', ''),
  (21, 13, 1, '2', 'IDENTIFIED', '2011-09-04 14:54:44', 'Заголовок', 'Block', '', ''),
  (22, 13, 1, '2', 'NEW', '2011-09-04 15:31:15', '', 'Major', '', ''),
  (23, 13, 1, '2', 'NEW', '2011-09-04 15:31:23', '', 'Major', '', ''),
  (28, 13, 1, '2', 'NEW', '2011-09-04 16:22:50', 'аипавпрерпа', 'Major', '', ''),
  (29, 13, 1, '1', 'NEW', '2011-09-04 16:51:31', 'иапипаи', 'Major', '', ''),
  (30, 13, 1, '0', 'NEW', '2011-09-04 16:51:43', 'апрвапр', 'Major', '', ''),
  (31, 13, 21, '1', 'NEW', '2011-09-04 16:52:49', 'апрвапрвапрвапрвпарвапр', 'Major', '', ''),
  (32, 1, 22, '1', 'NEW', '2011-09-11 13:39:05', 'Herlo', 'Major', '', ''),
  (33, 1, 22, '1', 'NEW', '2011-09-11 14:14:32', 'Spin De Physics', 'Major', '', ''),
  (34, 1, 22, '1', 'NEW', '2011-09-11 14:34:06', 'Zagolovok', 'Major', 'ккпкупук', 'пкупвапвапывап'),
  (35, 7, 22, '1', 'NEW', '2011-09-11 14:42:12', 'fgfhfghdfghdfghdfgh', 'Crash', 'dgfdgdfg', 'dsghfdghfghdfgh'),
  (36, 1, 23, '0', 'NEW', '2011-09-11 14:43:11', 'Заголовок', 'Block', 'Пиздееец', 'Пиздееец'),
  (37, 7, 22, '2', 'NEW', '2011-09-14 00:29:38', 'fgfdgf', 'Major', 'fhgfh', ''),
  (38, 7, 22, '1', 'ASSESSED', '2011-09-14 00:30:10', 'fghgfh', 'Error Handling', 'fghfgh', ''),
  (39, 8, 22, '0', 'IDENTIFIED', '2011-09-14 00:30:33', 'fdgfdh', 'Functional', '', '');

-- 
-- Вывод данных для таблицы MainMenu
--
INSERT INTO MainMenu VALUES 
  (1, 'www.google.ru', 'GOOGLE'),
  (3, '', 'Единственный'),
  (4, 'hi', 'HI');

-- 
-- Вывод данных для таблицы Modules
--
INSERT INTO Modules VALUES 
  (1, 'Текстовый модуль', 'Текстовый модуль', 'Text'),
  (0, 'Модуль ошибок', '', 'Error'),
  (11, 'Tarakaning', '', 'Tarakaning'),
  (6, 'Auth', 'Модуль аутентификации', 'Auth');

-- 
-- Вывод данных для таблицы Projects
--
INSERT INTO Projects VALUES 
  (1, 'Quki', 'вот наш один из долгостроев))', 13, '0000-00-00 00:00:00'),
  (2, 'Tarakaning', 'Баг-треккер на начальной стадии', 13, '0000-00-00 00:00:00'),
  (3, 'Fuck', NULL, 10, '0000-00-00 00:00:00'),
  (4, 'Siyfat', 'dfhdfgh', 11, '0000-00-00 00:00:00'),
  (5, 'Spektr-kzn', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (6, 'IMG', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (7, 'VSOOO', 'dfhdfgh', 3, '0000-00-00 00:00:00'),
  (8, 'Test', 'dfhdfgh', 8, '0000-00-00 00:00:00'),
  (9, 'Lustres', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (10, 'Basa16', 'fghfghfdgdhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhdrfh5ryrey56uy56g65nb76n76 u 67bthntyhnu657nu76un67un67nu67nun67un6un67un67un67un', NULL, '0000-00-00 00:00:00'),
  (11, 'Nocker', 'fghfghfdgdhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhdrfh5ryrey56uy56g65nb76n76 u 67bthntyhnu657nu76un67un67nu67nun67un6un67un67un67un', 13, '0000-00-00 00:00:00'),
  (12, 'bfgn', 'ndfnfgn', 9, '0000-00-00 00:00:00'),
  (13, 'bfgnfghh', 'ndfnfgn', NULL, '0000-00-00 00:00:00'),
  (14, 'bfgnfghhfbfghgfh', 'ndfnfgnfghdfgh', NULL, '0000-00-00 00:00:00'),
  (16, 'Beaty Wave', 'Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Beaty Wave of Bea', 13, '2011-09-01 00:15:51'),
  (17, 'Beaty Wave1', '', 13, '2011-09-01 00:40:33'),
  (18, 'nbgnhgnh', '', 13, '2011-09-01 00:45:46'),
  (19, 'qwe', '', 13, '2011-09-01 01:23:15'),
  (20, 'qwe1', '', 13, '2011-09-01 01:27:20'),
  (21, 'Huawei IDEOS X5 U8800', 'Android 2.2.1\\r\\n800 Mhz\\r\\n512 RAM\\r\\n5 MPx camera\\r\\nTFT 800*480 display', 13, '2011-09-02 01:06:13'),
  (22, 'Scuccko1', 'Экранирует специальные символы в unescaped_string, принимая во внимание кодировку соединения, таким образом, что результат можно безопасно использовать в SQL-запросе в функци mysql_query(). Если вставляются бинарные данные, то к ним так же необходимо прим', 1, '2011-09-03 14:58:40'),
  (23, 'Herlllou', 'Проект ниачём', 1, '2011-09-11 14:09:52');

-- 
-- Вывод данных для таблицы ReportComment
--
INSERT INTO ReportComment VALUES 
  (0, 9, 1, '2011-02-27 03:21:12', NULL);

-- 
-- Вывод данных для таблицы ReportsUsersHandling
--
-- Таблица не содержит данных

-- 
-- Вывод данных для таблицы SubscribesRequest
--
INSERT INTO SubscribesRequest VALUES 
  (1, 6, 1),
  (6, 1, 7),
  (5, 7, 7),
  (12, 8, 7),
  (8, 9, 7),
  (10, 7, 21),
  (9, 10, 21);

-- 
-- Вывод данных для таблицы TextModule
--
INSERT INTO TextModule VALUES 
  (19, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml">\r\n\t<head>\r\n\t\t<title>Tarakaning</title>\r\n\t\t<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />\r\n\t\t<link rel="stylesheet" type="text/css" href="verstko/style.css" />\r\n\t\t<link href="verstko/custom-theme/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>\r\n\t\t<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>\r\n\t\t<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>\r\n\r\n\t\t<script type="text/javascript" src="js/j.checkboxes.js"></script>\r\n\t\t<script type="text/javascript">\r\n\t\t/* <![CDATA[ */\r\n\t\t\t$(document).ready(function() {\r\n\t\t\t\t$("#tabs").tabs();\r\n\t\t\t\t$("input:button, input:submit, button, .groupier a, .groupier li span").button();\t\r\n\t\t\t\t$(''.reports_form'').checkboxes();\r\n\t\t\t});\r\n\t\t/* ]]>*/\r\n\t\t</script>\r\n\t</head>\r\n<body>\r\n<div id="header"> <img id="img_logo" src="images/logotype.PNG" alt="Tarakaning" />\r\n<div id="logo">\r\n<h1>Tarakaning</h1>\r\n<span>Багтреккер и управление проектами</span> </div>\r\n\r\n</div>\r\n\r\n<div id="main_menu">\r\n\t<ul>\r\n\t\t<li><a href="my_reports_with_checkboxes.html">Мои отчёты</a></li>\r\n\t\t<li><a href="reports_with_checkboxes.html">Отчёты проекта</a></li>\r\n\t\t<li><a href="projects.html">Мои проекты</a></li>\r\n\t\t<li><a href="subscribes.html">Мои заявки</a></li>\r\n\r\n\t\t<li><a href="find.html">Поиск</a></li>\r\n\t\t<li><a href="user_info.html">Профиль</a></li>\r\n\t</ul>\r\n</div>\r\n<div id="content_body">\r\n\t<div id="tabs">\r\n\t\t<ul>\r\n\t\t\t<li><a href="#my_project"><span>Мои проекты</span></a></li>\r\n\r\n\t\t\t<li><a href="#all_projects"><span>Все проекты</span></a></li>\t\t\t\r\n\t\t</ul>\r\n\t\t<div id="my_project">\r\n\t\t\t<div class="groupier">\r\n\t\t\t\t<a href="new_project.html">Создать новый проект</a>\r\n\t\t\t\t<ul>\r\n\t\t\t\t\t<li><a href="#">&lt;&lt;</a></li>\r\n\t\t\t\t\t<li><a href="#">&lt;</a></li>\r\n\r\n\t\t\t\t\t<li><a href="#">6</a></li>\r\n\t\t\t\t\t<li><span style="font-weight: bold; color: #a88; border-color: #a80; background: #d5d597 !important;">7</span></li>\r\n\t\t\t\t\t<li><a href="#">8</a></li>\r\n\t\t\t\t\t<li><a href="#">9</a></li>\r\n\t\t\t\t\t<li><a href="#">10</a></li>\r\n\t\t\t\t\t<li><a href="#">&gt;</a></li>\r\n\r\n\t\t\t\t\t<li><a href="#">&gt;&gt;</a></li>\r\n\t\t\t\t</ul>\r\n\t\t\t</div>\r\n\t\t\t<form action="#" class="reports_form">\r\n\t\t\t  <table class="projects_table">\r\n\t\t\t\t<col width="23" />\r\n\t\t\t\t<thead> \r\n\t\t\t\t\t<tr>\r\n\t\t\t\t\t  <th><input name="del" type="checkbox" /></th>\r\n\r\n\t\t\t\t\t  <th><a href="#">Проект</a></th>\r\n\t\t\t\t\t  <th><a href="#">Заголовок</a></th>\r\n\t\t\t\t\t  <th colspan="5"><a href="#">Отчётов</a></th>\r\n\t\t\t\t\t  <th><a href="#">Заявки</a></th>\r\n\t\t\t\t\t  <th><a href="#">Дата</a></th>\r\n\t\t\t\t\t</tr>\r\n\r\n\t\t\t\t</thead> \r\n\t\t\t\t<tbody>\r\n\t\t\t\t  <tr class="odd">\r\n\t\t\t\t\t<td><input name="delId" type="checkbox" /></td>\r\n\t\t\t\t\t<td>ООО Волосатый<br />\r\n\t\t\t\t\t</td>\r\n\t\t\t\t\t<td>Парикмахерыы</td>\r\n\t\t\t\t\t<td class="new">23</td><td class="confirmed">36</td><td class="assigned">9</td><td class="solved">11</td><td class="closed">2</td>\r\n\r\n\t\t\t\t\t<td><strong class="strongest">2</strong></td>\r\n\t\t\t\t\t<td>6 февраля 2007 12:56</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t  <tr class="even">\r\n\t\t\t\t\t<td><input name="delId" type="checkbox" /></td>\r\n\t\t\t\t\t<td>ОАО Пингви</td>\r\n\t\t\t\t\t<td>ставим линукс</td>\r\n\r\n\t\t\t\t\t<td class="new">13</td><td class="confirmed">678</td><td class="assigned">98</td><td class="solved">1</td><td class="closed">27</td>\r\n\t\t\t\t\t<td><strong class="strongest">1</strong></td>\r\n\t\t\t\t\t<td>7 октября 2011 07:48</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t  <tr class="odd">\r\n\r\n\t\t\t\t\t<td><input name="delId" type="checkbox" /></td>\r\n\t\t\t\t\t<td>ООО ЭнтитиЭфИкс</td>\r\n\t\t\t\t\t<td>Тити<br />\r\n\t\t\t\t\t</td>\r\n\t\t\t\t\t<td class="new">36</td><td class="confirmed">32</td><td class="assigned">19</td><td class="solved">28</td><td class="closed">46</td>\r\n\r\n\t\t\t\t\t<td><strong class="strongest">6</strong></td>\r\n\t\t\t\t\t<td>23 февраля 1989 11:34</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t  <tr class="even">\r\n\t\t\t\t\t<td><input name="delId" type="checkbox" /></td>\r\n\t\t\t\t\t<td>ООО Тараканинг</td>\r\n\t\t\t\t\t<td>крекер. баг-крекер<br />\r\n\r\n\t\t\t\t\t</td>\r\n\t\t\t\t\t<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td>\r\n\t\t\t\t\t<td><strong>0</strong><br />\r\n\t\t\t\t\t</td>\r\n\t\t\t\t\t<td>7 июля 2008 22:37</td>\r\n\r\n\t\t\t\t  </tr>\r\n\t\t\t\t  <tr class="odd">\r\n\t\t\t\t\t<td><input name="delId" type="checkbox" /></td>\r\n\t\t\t\t\t<td><a href="my_project_properties.html">ООО ТС </a></td>\r\n\t\t\t\t\t<td>Выбор себя</td>\r\n\t\t\t\t\t<td class="new">53</td><td class="confirmed">146</td><td class="assigned">34</td><td class="solved">45</td><td class="closed">11</td>\r\n\r\n\t\t\t\t\t<td><strong class="strongest">7</strong></td>\r\n\t\t\t\t\t<td>7 июля 2008 22:37</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t</tbody>\r\n\t\t\t  </table>\r\n\t\t\t\t<div class="groupier">\r\n\t\t\t\t\t<input value="Удалить" name="delBtn" type="button" />\r\n\t\t\t\t</div>\r\n\r\n\t\t\t</form>\r\n\t\t</div>\r\n\t\t<div id="all_projects">\r\n\t\t\t<div class="groupier">\r\n\t\t\t\t<ul>\r\n\t\t\t\t\t<li><a href="#">&lt;&lt;</a></li>\r\n\t\t\t\t\t<li><a href="#">&lt;</a></li>\r\n\t\t\t\t\t<li><a href="#">6</a></li>\r\n\r\n\t\t\t\t\t<li><span style="font-weight: bold; color: #a88; border-color: #a80; background: #d5d597 !important;">7</span></li>\r\n\t\t\t\t\t<li><a href="#">8</a></li>\r\n\t\t\t\t\t<li><a href="#">9</a></li>\r\n\t\t\t\t\t<li><a href="#">10</a></li>\r\n\t\t\t\t\t<li><a href="#">&gt;</a></li>\r\n\t\t\t\t\t<li><a href="#">&gt;&gt;</a></li>\r\n\t\t\t\t</ul>\r\n\r\n\t\t\t</div>\r\n\t\t\t  <table class="projects_table">\r\n\t\t\t\t<thead> \r\n\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t<th><a href="#">Проект</a></th>\r\n\t\t\t\t\t\t<th><a href="#">Заголовок</a></th>\r\n\t\t\t\t\t\t<th><a href="#">Владелец</a></th>\r\n\t\t\t\t\t\t<th colspan="5"><a href="#">Отчётов</a></th>\r\n\r\n\t\t\t\t\t\t<th><a href="#">Заявки</a></th>\r\n\t\t\t\t\t\t<th><a href="#">Дата</a></th>\r\n\t\t\t\t\t</tr>\r\n\t\t\t\t</thead> \r\n\t\t\t\t<tbody>\r\n\t\t\t\t  <tr class="odd">\r\n\t\t\t\t\t<td>ООО Волосатый</td>\r\n\t\t\t\t\t<td>Парикмахерыы</td>\r\n\r\n\t\t\t\t\t<td><a href="#">Yeldy</a></td>\r\n\t\t\t\t\t<td class="new">23</td><td class="confirmed">36</td><td class="assigned">9</td><td class="solved">11</td><td class="closed">2</td>\r\n\t\t\t\t\t<td><strong class="strongest">2</strong></td>\r\n\t\t\t\t\t<td>6 февраля 2007 12:56</td>\r\n\t\t\t\t  </tr>\r\n\r\n\t\t\t\t  <tr class="even">\r\n\t\t\t\t\t<td>ОАО Пингви</td>\r\n\t\t\t\t\t<td>ставим линукс</td>\r\n\t\t\t\t\t<td><a href="#">Trisha</a></td>\r\n\t\t\t\t\t<td class="new">13</td><td class="confirmed">678</td><td class="assigned">98</td><td class="solved">1</td><td class="closed">27</td>\r\n\r\n\t\t\t\t\t<td><strong class="strongest">1</strong></td>\r\n\t\t\t\t\t<td>7 октября 2011 07:48</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t  <tr class="odd">\r\n\t\t\t\t\t<td>ООО ЭнтитиЭфИкс</td>\r\n\t\t\t\t\t<td>SQL-Lover</td>\r\n\t\t\t\t\t<td><a href="#">EntityFX</a></td>\r\n\r\n\t\t\t\t\t<td class="new">36</td><td class="confirmed">32</td><td class="assigned">19</td><td class="solved">28</td><td class="closed">46</td>\r\n\t\t\t\t\t<td><strong class="strongest">6</strong></td>\r\n\t\t\t\t\t<td>23 февраля 1989 11:34</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t  <tr class="even">\r\n\r\n\t\t\t\t\t<td>ООО Тараканинг</td>\r\n\t\t\t\t\t<td>крекер. баг-крекер</td>\r\n\t\t\t\t\t<td><a href="#">EntityFX</a></td>\r\n\t\t\t\t\t<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td>\r\n\t\t\t\t\t<td><strong>0</strong><br />\r\n\r\n\t\t\t\t\t</td>\r\n\t\t\t\t\t<td>7 июля 2008 22:37</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t  <tr class="odd">\r\n\t\t\t\t\t<td><a href="my_project_properties.html">ООО ТС </a></td>\r\n\t\t\t\t\t<td>Выбор себя</td>\r\n\t\t\t\t\t<td><a href="#">Sudo777</a></td>\r\n\r\n\t\t\t\t\t<td class="new">53</td><td class="confirmed">146</td><td class="assigned">34</td><td class="solved">45</td><td class="closed">11</td>\r\n\t\t\t\t\t<td><strong class="strongest">7</strong></td>\r\n\t\t\t\t\t<td>7 июля 2008 22:37</td>\r\n\t\t\t\t  </tr>\r\n\t\t\t\t</tbody>\r\n\r\n\t\t\t  </table>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n\r\n</body></html>', NULL),
  (46, '<html>\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">\r\n<title>Переменные PHP, переменные окружения, глобальные переменные</title>\r\n<link rel="StyleSheet" type="text/css" href="book4.css" tppabs="http://site/bookphp/book4.css">\r\n<link rel="StyleSheet" type="text/css" href="code.css" tppabs="http://site/bookphp/code.css">\r\n</head>\r\n<body bottommargin="0" marginheight="0" marginwidth="0" rightmargin="0" leftmargin="0" topmargin="0">\r\n<table style="border-bottom-style: solid; border-width: 4px; border-color: #BABABA" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#D8F0FB">\r\n    <tr valign="top">\r\n        <td style="padding-left: 50" height="18" colspan="2">\r\n            <a href="http://www.softtime.ru/" title="IT-студия SoftTime"><img src="images/softtime.gif" tppabs="http://site/bookphp/images/softtime.gif" border="0" width="137" height="18" vspace="10"></a>                    \r\n        </td>\r\n        <td valign="middle" align="right">  \r\n            <a style="color: #607683; font-size: 11px; font-family: Arial, Helvetica, sans-serif;" href="mailto:softtime@softtime.ru">Написать письмо авторам</a>\r\n        </td>\r\n        <td width=10%>&nbsp;</td>\r\n    </tr>\r\n</table>\r\n<table border="0" cellspacing="17" >\r\n    <tr valign="bottom">\r\n        <td width=7%>&nbsp;</td>\r\n        <td width="30%" align="center"><h1 style="font-size: 36; padding-top: 20px;  margin: 0px">УЧЕБНИК&nbsp;PHP</h1></td>\r\n        <td align="center"><img src="images/help.gif" tppabs="http://site/bookphp/images/help.gif" border="0" width="40" height="25" alt=""><br>\r\n            <a class=a1 href="http://www.softtime.ru/bookphp/help.php"  title="Справочник функций языка PHP 4 на сайте IT-студии <SoftTime>"><b>справочник&nbsp;функций&nbsp;<Оnline></b></a>\r\n        </td>\r\n        <td align="center"><img src="images/aboutbook.gif" tppabs="http://site/bookphp/images/aboutbook.gif" border="0" width="25" height="25" alt=""><br>\r\n            <a title="Об учебнике и его авторах" class=a1 href="aboutbook.php.htm" tppabs="http://site/bookphp/aboutbook.php"><b>Об&nbsp;учебнике</b></a>\r\n        </td>\r\n        <td align="center"><img src="images/updatebook.gif" tppabs="http://site/bookphp/images/updatebook.gif" border="0" width="40" height="25" alt=""><br>\r\n            <a target="_blank" title="Проверить обновления учебника" class=a1 href="http://www.softtime.ru/info/bookphp.php"><b>Обновление</b></a>\r\n        </td>       \r\n    </tr>\r\n</table><br>\r\n<a name="up"></a>\r\n<table border="0" cellspacing="0" cellpadding="0">\r\n    <tr valign="top">\r\n        <td width="25%">\r\n            <table border="0" width="100%">\r\n                <tr>\r\n                    <td style="background-image: url(images/linebook1.gif); background-repeat: no-repeat"  height="5" width="213"><img src="images/pic.gif" tppabs="http://site/bookphp/images/pic.gif" border="0" width="1" height="1" alt=""></td>\r\n                </tr>\r\n                <tr align="left">\r\n                    <td><p style="color: #1020E3; font-size: 16px; margin-left: 10"><b>Оглавление</b></p></td>\r\n                </tr>\r\n                <tr>\r\n                    <td style="background-image: url(images/linebook2.gif); background-repeat: no-repeat" height="7"><img src="images/pic.gif" tppabs="http://site/bookphp/images/pic.gif" border="0" width="1" height="1" alt=""></td>\r\n                </tr>\r\n                <tr>\r\n                    <td width="213">\r\n                        <ol>\r\n                            <li><a class=bookmenu href="gl1_1.php.htm" tppabs="http://site/bookphp/gl1_1.php">Основы PHP</a>\r\n                                                            <div id="activechapter">                                                        \r\n                                    <p class=subchapter><a class=bookmenu href="gl1_1.php.htm" tppabs="http://site/bookphp/gl1_1.php"><nobr>PHP программы</nobr></a></b></p>\r\n                                    <p class=subchapter><a class=bookmenu href="gl1_2.php.htm" tppabs="http://site/bookphp/gl1_2.php">Комментарии</a></b></p>\r\n                                    <p class=subchapter><b><a class=bookmenu href="gl1_3.php.htm" tppabs="http://site/bookphp/gl1_3.php">Переменные PHP</a></b></p>\r\n                                    <p class=subchapter><a class=bookmenu href="gl1_4.php.htm" tppabs="http://site/bookphp/gl1_4.php">Константы</a></b></p>\r\n                                    <p class=subchapter><a class=bookmenu href="gl1_5.php.htm" tppabs="http://site/bookphp/gl1_5.php">Типы данных в РНР. Преобразование типов</a></b></p>\r\n                                    <p class=subchapter><a class=bookmenu href="gl1_6.php.htm" tppabs="http://site/bookphp/gl1_6.php">Операторы</a></b></p>\r\n                                </div>  \r\n                                                        </li>\r\n                            <li><a class=bookmenu href="gl2_1.php.htm" tppabs="http://site/bookphp/gl2_1.php">Операторы языка PHP</a>\r\n                                                        </li>\r\n                            <li><a class=bookmenu href="gl3_1.php.htm" tppabs="http://site/bookphp/gl3_1.php">Строковые функции</a></li>\r\n                                    \r\n                            <li><a class=bookmenu href="gl4_1.php.htm" tppabs="http://site/bookphp/gl4_1.php">Массивы</a>\r\n                                \r\n                            </li>                                                           \r\n                            <li><a class=bookmenu href="gl5_1.php.htm" tppabs="http://site/bookphp/gl5_1.php">Функции</a>\r\n                                                        </li>\r\n                            <li><a class=bookmenu href="gl6_1.php.htm" tppabs="http://site/bookphp/gl6_1.php">Работа с файлами</a>\r\n                                                        </li>\r\n                            <li ><a class=bookmenu href="gl7_1.php.htm" tppabs="http://site/bookphp/gl7_1.php">Регулярные выражения</a>\r\n                                                        </li>\r\n                            <li ><a class=bookmenu href="gl8_1.php.htm" tppabs="http://site/bookphp/gl8_1.php">Сессии и cookies в PHP</a>\r\n                                                        </li>\r\n                            <li><a class=bookmenu href="gl9_1.php.htm" tppabs="http://site/bookphp/gl9_1.php">Работа с FTP</a>\r\n                                                        </li>\r\n                            <li><a class=bookmenu href="gl10_1.php.htm" tppabs="http://site/bookphp/gl10_1.php">Проверка данных</a>\r\n                                                        </li>\r\n                            <li><a class=bookmenu href="gl11_1.php.htm" tppabs="http://site/bookphp/gl11_1.php">Гостевая книга</a>\r\n                                                        </li>\r\n                            <li class="newchaptr"><a class=bookmenu href="gl12_1.php.htm" tppabs="http://site/bookphp/gl12_1.php">PHP и MySQL</a>\r\n                                                        </li>\r\n                            \r\n                        </ol>\r\n                        <p><i>Продолжение следует</i></p>\r\n                    </td>\r\n                </tr>\r\n            </table>\r\n        </td>\r\n        <td ><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">\r\n<br><br>\r\n<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">\r\n<table border="0" cellpadding="0" cellspacing="0" height="70">\r\n    <tr>\r\n        <td rowspan="3" width="70"><img src="images/book.gif" tppabs="http://site/bookphp/images/book.gif" border="0" width="59" height="44" alt=""></td>\r\n        <td height="50" valign="middle"><h1 class=namepage>Основы PHP</h1></td>\r\n    </tr>\r\n    <tr>\r\n        <td height="7px" bgcolor="#9FB6C9"><img src="images/pic.gif" tppabs="http://site/bookphp/images/pic.gif" border="0" width="1" height="1" alt=""></td>\r\n    </tr>\r\n    <tr><td height="13"><img src="images/pic.gif" tppabs="http://site/bookphp/images/pic.gif" border="0" width="1" height="1" alt=""></td></tr>\r\n</table><table border="0" align="right" cellpadding="0" cellspacing="7">\r\n    <tr valign="middle" align="center">\r\n        <td><img src="images/arrowleft.gif" tppabs="http://site/bookphp/images/arrowleft.gif" border="0" width="10" height="17" alt=""></td>\r\n        <td><a class=a1 href="gl1_2.php.htm" tppabs="http://site/bookphp/gl1_2.php">Предыдущая</a></td>\r\n        <td width="1" bgcolor="silver"></td>\r\n        <td><a class=a1 href="gl1_4.php.htm" tppabs="http://site/bookphp/gl1_4.php">Следующая</a></td>\r\n        <td><img src="images/arrowright.gif" tppabs="http://site/bookphp/images/arrowright.gif" border="0" width="10" height="17" alt=""></td>\r\n    </tr>\r\n    <td colspan="5" bgcolor="silver"></td>\r\n</table>\r\n<h1 class=p1>Переменные</h1>\r\n<p class=text>В РНР переменные начинаются со знака доллара (<b>$</b>). За этим знаком может следовать любое количество буквенно-цифровых символов и символов подчеркивания, но первый символ не может быть цифрой или подчеркиванием. Следует также помнить, что имена переменных в РНР чувствительны к регистру, в отличие от ключевых слов.\r\n</p>\r\n<p class=text>При объявлении переменных в РНР не требуется явно указывать тип переменной, при этом одна и та же переменная может иметь на протяжении программы разные типы. \r\n</p>\r\n<p class=text>Переменная инициализируется в момент присваивания ей значения и существует до тех пор, пока выполняется программа. Т.е., в случае web-страницы это означает, что до тех пор, пока не завершен запрос. \r\n</p>\r\n<h1 class=p1>Внешние переменные</h1>\r\n<p class=text>После того, как запрос клиента проанализирован веб-сервером и передан РНР машине, последняя устанавливает ряд переменных, которые содержат данные, относящиеся к запросу и доступны все время его выполнения.  Сначала РНР берет <b class=nob>переменные окружения</b> Вашей системы и создает переменные с теми же именами и значениями в окружении сценария РНР для того чтобы сценариям, расположенным на сервере были доступны особенности системы клиента. Эти переменные помещаются в ассоциативный массив <b>$HTTP_ENV_VARS</b> (подробнее о массивах можно узнать в <a href="gl4_1.php.htm" tppabs="http://site/bookphp/gl4_1.php">главе 4</a>). \r\n</p>\r\n<p class=text>Естественно, что переменные массива <b>$HTTP_ENV_VARS</b> являются системно зависимыми (поскольку это фактически <b class=nob>переменные окружения</b>). Посмотреть значения переменных окружения для Вашей машины Вы можете при помощи команды env (Unix) или set (Windows). \r\n</p>\r\n<p class=text>Затем РНР создает группу GET-переменных, которые создаются при анализе строки запроса. Строка запроса хранится в переменной <b>$QUERY_STRING</b> и представляет собой информацию, следующую за символом &quot;<b>?</b>&quot; в запрошенном URL. РНР разбивает строку запроса по символам <b>&</b> на отдельные элементы, а затем ищет в каждом из этих элементов знак &quot;=&quot;. Если знак &quot;=&quot; найден, то создается переменная с именем из символов, стоящих слева от знака равенства. Рассмотрим следующую форму: \r\n</p>\r\n<blockquote>\r\n<pre>\r\n<em class=gr>&lt;form</em> action = <em class=comnt>"http://localhost/PHP/test.php"</em> method="<b>get</b>"&gt;\r\n   HDD: <em class=gr>&lt;input</em> type="<b>text</b>" name="<b>HDD</b>"/&gt;&lt;br&gt;\r\n   CDROM: <em class=gr>&lt;input</em> type="<b>text</b>" name="<b>CDROM</b>"/&gt;&lt;br&gt;\r\n<em class=gr>&lt;input</em> type="<b>submit</b>"/&gt;\r\n</form>\r\n</pre>\r\n</blockquote>\r\n<p class=text>\r\nЕсли Вы в этой форме в строке HDD наберете, к примеру, &quot;Maxtor&quot;, а в строке  CDROM &quot;Nec&quot;, то она сгенерирует следующую форму запроса:\r\n</p>\r\n<em class=comnt>http://localhost/PHP/test.php?HDD=Maxtor&CDROM=Nec</em>\r\n<p class=text>В нашем случае РНР создаст следующие переменные: </em>\r\n<b>$HDD</b>&nbsp;=&nbsp;&quot;Maxtor&quot; и <b>$CDROM</b>&nbsp;=&nbsp;&quot;Nec&quot;.  \r\n<p class=text>Вы можете работать с этими переменными из Вашего скрипта (у нас – test.php) как с обычными переменными. В нашем случае они просто выводятся на экран:\r\n</p>\r\n<blockquote>\r\n<pre>\r\n<em class=red>&lt;?</em>\r\n   <em class=gr>echo</em>(&quot;&lt;p&gt;HDD is $HDD&lt;/p&gt;");\r\n   <em class=gr>echo</em>(&quot;&lt;p&gt;CDROM is $CDROM&lt;/p&gt;");\r\n<em class=red>?&gt;</em>\r\n</pre>\r\n</blockquote>\r\n<p class=text>\r\nЕсли запрос страницы выполняется при помощи метода POST, то появляется группа POST-переменных, которые интерпретируются также и помещаются в массив <b>$HTTP_POST_VARS</b>.\r\n</p>\r\n<table border="0" align="center" cellpadding="0" cellspacing="0">\r\n    <tr>\r\n        <td colspan="5" align="center"><img src="images/linebook1.gif" tppabs="http://site/bookphp/images/linebook1.gif" border="0" width="212" height="5" alt=""></td>\r\n    </tr>\r\n    <tr valign="middle" align="center">\r\n        <td><img src="images/arrowleft.gif" tppabs="http://site/bookphp/images/arrowleft.gif" border="0" width="10" height="17" alt=""></td>\r\n        <td><a class=a1 href="gl1_2.php.htm" tppabs="http://site/bookphp/gl1_2.php">Предыдущая</a></td>\r\n        <td width="1" bgcolor="silver"></td>\r\n        <td><a class=a1 href="gl1_4.php.htm" tppabs="http://site/bookphp/gl1_4.php">Следующая</a></td>\r\n        <td><img src="images/arrowright.gif" tppabs="http://site/bookphp/images/arrowright.gif" border="0" width="10" height="17" alt=""></td>\r\n    </tr>\r\n    <tr>\r\n        <td colspan="5" align="center"><img src="images/linebook2.gif" tppabs="http://site/bookphp/images/linebook2.gif" border="0" width="213" height="11" alt=""></td>\r\n    </tr>\r\n</table>\r\n        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">\r\n            </p>\r\n        </td>   \r\n        <td width="7%">&nbsp;</td>  \r\n    </tr>\r\n</table>\r\n<table border="0" width="100%">\r\n    <tr>\r\n        <td width=20%>&nbsp;</td>\r\n        <td>\r\n            <a class=a1 href="#up">Наверх</a>\r\n        </td>\r\n    </tr>\r\n</table><br>\r\n</body>\r\n</html> ', NULL);

-- 
-- Вывод данных для таблицы URL
--
INSERT INTO URL VALUES 
  (1, '/', '', '', 11, 0, 0, 0),
  (71, 'edit', '', '', 11, 0, 64, 0),
  (56, 'login', 'Аутентификация', 'Аутентификация', 6, 0, 1, 0),
  (57, 'logout', '', '', 6, 0, 1, 0),
  (58, 'registration', '', '', 6, 0, 1, 0),
  (59, 'do', '', '', 6, 0, 56, 0),
  (60, 'do', '', '', 6, 0, 58, 0),
  (61, 'my', '', '', 11, 0, 1, 0),
  (62, 'projects', '', '', 11, 0, 61, 0),
  (64, 'project', '', '', 11, 0, 61, 0),
  (65, 'new', '', '', 11, 0, 64, 0),
  (63, 'bugs', '', '', 11, 0, 61, 0),
  (67, 'bug', '', '', 11, 0, 1, 0),
  (68, 'show', '', '', 11, 0, 67, 1),
  (69, 'add', '', '', 11, 0, 67, 0),
  (70, 'show', 'Инфорация о проекте', 'Инфорация о проекте', 11, 0, 64, 1);

-- 
-- Вывод данных для таблицы Users
--
INSERT INTO Users VALUES 
  (1, 'EntityFX', 'Артём', 'Солопий', 'Валерьевич', '408edad392248bc60f0e7ddaed995fe5', 0, 1, 'tym_@mail.ru', NULL, NULL),
  (3, 'Vasiliy', 'Артём', 'Солопий', 'Валерьевич', 'f188f8028be984727e58c6aed3cbe2d3', 0, 1, 'tym_@mail.ru', NULL, 7),
  (6, 'Oliya', NULL, NULL, NULL, '', 0, 0, NULL, NULL, NULL),
  (7, 'Marat', 'Марат', 'Ахметов', 'Альбертович', '408edad392248bc60f0e7ddaed995fe5', 0, 1, NULL, NULL, NULL),
  (8, 'Dmitry', 'Дмитрий', 'Онотольевич', 'Мядведев', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 0, 'dmitry@medved.net', NULL, NULL),
  (9, 'Ivan', '', '', '', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 0, '', NULL, NULL),
  (10, 'Misha', '', '', '', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 0, '', NULL, NULL),
  (11, 'edf', '', '', '', 'baee68b86198d8fe688d0c7fb695e8d8', 0, 0, '', NULL, NULL),
  (13, 'Artem', 'Artem', 'Solopiy', 'Valer''evich', '408edad392248bc60f0e7ddaed995fe5', 0, 1, '', NULL, NULL),
  (14, 'Kolya', '', '', '', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 1, '', NULL, NULL);

-- 
-- Вывод данных для таблицы UsersInProjects
--
INSERT INTO UsersInProjects VALUES 
  (4, 3, 1),
  (12, 6, 1),
  (6, 1, 3),
  (1, 2, 3),
  (10, 3, 3),
  (8, 6, 3),
  (2, 1, 6),
  (20, 22, 7),
  (21, 22, 8),
  (11, 3, 13),
  (7, 5, 13);

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;