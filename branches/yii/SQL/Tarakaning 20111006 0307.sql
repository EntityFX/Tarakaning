-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 5.0.48.1
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 06.10.2011 3:07:58
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
-- Описание для таблицы DefectItem
--
DROP TABLE IF EXISTS DefectItem;
CREATE TABLE DefectItem (
  ID INT(11) NOT NULL,
  DefectType ENUM('Crash','Cosmetic','Error Handling','Functional','Minor','Major','Setup','Block') NOT NULL,
  StepsText TEXT NOT NULL,
  PRIMARY KEY (ID),
  CONSTRAINT FK_DefectItem_ErrorReport_ID FOREIGN KEY (ID)
    REFERENCES errorreport(ID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AVG_ROW_LENGTH = 341
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы ErorrReportHistory
--
DROP TABLE IF EXISTS ErorrReportHistory;
CREATE TABLE ErorrReportHistory (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  ErrorReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED NOT NULL,
  OldStatus ENUM('NEW','ASSIGNED','CONFIRMED','SOLVED','CLOSED') NOT NULL,
  OldTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  Description TEXT DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ErorrReportHistory_ErrorReport_ID (ErrorReportID),
  INDEX FK_ErorrReportHistory_Users_UserID (UserID),
  CONSTRAINT FK_ErorrReportHistory_ErrorReport_ID FOREIGN KEY (ErrorReportID)
    REFERENCES errorreport(ID) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_ErorrReportHistory_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы ErrorReport
--
DROP TABLE IF EXISTS ErrorReport;
CREATE TABLE ErrorReport (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  UserID INT(11) UNSIGNED NOT NULL,
  ProjectID INT(11) NOT NULL,
  Kind ENUM('Defect','Task') NOT NULL,
  PriorityLevel ENUM('0','1','2') NOT NULL,
  Status ENUM('NEW','ASSESSED','IDENTIFIED','RESOLVED','CLOSED') NOT NULL,
  `Time` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
  Title VARCHAR(150) NOT NULL,
  Description TEXT NOT NULL,
  AssignedTo INT(11) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ErrorReport_Projects_ProjectID (ProjectID),
  INDEX FK_ErrorReport_Users_UserID (UserID),
  CONSTRAINT FK_ErrorReport_Projects_ProjectID FOREIGN KEY (ProjectID)
    REFERENCES projects(ProjectID) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_ErrorReport_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 79
AVG_ROW_LENGTH = 224
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы MainMenu
--
DROP TABLE IF EXISTS MainMenu;
CREATE TABLE MainMenu (
  id INT(11) NOT NULL AUTO_INCREMENT,
  url VARCHAR(255) DEFAULT NULL,
  title VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 25
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы Modules
--
DROP TABLE IF EXISTS Modules;
CREATE TABLE Modules (
  moduleId INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID модуля',
  name VARCHAR(100) NOT NULL COMMENT 'Заголовок модуля',
  descr TINYTEXT NOT NULL COMMENT 'Описание модуля',
  path VARCHAR(100) NOT NULL COMMENT 'Путь к модулю',
  PRIMARY KEY (moduleId),
  UNIQUE INDEX path (path)
)
ENGINE = MYISAM
AUTO_INCREMENT = 15
AVG_ROW_LENGTH = 33
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица - список модулей';

--
-- Описание для таблицы Projects
--
DROP TABLE IF EXISTS Projects;
CREATE TABLE Projects (
  ProjectID INT(11) NOT NULL AUTO_INCREMENT,
  Name VARCHAR(100) NOT NULL,
  Description TINYTEXT DEFAULT NULL,
  OwnerID INT(11) UNSIGNED DEFAULT NULL,
  CreateDate DATETIME NOT NULL,
  PRIMARY KEY (ProjectID),
  INDEX fk_Projects_Users1 (OwnerID),
  INDEX Name (Name),
  CONSTRAINT fk_Projects_Users1 FOREIGN KEY (OwnerID)
    REFERENCES users(UserID) ON DELETE NO ACTION ON UPDATE NO ACTION
)
ENGINE = INNODB
AUTO_INCREMENT = 49
AVG_ROW_LENGTH = 348
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы ReportComment
--
DROP TABLE IF EXISTS ReportComment;
CREATE TABLE ReportComment (
  ID INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Comment` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportComment_ErrorReport_ID (ReportID),
  INDEX FK_ReportComment_Users_UserID (UserID),
  CONSTRAINT FK_ReportComment_ErrorReport_ID FOREIGN KEY (ReportID)
    REFERENCES errorreport(ID) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_ReportComment_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 40
AVG_ROW_LENGTH = 420
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы ReportsUsersHandling
--
DROP TABLE IF EXISTS ReportsUsersHandling;
CREATE TABLE ReportsUsersHandling (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportsUsersHandling_Users_UserID (UserID),
  INDEX IX_ReportsUsersHandling_ReportID (ReportID),
  UNIQUE INDEX ReportID (ReportID),
  CONSTRAINT FK_ReportsUsersHandling_ErrorReport_ID FOREIGN KEY (ReportID)
    REFERENCES errorreport(ID) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_ReportsUsersHandling_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы SubscribesRequest
--
DROP TABLE IF EXISTS SubscribesRequest;
CREATE TABLE SubscribesRequest (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  UserID INT(10) UNSIGNED DEFAULT NULL,
  ProjectID INT(11) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX fk_SubscribesRequest_Projects1 (ProjectID),
  INDEX fk_SubscribesRequest_Users1 (UserID),
  UNIQUE INDEX UK_SubscribesRequest (ProjectID, UserID),
  CONSTRAINT fk_SubscribesRequest_Projects1 FOREIGN KEY (ProjectID)
    REFERENCES projects(ProjectID) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_SubscribesRequest_Users1 FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
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
CREATE TABLE TextModule (
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
CREATE TABLE URL (
  id INT(11) NOT NULL COMMENT 'Id раздела',
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
AVG_ROW_LENGTH = 28
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица URL адресов и соответствующих модулей';

--
-- Описание для таблицы Users
--
DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
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
AUTO_INCREMENT = 19
AVG_ROW_LENGTH = 1170
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

--
-- Описание для таблицы UsersInProjects
--
DROP TABLE IF EXISTS UsersInProjects;
CREATE TABLE UsersInProjects (
  RecordID INT(11) NOT NULL AUTO_INCREMENT,
  ProjectID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (RecordID),
  INDEX IX_UsersInProjects_ProjectID (ProjectID),
  UNIQUE INDEX UK_UsersInProjects (UserID, ProjectID),
  CONSTRAINT FK_UsersInProjects_Projects_ProjectID FOREIGN KEY (ProjectID)
    REFERENCES projects(ProjectID) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_UsersInProjects_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 28
AVG_ROW_LENGTH = 1170
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DELIMITER $$

--
-- Описание для процедуры AddItem
--
DROP PROCEDURE IF EXISTS AddItem$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE AddItem(IN UserID INT, IN ProjectID INT, IN PriorityLevel VARCHAR(1), IN StatusValue VARCHAR(50), IN `Date` DATETIME, IN Title VARCHAR(255), IN Kind VARCHAR(50), IN Description VARCHAR(255), IN DefectType VARCHAR(50), IN StepsText VARCHAR(255))
BEGIN
    DECLARE LAST_ID INT;
    INSERT INTO ErrorReport 
        (
            UserID,
            ProjectID,
            PriorityLevel,
            `Status`,
            `Time`,
            Title,
            Kind,
            Description
        ) 
        VALUES
        (
            UserID,
            ProjectID,
            PriorityLevel,
            StatusValue,
            `Date`,
            Title,
            Kind,
            Description
        );

    SET LAST_ID=(SELECT last_insert_id() FROM ErrorReport LIMIT 0,1);

    IF Kind = 'Defect' THEN
        INSERT INTO DefectItem (ID,DefectType,StepsText) 
            VALUES (LAST_ID,DefectType,StepsText);
    END IF; 
END
$$

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
-- Описание для представления alluserprojects
--
DROP VIEW IF EXISTS alluserprojects CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW alluserprojects
AS
	select `P`.`ProjectID` AS `ProjectID`,`P`.`Name` AS `Name`,`P`.`OwnerID` AS `UserID`,`U`.`NickName` AS `NickName`,_utf8'1' AS `My` from (`projects` `P` left join `users` `U` on((`P`.`OwnerID` = `U`.`UserID`))) union select `UP`.`ProjectID` AS `ProjectID`,`P`.`Name` AS `Name`,`UP`.`UserID` AS `UserID`,`U`.`NickName` AS `NickName`,_utf8'0' AS `My` from ((`usersinprojects` `UP` join `projects` `P` on((`UP`.`ProjectID` = `P`.`ProjectID`))) left join `users` `U` on((`UP`.`UserID` = `U`.`UserID`)));

--
-- Описание для представления commentsdetail
--
DROP VIEW IF EXISTS commentsdetail CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW commentsdetail
AS
	select `R`.`ID` AS `ID`,`R`.`ReportID` AS `ReportID`,`R`.`UserID` AS `UserID`,`R`.`Time` AS `Time`,`R`.`Comment` AS `Comment`,`U`.`NickName` AS `NickName` from (`reportcomment` `R` left join `users` `U` on((`R`.`UserID` = `U`.`UserID`)));

--
-- Описание для представления errorreportsinfo
--
DROP VIEW IF EXISTS errorreportsinfo CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW errorreportsinfo
AS
	select `E`.`ID` AS `ID`,`E`.`Kind` AS `Kind`,`E`.`UserID` AS `UserID`,`E`.`AssignedTo` AS `AssignedTo`,`E`.`ProjectID` AS `ProjectID`,`E`.`PriorityLevel` AS `PriorityLevel`,`E`.`Status` AS `Status`,`E`.`Time` AS `Time`,`E`.`Title` AS `Title`,`D`.`DefectType` AS `ErrorType`,`E`.`Description` AS `Description`,`D`.`StepsText` AS `StepsText`,`P`.`Name` AS `ProjectName`,`U`.`NickName` AS `NickName`,`U1`.`NickName` AS `AssignedNickName` from ((((`errorreport` `E` join `projects` `P` on((`E`.`ProjectID` = `P`.`ProjectID`))) left join `users` `U` on((`E`.`UserID` = `U`.`UserID`))) left join `users` `U1` on((`E`.`AssignedTo` = `U1`.`UserID`))) left join `defectitem` `D` on((`E`.`ID` = `D`.`ID`)));

--
-- Описание для представления projectanderrorsview
--
DROP VIEW IF EXISTS projectanderrorsview CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW projectanderrorsview
AS
	select `p`.`ProjectID` AS `ProjectID`,`p`.`Name` AS `Name`,`p`.`Description` AS `Description`,`p`.`OwnerID` AS `OwnerID`,`p`.`NickName` AS `NickName`,`p`.`CreateDate` AS `CreateDate`,0 AS `CountRequests`,`p`.`CountUsers` AS `CountUsers`,count((case when (`E`.`Status` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`E`.`Status` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`E`.`Status` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`E`.`Status` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`E`.`Status` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED` from (`projectsinfoview` `P` left join `errorreport` `E` on((`E`.`ProjectID` = `p`.`ProjectID`))) group by `p`.`ProjectID`;

--
-- Описание для представления projectsinfoview
--
DROP VIEW IF EXISTS projectsinfoview CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW projectsinfoview
AS
	select `P`.`ProjectID` AS `ProjectID`,`P`.`Name` AS `Name`,left(`P`.`Description`,25) AS `Description`,`P`.`OwnerID` AS `OwnerID`,`U`.`NickName` AS `NickName`,`P`.`CreateDate` AS `CreateDate`,(count(`UP`.`ProjectID`) + (`P`.`OwnerID` is not null)) AS `CountUsers` from ((`projects` `P` left join `users` `U` on((`P`.`OwnerID` = `U`.`UserID`))) left join `usersinprojects` `UP` on((`UP`.`ProjectID` = `P`.`ProjectID`))) group by `P`.`ProjectID`;

--
-- Описание для представления projectsinfowithoutmeview
--
DROP VIEW IF EXISTS projectsinfowithoutmeview CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW projectsinfowithoutmeview
AS
	select `p`.`ProjectID` AS `ProjectID`,`p`.`Name` AS `Name`,`p`.`Description` AS `Description`,`p`.`OwnerID` AS `OwnerID`,`p`.`NickName` AS `NickName`,`p`.`CreateDate` AS `CreateDate`,`p`.`CountRequests` AS `CountRequests`,`p`.`CountUsers` AS `CountUsers`,`p`.`NEW` AS `NEW`,`p`.`IDENTIFIED` AS `IDENTIFIED`,`p`.`ASSESSED` AS `ASSESSED`,`p`.`RESOLVED` AS `RESOLVED`,`p`.`CLOSED` AS `CLOSED`,`U`.`UserID` AS `UserID` from (`usersinprojects` `U` join `projectanderrorsview` `P` on((`p`.`ProjectID` = `U`.`ProjectID`)));

--
-- Описание для представления projectswithusername
--
DROP VIEW IF EXISTS projectswithusername CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW projectswithusername
AS
	select `P`.`ProjectID` AS `ProjectID`,`P`.`Name` AS `Name`,`P`.`Description` AS `Description`,`P`.`OwnerID` AS `OwnerID`,`P`.`CreateDate` AS `CreateDate`,`U`.`NickName` AS `NickName` from (`projects` `P` left join `users` `U` on((`P`.`OwnerID` = `U`.`UserID`)));

--
-- Описание для представления projectusersinfo
--
DROP VIEW IF EXISTS projectusersinfo CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW projectusersinfo
AS
	select `P`.`OwnerID` AS `UserID`,`P`.`ProjectID` AS `ProjectID`,`U`.`NickName` AS `NickName`,count((case when (`E`.`Status` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`E`.`Status` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`E`.`Status` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`E`.`Status` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`E`.`Status` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED`,count(`E`.`ID`) AS `CountErrors`,1 AS `Owner` from ((`projects` `P` left join `users` `U` on((`P`.`OwnerID` = `U`.`UserID`))) left join `errorreport` `E` on(((`P`.`ProjectID` = `E`.`ProjectID`) and (`U`.`UserID` = `E`.`UserID`)))) group by `P`.`ProjectID`,`E`.`UserID` union select `UP`.`UserID` AS `UserID`,`UP`.`ProjectID` AS `ProjectID`,`U`.`NickName` AS `NickName`,count((case when (`E`.`Status` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`E`.`Status` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`E`.`Status` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`E`.`Status` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`E`.`Status` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED`,count(`E`.`ID`) AS `CountErrors`,0 AS `Owner` from ((`usersinprojects` `UP` left join `users` `U` on((`UP`.`UserID` = `U`.`UserID`))) left join `errorreport` `E` on(((`UP`.`ProjectID` = `E`.`ProjectID`) and (`UP`.`UserID` = `E`.`UserID`)))) group by `UP`.`ProjectID`,`UP`.`UserID`;

-- 
-- Вывод данных для таблицы DefectItem
--
INSERT INTO DefectItem VALUES 
  (2, 'Crash', ''),
  (3, 'Major', 'sdfasdfadsf'),
  (4, 'Error Handling', 'dsfasdfasdfsdf'),
  (5, 'Functional', 'asdfasdfasdfdsfasdfasdfasdf'),
  (6, 'Crash', ''),
  (7, 'Error Handling', ''),
  (8, 'Error Handling', ''),
  (9, 'Crash', ''),
  (10, 'Crash', ''),
  (11, 'Crash', ''),
  (12, 'Crash', ''),
  (13, 'Crash', ''),
  (14, 'Error Handling', ''),
  (15, 'Major', ''),
  (16, 'Major', ''),
  (17, 'Major', ''),
  (18, 'Block', ''),
  (19, 'Block', ''),
  (20, 'Block', ''),
  (21, 'Block', ''),
  (22, 'Major', ''),
  (23, 'Major', ''),
  (28, 'Major', ''),
  (29, 'Major', ''),
  (30, 'Major', ''),
  (31, 'Major', ''),
  (32, 'Major', ''),
  (33, 'Major', ''),
  (34, 'Major', 'пкупвапвапывап'),
  (35, 'Crash', 'dsghfdghfghdfgh'),
  (36, 'Block', 'Пиздееец'),
  (37, 'Major', ''),
  (38, 'Error Handling', ''),
  (39, 'Functional', ''),
  (40, 'Major', ''),
  (41, 'Major', ''),
  (42, 'Major', ''),
  (43, 'Crash', 'dfghdfh'),
  (44, 'Major', 'fhfghfgh'),
  (61, 'Crash', 'steps'),
  (62, 'Major', 'steps'),
  (63, 'Major', 'К жопе'),
  (66, 'Major', 'пропропро'),
  (67, 'Major', 'пропропо'),
  (75, 'Major', 'пароапроапроапорарполрлол'),
  (76, 'Cosmetic', '1. Перешёл на страницу комментариев айтема\r\n2. Нажал на заголовок сорируемого поля\r\n3. Сортировка выполнилась, но поле не выделилось'),
  (77, 'Crash', '1. Был изменен tpl шаблон для страниц &quot;Мои отчёты&quot; и &quot;Отчёты проекта&quot;\r\n2. При первом обращении вылезло сообщение об ошибке\r\n\r\nПадение Apache, файл php5ts.dll'),
  (78, 'Major', '1. Нажал создание бага\r\n2. Заполнил все поля\r\n3. Нажал сохранить\r\n4. При отображении поля &quot;Описание&quot; и &quot;Действия, которые привели к ошибке&quot; были урезаны.');

-- 
-- Вывод данных для таблицы ErorrReportHistory
--
-- Таблица не содержит данных

-- 
-- Вывод данных для таблицы ErrorReport
--
INSERT INTO ErrorReport VALUES 
  (2, 3, 2, 'Defect', '1', 'CLOSED', '2011-02-06 18:05:52', 'Возник BSOD', 'При попытке вызвать экран, вышла критическая ошибка', NULL),
  (3, 10, 3, 'Defect', '1', 'NEW', '2011-02-17 19:57:46', '', 'dsvfdgfdgdfg', NULL),
  (4, 3, 1, 'Defect', '1', 'ASSESSED', '2011-02-17 19:58:23', 'dfdsfd', 'dsfsdf', NULL),
  (5, 3, 2, 'Defect', '2', 'IDENTIFIED', '2011-02-17 20:12:23', 'sdfsd', 'sdfsadfasdfsdaf', NULL),
  (6, 3, 1, 'Defect', '1', 'NEW', '2011-02-24 23:19:28', 'Взрыв', '', NULL),
  (7, 3, 7, 'Defect', '2', 'NEW', '2011-02-24 23:20:08', 'Уничтожение', '', NULL),
  (8, 10, 3, 'Defect', '0', 'IDENTIFIED', '2011-02-24 23:20:24', 'Закрытие', '', NULL),
  (9, 1, 3, 'Defect', '2', 'RESOLVED', '2011-02-26 23:31:53', '', '', NULL),
  (10, 1, 2, 'Defect', '1', 'CLOSED', '2011-02-26 23:40:22', '', '', NULL),
  (11, 3, 6, 'Defect', '1', 'NEW', '2011-02-27 03:32:03', 'fsdfsdf', '', NULL),
  (12, 3, 6, 'Defect', '2', 'ASSESSED', '2011-02-27 03:34:34', 'sdfdsf', '', NULL),
  (13, 3, 11, 'Defect', '0', 'NEW', '2011-02-28 00:41:17', 'Искривление зеркала', '', NULL),
  (14, 13, 1, 'Defect', '2', 'RESOLVED', '2011-09-02 01:08:44', 'OK', '', NULL),
  (15, 3, 1, 'Defect', '2', 'NEW', '2011-09-04 14:14:54', '', '', NULL),
  (16, 13, 1, 'Defect', '2', 'NEW', '2011-09-04 14:26:32', '', '', NULL),
  (17, 13, 1, 'Defect', '2', 'NEW', '2011-09-04 14:27:02', '', '', NULL),
  (18, 13, 1, 'Defect', '2', 'NEW', '2011-09-04 14:44:24', 'Заголовок', '', NULL),
  (19, 13, 1, 'Defect', '2', 'ASSESSED', '2011-09-04 14:51:07', 'Заголовок', '', NULL),
  (20, 13, 1, 'Defect', '2', 'NEW', '2011-09-04 14:53:52', 'Заголовок', '', NULL),
  (21, 13, 1, 'Defect', '2', 'IDENTIFIED', '2011-09-04 14:54:44', 'Заголовок', '', NULL),
  (22, 13, 1, 'Defect', '2', 'NEW', '2011-09-04 15:31:15', '', '', NULL),
  (23, 13, 1, 'Defect', '2', 'NEW', '2011-09-04 15:31:23', '', '', NULL),
  (28, 13, 1, 'Defect', '2', 'NEW', '2011-09-04 16:22:50', 'аипавпрерпа', '', NULL),
  (29, 13, 1, 'Defect', '1', 'NEW', '2011-09-04 16:51:31', 'иапипаи', '', NULL),
  (30, 13, 1, 'Defect', '0', 'NEW', '2011-09-04 16:51:43', 'апрвапр', '', NULL),
  (31, 13, 21, 'Defect', '1', 'NEW', '2011-09-04 16:52:49', 'апрвапрвапрвапрвпарвапр', '', NULL),
  (32, 1, 22, 'Defect', '1', 'NEW', '2011-09-11 13:39:05', 'Herlo', '', NULL),
  (33, 1, 22, 'Defect', '1', 'NEW', '2011-09-11 14:14:32', 'Spin De Physics', '', 15),
  (34, 1, 22, 'Defect', '1', 'NEW', '2011-09-11 14:34:06', 'Zagolovok', 'ккпкупук', 3),
  (35, 7, 22, 'Defect', '1', 'NEW', '2011-09-11 14:42:12', 'fgfhfghdfghdfghdfgh', 'dgfdgdfg', NULL),
  (36, 1, 23, 'Defect', '0', 'NEW', '2011-09-11 14:43:11', 'Заголовок', 'Пиздееец', NULL),
  (37, 7, 22, 'Defect', '2', 'NEW', '2011-09-14 00:29:38', 'fgfdgf', 'fhgfh', NULL),
  (38, 7, 22, 'Defect', '1', 'ASSESSED', '2011-09-14 00:30:10', 'fghgfh', 'fghfgh', NULL),
  (39, 8, 22, 'Defect', '0', 'IDENTIFIED', '2011-09-14 00:30:33', 'fdgfdh', '', NULL),
  (40, 1, 22, 'Defect', '1', 'NEW', '2011-09-14 01:28:20', 'Заголовок', '', NULL),
  (41, 1, 23, 'Defect', '1', 'NEW', '2011-09-14 01:28:38', 'апыавпв', '', NULL),
  (42, 15, 24, 'Defect', '1', 'NEW', '2011-09-14 23:35:28', 'Aga', '', 1),
  (43, 1, 23, 'Defect', '2', 'NEW', '2011-09-15 00:41:45', 'fghfghfghghf', 'tgrhgfh', NULL),
  (44, 1, 22, 'Defect', '1', 'NEW', '2011-09-15 00:52:49', '1r32545', 'fghfh', NULL),
  (45, 1, 22, 'Defect', '1', 'NEW', '2011-09-22 02:08:40', 'Пнпн', '', NULL),
  (46, 1, 22, 'Defect', '1', 'NEW', '2011-09-24 18:48:14', 'gavnyo ', '', NULL),
  (47, 1, 22, 'Defect', '2', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (48, 1, 22, 'Defect', '2', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (49, 1, 22, 'Defect', '2', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (50, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (51, 1, 22, 'Defect', '2', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (52, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (53, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (54, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (55, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (56, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (57, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (58, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (59, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (60, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (61, 1, 22, 'Defect', '2', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (62, 1, 22, 'Defect', '2', 'NEW', '2011-09-25 16:00:00', 'title', 'description', NULL),
  (63, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:08:23', 'Ебашинск', 'Ебанунск', NULL),
  (64, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:21:26', 'Заголовок', 'Ага', NULL),
  (65, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:23:19', 'рпарар', 'апрпаор', NULL),
  (66, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:26:19', 'апроапро', 'прпрорпо', NULL),
  (67, 1, 22, 'Defect', '1', 'NEW', '2011-09-25 16:26:37', '1234567', 'пааапорпорпо', NULL),
  (68, 1, 22, 'Task', '1', 'NEW', '2011-09-25 16:29:56', 'апраправ', 'авпрвар', NULL),
  (69, 1, 22, 'Task', '1', 'NEW', '2011-10-02 12:29:08', 'Задача №1 ', 'Купить пива Василию', NULL),
  (70, 15, 47, 'Task', '1', 'NEW', '2011-10-03 22:35:06', 'сделать $100000000', 'делать самому', NULL),
  (71, 18, 48, 'Task', '0', 'NEW', '2011-10-06 00:55:55', 'Реализовать выделение текущего раздела меню', 'В настоящий момент меня всегда выделяется на разделе &quot;Мои отчёты&quot;. Нужно, чтобы выделялся на текущем.', 1),
  (72, 1, 48, 'Task', '2', 'NEW', '2011-10-06 02:24:18', 'Реализовать возможность нзначения айтема на пользователя текущего проекта', 'Должно работать поле, которое при выборе проекта обновляется и содержит список всех участников проекта, включая владельца. Логин создающего данный айтем должно находиться на первом месте, также если пустое значение, то айтем не будет назначен кому-либо.', NULL),
  (73, 1, 48, 'Task', '1', 'NEW', '2011-10-06 02:29:50', 'Удаление всех выделенных айтемов из грида в Моих отчётах', '1. Переходим на страницу Мои отчёты.\r\n2. Пользователь должен удалять все выделенные айтемы в гриде, нажав чекбокс в вернем углу грида. При нажатии &quot;Удалить выделенные&quot; должно быть показано диалоговое окно подтверждения. \r\n3. Если нажато [Да] - у', NULL),
  (74, 1, 22, 'Task', '1', 'NEW', '2011-10-06 02:34:08', 'ролрол', 'тьиьтиь', NULL),
  (75, 1, 22, 'Defect', '1', 'NEW', '2011-10-06 02:34:56', 'орпопропр', 'апорапопо', NULL),
  (76, 1, 48, 'Defect', '0', 'NEW', '2011-10-06 02:52:31', 'В комментариях у грида не подсвечивается текущее поле, по которому сортируем', 'Поле грида, по которому сортируем, должно выделяться в красный цвет.', NULL),
  (77, 18, 48, 'Defect', '2', 'NEW', '2011-10-06 03:01:38', 'Если отсутствует скомпилированный шаблон для страниц &quot;Мои отчёты&quot; и &quot;Отчёты проекта&quot;, происходит исключение в Apache', 'Краш отчёт:\r\n\r\nСигнатура проблемы:\r\n  Имя события проблемы:\tAPPCRASH\r\n  Имя приложения:\thttpd.exe\r\n  Версия приложения:\t2.2.4.0\r\n  Отметка времени приложения:\t45a476e3\r\n  Имя модуля с ошибкой:\tphp5ts.dll\r\n  Версия модуля с ошибкой:\t5.2.4.4\r\n  Отметка врем', NULL),
  (78, 18, 48, 'Defect', '1', 'NEW', '2011-10-06 03:05:15', 'При создании айтема урезается текст в полях &quot;Описание&quot; и &quot;Действия, которые привели к ошибке&quot;', 'Текст после создания айтема урезается, что неверно. Нужно увеличить до 1024 символов поля &quot;Описание&quot; и &quot;Действия, которые привели к ошибке&quot;.\r\n\r\nНадо пофиксить.', NULL);

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
  (14, 'Модуль ошибок', '', 'Error'),
  (11, 'Tarakaning', '', 'Tarakaning'),
  (6, 'Auth', 'Модуль аутентификации', 'Auth'),
  (10, '', '', 'Profile');

-- 
-- Вывод данных для таблицы Projects
--
INSERT INTO Projects VALUES 
  (1, 'Quki', 'вот наш один из долгостроев))', 13, '0000-00-00 00:00:00'),
  (2, 'Tarakaning', 'Баг-треккер на начальной стадии', 13, '0000-00-00 00:00:00'),
  (3, 'Fuck', NULL, 10, '0000-00-00 00:00:00'),
  (4, 'Siyfat', 'dfhdfgh', 11, '0000-00-00 00:00:00'),
  (5, 'Spektr-kzn', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (6, 'IMG', 'dfhdfgh', 14, '0000-00-00 00:00:00'),
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
  (23, 'Herlllou', 'Проект ниачём', 1, '2011-09-11 14:09:52'),
  (24, 'Gaphy Gaph', 'GuGaGaShenki', 15, '2011-09-14 23:34:44'),
  (25, 'fghfh', 'fghfgh', 1, '2011-09-21 21:37:48'),
  (26, 'fghdfh', 'dfghdfghdfgh', 1, '2011-09-21 21:37:54'),
  (27, 'fthfdgh', 'fghdfhdfhfdghdrhgdfgh', 1, '2011-09-21 21:38:01'),
  (28, 'dfghfdshfgh', 'rthetyhjtyjghhjfgjhgj', 1, '2011-09-21 21:38:08'),
  (29, 'vgcchgjhgjghjg', 'ghhhgjfghjfghjhgjfhgj', 1, '2011-09-21 21:38:19'),
  (30, 'gfhhgfdghjk', 'gfjgfjfgjghjgfhjfgj', 1, '2011-09-21 21:38:26'),
  (31, 'cghjgchj', 'ghjfghjghjfghj', 1, '2011-09-21 21:38:32'),
  (32, 'hgjghdjfghj', 'fhjjdfgdjfgjhgjgf', 1, '2011-09-21 21:38:39'),
  (33, 'gfjhfggj', 'aaaaaaaaaa', 1, '2011-09-21 21:38:46'),
  (34, 'aaaaaaaaaaaaaaaaaa', '', 1, '2011-09-21 21:38:51'),
  (35, 'ssssssssssssss', '', 1, '2011-09-21 21:38:55'),
  (36, 'dddddddddddddddddddddd', '', 1, '2011-09-21 21:39:00'),
  (37, 'gggggggggggggggggg', '', 1, '2011-09-21 21:39:07'),
  (38, 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj', '', 1, '2011-09-21 21:39:27'),
  (39, 'dddddddddddddddddddddddddddddddd', '', 1, '2011-09-21 21:40:05'),
  (40, 'gfhdfhf', '', 1, '2011-09-21 22:42:11'),
  (41, 'dfhghdfgh', '', 1, '2011-09-21 22:42:15'),
  (42, 'dfhdfghdgfh', '', 1, '2011-09-21 22:42:19'),
  (43, 'dfhdfhgfh', '', 1, '2011-09-21 22:42:23'),
  (44, 'fghjgjghj', '', 1, '2011-09-21 23:26:41'),
  (45, '123', '', 1, '2011-09-21 23:26:58'),
  (46, 'смиаи', 'апиапи', 1, '2011-09-22 21:06:49'),
  (47, 'проект', 'проекта описание\\r\\n', 15, '2011-10-03 22:33:35'),
  (48, 'Tarakaning', 'Баг-треккер, таскер', 1, '2011-10-05 23:45:25');

-- 
-- Вывод данных для таблицы ReportComment
--
INSERT INTO ReportComment VALUES 
  (1, 37, 1, '2011-09-19 23:36:47', 'Ты чё ёптэ. Исправляй нах баг'),
  (2, 37, 15, '2011-09-19 23:36:59', 'Ебись ты в корень!'),
  (3, 37, 1, '2011-09-20 01:27:44', ''),
  (4, 37, 1, '2011-09-20 01:28:32', 'Хрен'),
  (5, 37, 1, '2011-09-20 01:29:40', ' Жопло'),
  (6, 37, 1, '2011-09-20 01:30:15', ' Note: translations are provided by Zend Framework community volunteer effort. Each translation of the reference guide may be incomplete. Sections that have not been translated yet are included in English. Translations that are less than 50% complete are '),
  (7, 37, 1, '2011-09-20 01:41:20', ' \r\n\r\nProgrammer\\''s Reference Guide\r\n\r\nDownload\r\nEnglish\r\nzip | tar.gz\r\nDeutsch (German)\r\nzip | tar.gz\r\nFran&amp;#231;ais (French)\r\nzip | tar.gz\r\n&amp;#26085;&amp;#26412;&amp;#35486; (Japanese)\r\nzip | tar.gz\r\nРусский (Russian)\r\nzip | tar.gz\r\n&amp;#31616;&a'),
  (8, 37, 1, '2011-09-20 01:41:54', ' Zend Framework is an open source, object oriented web application framework for PHP 5. Zend Framework is often called a \\''component library\\'', because it has many loosely coupled components that you can use more or less independently. But Zend Framework '),
  (9, 37, 15, '2011-09-20 01:45:11', ' Using these components, we will build a simple database-driven guest book application within minutes. The complete source code for this application is available in the following archives:'),
  (10, 63, 1, '2011-09-25 16:08:41', ' Бляяяя'),
  (11, 63, 1, '2011-09-25 16:08:57', ' Жопировск'),
  (12, 63, 1, '2011-09-25 16:09:06', ' Не то слово братанище)))'),
  (13, 32, 1, '2011-09-25 21:21:23', ' авпывап\r\n'),
  (14, 32, 1, '2011-09-25 21:21:25', ' авпвапвап'),
  (15, 32, 1, '2011-09-25 21:21:28', ' выапывапвапвапва'),
  (16, 32, 1, '2011-09-25 21:22:09', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (17, 32, 1, '2011-09-25 21:22:18', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (18, 32, 1, '2011-09-25 21:22:21', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (19, 32, 1, '2011-09-25 21:22:25', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (20, 32, 1, '2011-09-25 21:22:41', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (21, 32, 1, '2011-09-25 21:22:45', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (22, 32, 1, '2011-09-25 21:22:48', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (23, 32, 1, '2011-09-25 21:22:51', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (24, 32, 1, '2011-09-25 21:22:55', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (25, 32, 1, '2011-09-25 21:22:58', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (26, 32, 1, '2011-09-25 21:23:18', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (27, 32, 1, '2011-09-25 21:23:21', ' ставишь Recovery 4.0.0.5, поттом бросаешь на sd карту скачанный zip файл, выключаешь телефон, жмешь одновременно клавишу увеличения громкости и включения-телефон зайдет в рековери меню, дальше выполняешь \\&quot;Wipe data/factory reset\\&quot; , потом \\&qu'),
  (28, 32, 1, '2011-09-25 21:23:41', ' Разогнан проц до 1ггц, в Quadrant на стоковой проше у меня выдавало 1400 попугаев, а тут под 2300, т.е прирост большой к производительности. Как говорил- очень гибко настраивается, т.е если на стоковой проше, чтобы выставить некоторые настройки нужно был'),
  (29, 32, 1, '2011-09-25 21:23:44', ' Разогнан проц до 1ггц, в Quadrant на стоковой проше у меня выдавало 1400 попугаев, а тут под 2300, т.е прирост большой к производительности. Как говорил- очень гибко настраивается, т.е если на стоковой проше, чтобы выставить некоторые настройки нужно был'),
  (30, 32, 1, '2011-09-25 21:23:55', ' Разогнан проц до 1ггц, в Quadrant на стоковой проше у меня выдавало 1400 попугаев, а тут под 2300, т.е прирост большой к производительности. Как говорил- очень гибко настраивается, т.е если на стоковой проше, чтобы выставить некоторые настройки нужно был'),
  (31, 32, 1, '2011-09-26 01:39:14', ' роорпо'),
  (32, 32, 1, '2011-09-26 01:42:20', ' роорпо'),
  (33, 32, 1, '2011-09-26 01:42:42', ' роорпо'),
  (34, 69, 1, '2011-10-02 12:41:01', ' Ага, куплю тебе, Василий)))'),
  (35, 70, 15, '2011-10-03 22:35:43', ' привет'),
  (36, 70, 15, '2011-10-03 22:35:50', ' сделаем это!'),
  (37, 57, 1, '2011-10-05 21:25:43', ' Hui'),
  (38, 57, 1, '2011-10-05 21:25:49', ' Blyaaa'),
  (39, 32, 1, '2011-10-06 02:46:35', ' Хреновый баг короче у нас((((');

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
  (70, 'show', 'Инфорация о проекте', 'Инфорация о проекте', 11, 0, 64, 1),
  (87, 'bugs', '', '', 11, 0, 64, 1),
  (72, 'profile', '', '', 10, 0, 1, 0),
  (73, 'show', '', '', 10, 0, 72, 1),
  (74, 'edit', '', '', 10, 0, 72, 1);

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
  (14, 'Kolya', '', '', '', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 1, '', NULL, NULL),
  (15, 'Android', 'Android', 'Android', 'Android', '5a0cf5667029ac6bbad1c4ecdc3f659e', 0, 1, '', NULL, NULL),
  (16, 'Los', '', '', '', '408edad392248bc60f0e7ddaed995fe5', 0, 0, '', NULL, NULL),
  (17, 'Egor', 'Egor', 'Vasilyev', 'Ermolaev', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 1, 'aik2029@gmail.com', NULL, NULL),
  (18, 'Timur', 'Тимур', 'Юсупзянов', 'Равхатович', 'a25960829aeaba68de7c7a0a669a5023', 0, 1, 'gtimur7@gmail.com', NULL, NULL);

-- 
-- Вывод данных для таблицы UsersInProjects
--
INSERT INTO UsersInProjects VALUES 
  (10, 3, 1),
  (12, 6, 1),
  (6, 1, 3),
  (1, 2, 3),
  (8, 6, 3),
  (2, 1, 6),
  (20, 22, 7),
  (21, 22, 8),
  (4, 3, 9),
  (11, 3, 13),
  (7, 5, 13),
  (23, 6, 15),
  (22, 22, 15),
  (27, 48, 18);

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;