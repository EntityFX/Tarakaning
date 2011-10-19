-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 5.0.50.1
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 20.10.2011 2:56:28
-- Версия сервера: 5.1.40-community
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
    REFERENCES errorreport(ID) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AVG_ROW_LENGTH = 481
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
  OldTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  Description TEXT DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ErorrReportHistory_ErrorReport_ID (ErrorReportID),
  INDEX FK_ErorrReportHistory_Users_UserID (UserID),
  CONSTRAINT FK_ErorrReportHistory_ErrorReport_ID FOREIGN KEY (ErrorReportID)
    REFERENCES errorreport(ID) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 131
AVG_ROW_LENGTH = 442
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
    REFERENCES projects(ProjectID) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FK_ErrorReport_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 8722
AVG_ROW_LENGTH = 190
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
    REFERENCES users(UserID) ON DELETE SET NULL ON UPDATE SET NULL
)
ENGINE = INNODB
AUTO_INCREMENT = 52
AVG_ROW_LENGTH = 606
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
    REFERENCES errorreport(ID) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FK_ReportComment_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 75
AVG_ROW_LENGTH = 780
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
    REFERENCES errorreport(ID) ON DELETE CASCADE ON UPDATE CASCADE,
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
    REFERENCES projects(ProjectID) ON DELETE CASCADE ON UPDATE CASCADE,
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
    REFERENCES projects(ProjectID) ON DELETE CASCADE ON UPDATE CASCADE,
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
PROCEDURE AddItem(IN UserID INT, IN ProjectID INT, IN AssignedTo INT, IN PriorityLevel VARCHAR(1), IN StatusValue VARCHAR(50), IN `Date` DATETIME, IN Title VARCHAR(255), IN Kind VARCHAR(50), IN Description TEXT, IN DefectType VARCHAR(50), IN StepsText TEXT)
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
            Description,
            `AssignedTo`
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
            Description,
            AssignedTo
        );

    SET LAST_ID=(SELECT last_insert_id() FROM ErrorReport LIMIT 0,1);

    IF Kind = 'Defect' THEN
        INSERT INTO DefectItem (ID,DefectType,StepsText) 
            VALUES (LAST_ID,DefectType,StepsText);
    END IF; 
END
$$

--
-- Описание для процедуры DeleteCommentsFromList
--
DROP PROCEDURE IF EXISTS DeleteCommentsFromList$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE DeleteCommentsFromList(IN _UserID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString INT;
    DECLARE ItemValue INT;

    SET SymbolPosition=1;

    CREATE TEMPORARY TABLE ItemsTable (
        ItemID INT
    );

    -- Parsing incoming data
    z1: WHILE SymbolPosition>0 DO
        SELECT LOCATE(';',ItemsList,SymbolPosition) INTO SymbolPosition;
        SELECT SUBSTRING(ItemsList,SymbolPosition+1,LOCATE(';',ItemsList,SymbolPosition+1)-SymbolPosition-1) INTO ItemString;
        SELECT CAST(ItemString AS SIGNED ) INTO ItemValue;
        INSERT INTO ItemsTable VALUES (ItemValue);
        IF SymbolPosition=0 THEN 
            LEAVE z1;
        END IF;
        SET SymbolPosition=SymbolPosition+1;
    END WHILE;

    CREATE TEMPORARY TABLE CommentsForDelete (
        ItemID INT
    );

    INSERT INTO CommentsForDelete SELECT ID FROM 
        ReportComment RC
    INNER JOIN  ItemsTable I
        ON RC.ID=I.ItemID
    WHERE UserID=_UserID;

    DELETE FROM ReportComment WHERE ID IN (SELECT ItemID FROM CommentsForDelete);

END
$$

--
-- Описание для процедуры DeleteItemsFromList
--
DROP PROCEDURE IF EXISTS DeleteItemsFromList$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE DeleteItemsFromList(IN _UserID INT, IN _ProjectID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString INT;
    DECLARE ItemValue INT;

    DECLARE _OwnerID INT;

    SET SymbolPosition=1;

    CREATE TEMPORARY TABLE ItemsTable (
        ItemID INT
    );

    -- Parsing incoming data
    z1: WHILE SymbolPosition>0 DO
        SELECT LOCATE(';',ItemsList,SymbolPosition) INTO SymbolPosition;
        SELECT SUBSTRING(ItemsList,SymbolPosition+1,LOCATE(';',ItemsList,SymbolPosition+1)-SymbolPosition-1) INTO ItemString;
        SELECT CAST(ItemString AS SIGNED ) INTO ItemValue;
        INSERT INTO ItemsTable VALUES (ItemValue);
        IF SymbolPosition=0 THEN 
            LEAVE z1;
        END IF;
        SET SymbolPosition=SymbolPosition+1;
    END WHILE;

    SELECT OwnerID INTO _OwnerID FROM Projects WHERE ProjectID=_ProjectID;

    CREATE TEMPORARY TABLE ItemsForDelete (
        ItemID INT
    );

    /*
    Is current user project owner
    */
    IF _UserID=_OwnerID THEN
        INSERT INTO ItemsForDelete (ItemID) (SELECT 
                E.ID
            FROM 
                ErrorReport E
            INNER JOIN ItemsTable T
                ON E.ID=`T`.ItemID
            WHERE E.ProjectID=_ProjectID);

    ELSE
        INSERT INTO ItemsForDelete (ItemID) (SELECT 
                E.ID
            FROM 
                ErrorReport E
            INNER JOIN ItemsTable T
                ON E.ID=`T`.ItemID
            WHERE E.UserID=_UserID AND E.ProjectID=_ProjectID);
    END IF;

    DELETE FROM ErrorReport WHERE ID IN (SELECT ItemID FROM ItemsForDelete);

END
$$

--
-- Описание для процедуры DeleteProjects
--
DROP PROCEDURE IF EXISTS DeleteProjects$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE DeleteProjects(IN _UserID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString INT;
    DECLARE ItemValue INT;

    SET SymbolPosition=1;

    CREATE TEMPORARY TABLE ItemsTable (
        ItemID INT
    );

    -- Parsing incoming data
    z1: WHILE SymbolPosition>0 DO
        SELECT LOCATE(';',ItemsList,SymbolPosition) INTO SymbolPosition;
        SELECT SUBSTRING(ItemsList,SymbolPosition+1,LOCATE(';',ItemsList,SymbolPosition+1)-SymbolPosition-1) INTO ItemString;
        SELECT CAST(ItemString AS SIGNED ) INTO ItemValue;
        INSERT INTO ItemsTable VALUES (ItemValue);
        IF SymbolPosition=0 THEN 
            LEAVE z1;
        END IF;
        SET SymbolPosition=SymbolPosition+1;
    END WHILE;

    CREATE TEMPORARY TABLE ProjectsForDelete (
        ItemID INT
    );

    INSERT INTO ProjectsForDelete SELECT ProjectID FROM 
        Projects P
    INNER JOIN  ItemsTable I
        ON P.ProjectID=I.ItemID
    WHERE P.OwnerID=_UserID;

    DELETE FROM Projects WHERE ProjectID IN (SELECT ItemID FROM ProjectsForDelete);

END
$$

--
-- Описание для процедуры EditItem
--
DROP PROCEDURE IF EXISTS EditItem$$
CREATE DEFINER = 'root'@'localhost'
PROCEDURE EditItem(IN _ItemID INT, IN _Title VARCHAR(255), IN _PriorityLevel VARCHAR(1), IN _StatusValue VARCHAR(50), IN _AssignedTo INT, IN _Description TEXT, IN _DefectType VARCHAR(50), IN _StepsText TEXT)
BEGIN
    DECLARE ItemProjectID INT;
    DECLARE ItemKind VARCHAR(50);
    DECLARE AssignedProjectID INT;

    SELECT ProjectID, Kind INTO ItemProjectID,ItemKind FROM ErrorReport WHERE ID=_ItemID;

    IF _AssignedTo!=NULL THEN
        SET AssignedProjectID=(SELECT ProjectID FROM UsersInProjects WHERE UserID=_AssignedTo
        UNION
        SELECT ProjectID FROM Projects WHERE OwnerID=_AssignedTo);

        IF ItemProjectID<>AssignedProjectID THEN 
            SET _AssignedTo=NULL;
        END IF;
    END IF;

    IF _Title<>'' THEN
        UPDATE ErrorReport SET 
            Title=_Title, 
            PriorityLevel=_PriorityLevel,
            `Status`=_StatusValue,
            Description=_Description,
            AssignedTo=_AssignedTo
        WHERE 
            ID=_ItemID;
    
        IF ItemKind='Defect' THEN
            UPDATE DefectItem SET DefectType=_DefectType, StepsText=_StepsText WHERE ID=_ItemID;
        END IF;
    END IF;
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
-- Описание для представления errorcommentscount
--
DROP VIEW IF EXISTS errorcommentscount CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW errorcommentscount
AS
	select `RC`.`UserID` AS `UserID`,`RC`.`ReportID` AS `ReportID`,`E`.`ProjectID` AS `ProjectID`,count(`RC`.`ID`) AS `ItemUserComment` from (`reportcomment` `RC` join `errorreport` `E` on((`RC`.`ReportID` = `E`.`ID`))) group by `RC`.`ReportID`,`RC`.`UserID`;

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
-- Описание для представления projectcomentscount
--
DROP VIEW IF EXISTS projectcomentscount CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW projectcomentscount
AS
	select `up`.`ProjectID` AS `ProjectID`,`up`.`UserID` AS `UserID`,ifnull(sum(`ec`.`ItemUserComment`),0) AS `CountComments` from (`alluserprojects` `UP` left join `errorcommentscount` `EC` on(((`up`.`ProjectID` = `ec`.`ProjectID`) and (`up`.`UserID` = `ec`.`UserID`)))) group by `up`.`UserID`,`up`.`ProjectID`;

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
	select `P`.`OwnerID` AS `UserID`,`P`.`ProjectID` AS `ProjectID`,`U`.`NickName` AS `NickName`,count((case when (`E`.`Status` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`E`.`Status` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`E`.`Status` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`E`.`Status` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`E`.`Status` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED`,count(`E`.`ID`) AS `CountErrors`,1 AS `Owner` from ((`projects` `P` left join `users` `U` on((`P`.`OwnerID` = `U`.`UserID`))) left join `errorreport` `E` on(((`P`.`ProjectID` = `E`.`ProjectID`) and (`P`.`OwnerID` = `E`.`AssignedTo`)))) group by `P`.`ProjectID`,`P`.`OwnerID`,`U`.`UserID`,`E`.`AssignedTo` union select `UP`.`UserID` AS `UserID`,`UP`.`ProjectID` AS `ProjectID`,`U`.`NickName` AS `NickName`,count((case when (`E`.`Status` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`E`.`Status` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`E`.`Status` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`E`.`Status` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`E`.`Status` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED`,count(`E`.`ID`) AS `CountErrors`,0 AS `Owner` from ((`usersinprojects` `UP` left join `users` `U` on((`UP`.`UserID` = `U`.`UserID`))) left join `errorreport` `E` on(((`UP`.`ProjectID` = `E`.`ProjectID`) and (`UP`.`UserID` = `E`.`AssignedTo`)))) group by `UP`.`ProjectID`,`UP`.`UserID`;

--
-- Описание для представления projectusersinfofull
--
DROP VIEW IF EXISTS projectusersinfofull CASCADE;
CREATE OR REPLACE 
	DEFINER = 'root'@'localhost'
VIEW projectusersinfofull
AS
	select `p`.`UserID` AS `UserID`,`p`.`ProjectID` AS `ProjectID`,`p`.`NickName` AS `NickName`,`p`.`NEW` AS `NEW`,`p`.`IDENTIFIED` AS `IDENTIFIED`,`p`.`ASSESSED` AS `ASSESSED`,`p`.`RESOLVED` AS `RESOLVED`,`p`.`CLOSED` AS `CLOSED`,`p`.`CountErrors` AS `CountErrors`,`p`.`Owner` AS `Owner`,count(`E`.`ID`) AS `CountCreated`,`pc`.`CountComments` AS `CountComments` from ((`projectusersinfo` `P` left join `errorreport` `E` on(((`p`.`ProjectID` = `E`.`ProjectID`) and (`p`.`UserID` = `E`.`UserID`)))) join `projectcomentscount` `PC` on(((`p`.`ProjectID` = `pc`.`ProjectID`) and (`p`.`UserID` = `pc`.`UserID`)))) group by `p`.`ProjectID`,`p`.`UserID`;

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
  (31, 'Major', ''),
  (42, 'Major', ''),
  (43, 'Crash', 'dfghdfh'),
  (76, 'Cosmetic', '1. Перешёл на страницу комментариев айтема\r\n2. Нажал на заголовок сорируемого поля\r\n3. Сортировка выполнилась, но поле не выделилось'),
  (77, 'Crash', '1. Был изменен tpl шаблон для страниц &quot;Мои отчёты&quot; и &quot;Отчёты проекта&quot;\r\n2. При первом обращении вылезло сообщение об ошибке\r\n\r\nПадение Apache, файл php5ts.dll'),
  (78, 'Major', '1. Нажал создание бага\r\n2. Заполнил все поля\r\n3. Нажал сохранить\r\n4. При отображении поля &quot;Описание&quot; и &quot;Действия, которые привели к ошибке&quot; были урезаны.'),
  (84, 'Major', '1. Перешёл в раздел добавления айтемов.\r\n2. Заполнил все поля\r\n3. Сохранил\r\n4. Текст обрезался.'),
  (87, 'Major', 'Меню всегда активна на Мои Отчёты - это неправильно'),
  (91, 'Minor', '1. Переходим на страницу &quot;Мои отчёты&quot;<br />\r\n2. Выбираем в выпадающем списке &quot;Проекты&quot; нужный проект<br />\r\n3. Должно обновится на выбранный проект, но обновляется на тот, который по-умолчанию'),
  (106, 'Major', '1. Перешёл на страницу &quot;Профиль&quot;<br />\r\n2. Нажал кнопку &quot;Редактировать профиль&quot;<br />\r\n3. Выбрал вкладку &quot;Смена пароля&quot;<br />\r\n4. Ничего не заполнял<br />\r\n5. Нажал сохранить. Система показала успешное сохранение, но произошла потеря данных (см. Описание)'),
  (107, 'Minor', '1. Перешёл на страницу проекта.<br />\r\n2. Открыл вкладку &quot;Участники&quot;<br />\r\n3. Навёл на ссылку пользователя и нажал на неё - не перенаправляет на страницу профиля пользователя.'),
  (8689, 'Major', 'ghhjghj'),
  (8721, 'Major', '1 пунктов от увеличения частоты — 3.0/2.6=115% \r\n\r\nпока они не раскроют "источники" этого прироста — предсказатель переходов, уже говорили. Хотя сомнительно, он и так во всех современных ЦП работает на 98-99%. Тогда где-то затыки приуменьшили. Или префетчер подкрутили. Проверим \r\n\r\n48 — и это само по себе любопытно — им при этом удалось ещё и латентность уменьшить.');

-- 
-- Вывод данных для таблицы ErorrReportHistory
--
INSERT INTO ErorrReportHistory VALUES 
  (2, 136, 1, '2011-10-18 03:53:39', 'Пользователь 1 изменил статус с Array на Array'),
  (3, 136, 1, '2011-10-18 03:55:25', 'Пользователь 1 изменил статус с Array на Array'),
  (4, 136, 1, '2011-10-18 04:00:58', 'Пользователь 1 изменил статус с Оценён на Идентифицирован'),
  (5, 136, 1, '2011-10-18 04:01:38', 'Пользователь <strong>1</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Новый</strong>'),
  (6, 8714, 1, '2011-10-18 04:23:37', 'Задача добавлена'),
  (7, 8714, 1, '2011-10-18 04:23:49', 'Пользователь <strong>1</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (8, 8714, 1, '2011-10-18 04:23:51', 'Пользователь <strong>1</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Новый</strong>'),
  (9, 8714, 1, '2011-10-18 04:25:20', 'Пользователь <strong>1</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (10, 136, 15, '2011-10-18 04:26:09', 'Пользователь <strong>15</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (11, 136, 1, '2011-10-18 04:27:39', 'Пользователь <strong>1</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (12, 136, 15, '2011-10-18 04:27:42', 'Пользователь <strong>15</strong> изменил статус с <strong>Оценён</strong> на <strong>Идентифицирован</strong>'),
  (13, 136, 1, '2011-10-18 04:28:00', 'Пользователь <strong>1</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (14, 136, 15, '2011-10-18 04:28:02', 'Пользователь <strong>15</strong> изменил статус с <strong>Оценён</strong> на <strong>Идентифицирован</strong>'),
  (15, 136, 15, '2011-10-18 04:28:11', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (16, 8715, 1, '2011-10-19 00:03:57', 'Задача добавлена'),
  (17, 8716, 15, '2011-10-19 00:51:21', 'Задача добавлена'),
  (18, 8714, 15, '2011-10-19 01:17:42', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (19, 8714, 15, '2011-10-19 01:18:38', 'Пользователь <strong>15</strong> изменил статус с <strong>Оценён</strong> на <strong>Решён</strong>'),
  (20, 8714, 15, '2011-10-19 01:18:52', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Новый</strong>'),
  (21, 8714, 15, '2011-10-19 01:18:54', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Новый</strong>'),
  (22, 8714, 15, '2011-10-19 01:19:33', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Идентифицирован</strong>'),
  (23, 8714, 15, '2011-10-19 01:21:45', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Идентифицирован</strong>'),
  (24, 8714, 15, '2011-10-19 01:22:03', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Оценён</strong>'),
  (25, 8714, 15, '2011-10-19 01:22:09', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Идентифицирован</strong>'),
  (26, 8714, 15, '2011-10-19 01:24:00', 'Пользователь <strong>15</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (27, 8714, 15, '2011-10-19 01:24:11', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (28, 8714, 15, '2011-10-19 01:24:13', 'Пользователь <strong>15</strong> изменил статус с <strong>Оценён</strong> на <strong>Новый</strong>'),
  (29, 8714, 15, '2011-10-19 01:26:07', 'Пользователь <strong>15</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (30, 8714, 15, '2011-10-19 01:26:10', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (31, 8714, 15, '2011-10-19 01:28:04', 'Пользователь <strong>15</strong> изменил статус с <strong>Оценён</strong> на <strong>Идентифицирован</strong>'),
  (32, 8714, 15, '2011-10-19 01:28:06', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (33, 8714, 15, '2011-10-19 01:28:08', 'Пользователь <strong>15</strong> изменил статус с <strong>Оценён</strong> на <strong>Решён</strong>'),
  (34, 8714, 15, '2011-10-19 01:28:11', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Оценён</strong>'),
  (35, 8714, 15, '2011-10-19 01:29:11', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Идентифицирован</strong>'),
  (36, 8714, 15, '2011-10-19 01:31:05', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Идентифицирован</strong>'),
  (37, 8714, 15, '2011-10-19 01:31:07', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (38, 8714, 15, '2011-10-19 01:31:10', 'Пользователь <strong>15</strong> изменил статус с <strong>Оценён</strong> на <strong>Решён</strong>'),
  (39, 8714, 15, '2011-10-19 01:36:55', 'Пользователь <strong>15</strong> изменил статус с <strong>Решён</strong> на <strong>Идентифицирован</strong>'),
  (40, 8714, 15, '2011-10-19 01:37:58', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Новый</strong>'),
  (41, 8714, 15, '2011-10-19 01:44:04', 'Пользователь <strong>15</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (42, 8714, 15, '2011-10-19 01:44:08', 'Пользователь <strong>15</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Новый</strong>'),
  (43, 136, 1, '2011-10-19 01:45:34', 'Пользователь <strong>1</strong> изменил статус с <strong>Оценён</strong> на <strong>Решён</strong>'),
  (44, 136, 1, '2011-10-19 01:45:39', 'Пользователь <strong>1</strong> изменил статус с <strong>Решён</strong> на <strong>Закрыт</strong>'),
  (45, 136, 1, '2011-10-19 01:45:43', 'Пользователь <strong>1</strong> изменил статус с <strong>Закрыт</strong> на <strong>Идентифицирован</strong>'),
  (46, 136, 1, '2011-10-19 01:51:37', 'Пользователь <strong>1</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (47, 136, 1, '2011-10-19 01:51:43', 'Пользователь <strong>1</strong> изменил статус с <strong>Оценён</strong> на <strong>Оценён</strong>'),
  (48, 136, 1, '2011-10-19 01:51:48', 'Пользователь <strong>1</strong> изменил статус с <strong>Оценён</strong> на <strong>Новый</strong>'),
  (49, 82, 1, '2011-10-19 01:52:25', 'Пользователь <strong>1</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (50, 82, 1, '2011-10-19 01:52:31', 'Пользователь <strong>1</strong> изменил статус с <strong>Оценён</strong> на <strong>Идентифицирован</strong>'),
  (51, 82, 18, '2011-10-19 01:54:23', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (52, 82, 18, '2011-10-19 01:54:32', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Новый</strong>'),
  (53, 82, 1, '2011-10-19 01:54:43', 'Пользователь <strong>1</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (54, 8717, 18, '2011-10-19 21:14:11', 'Задача добавлена'),
  (55, 8718, 18, '2011-10-19 21:14:22', 'Задача добавлена'),
  (56, 8719, 1, '2011-10-19 21:17:36', 'Задача добавлена'),
  (57, 8720, 1, '2011-10-19 21:18:13', 'Задача добавлена'),
  (58, 8721, 18, '2011-10-19 21:29:47', 'Задача добавлена'),
  (59, 8721, 18, '2011-10-19 21:30:01', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (60, 8721, 18, '2011-10-19 21:30:25', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (61, 8721, 18, '2011-10-19 21:30:27', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (62, 8721, 18, '2011-10-19 21:30:28', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (63, 8721, 18, '2011-10-19 21:30:30', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (64, 8721, 18, '2011-10-19 21:30:31', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (65, 8721, 18, '2011-10-19 21:30:33', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (66, 8721, 18, '2011-10-19 21:30:35', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (67, 8721, 18, '2011-10-19 21:30:36', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (68, 8721, 18, '2011-10-19 21:30:37', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (69, 8721, 18, '2011-10-19 21:30:40', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (70, 8721, 18, '2011-10-19 21:30:42', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (71, 8721, 18, '2011-10-19 21:30:44', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (72, 8721, 18, '2011-10-19 21:30:45', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (73, 8721, 18, '2011-10-19 21:30:47', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (74, 8721, 18, '2011-10-19 21:30:50', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (75, 8721, 18, '2011-10-19 21:30:51', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (76, 8721, 18, '2011-10-19 21:30:53', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (77, 8721, 18, '2011-10-19 21:31:11', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (78, 8721, 18, '2011-10-19 21:31:18', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (79, 8721, 18, '2011-10-19 21:31:46', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (80, 8721, 18, '2011-10-19 21:31:49', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (81, 8721, 18, '2011-10-19 21:32:06', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (82, 8721, 18, '2011-10-19 21:32:16', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (83, 8721, 18, '2011-10-19 21:32:18', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (84, 8721, 18, '2011-10-19 21:32:20', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (85, 8721, 18, '2011-10-19 21:32:22', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (86, 8721, 18, '2011-10-19 21:32:31', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (87, 82, 1, '2011-10-19 22:01:24', 'Пользователь <strong>1</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (88, 8717, 18, '2011-10-19 22:07:01', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (89, 8717, 18, '2011-10-19 22:07:06', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (90, 82, 18, '2011-10-19 22:07:37', 'Пользователь <strong>18</strong> изменил статус с <strong>Оценён</strong> на <strong>Идентифицирован</strong>'),
  (91, 8721, 18, '2011-10-20 00:45:30', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (92, 8721, 18, '2011-10-20 00:45:44', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (93, 8721, 18, '2011-10-20 00:46:01', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (94, 8721, 18, '2011-10-20 00:46:33', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (95, 8721, 18, '2011-10-20 00:47:06', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (96, 8721, 18, '2011-10-20 00:47:08', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (97, 8721, 18, '2011-10-20 01:12:38', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (98, 8721, 18, '2011-10-20 01:13:53', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (99, 8721, 18, '2011-10-20 01:14:13', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (100, 8721, 18, '2011-10-20 01:14:17', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Оценён</strong>'),
  (101, 8721, 18, '2011-10-20 01:14:22', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (102, 8721, 18, '2011-10-20 01:17:47', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (103, 80, 18, '2011-10-20 01:25:22', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (104, 80, 18, '2011-10-20 01:25:41', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Новый</strong>'),
  (105, 8721, 18, '2011-10-20 02:23:05', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (106, 8721, 18, '2011-10-20 02:23:35', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (107, 8721, 18, '2011-10-20 02:24:03', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (108, 8721, 18, '2011-10-20 02:24:45', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (109, 8721, 18, '2011-10-20 02:24:54', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (110, 8721, 18, '2011-10-20 02:25:28', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (111, 8721, 18, '2011-10-20 02:26:02', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (112, 8721, 18, '2011-10-20 02:26:54', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (113, 8721, 18, '2011-10-20 02:27:06', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (114, 8721, 18, '2011-10-20 02:27:11', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (115, 8721, 18, '2011-10-20 02:28:17', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (116, 8721, 18, '2011-10-20 02:28:38', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (117, 8721, 18, '2011-10-20 02:29:53', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (118, 8721, 18, '2011-10-20 02:30:18', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (119, 8721, 18, '2011-10-20 02:30:29', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (120, 8721, 18, '2011-10-20 02:34:32', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (121, 8721, 18, '2011-10-20 02:34:39', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (122, 8721, 18, '2011-10-20 02:35:20', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Новый</strong>'),
  (123, 8721, 18, '2011-10-20 02:35:59', 'Пользователь <strong>18</strong> изменил статус с <strong>Новый</strong> на <strong>Идентифицирован</strong>'),
  (124, 8721, 18, '2011-10-20 02:38:06', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (125, 8721, 18, '2011-10-20 02:38:10', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (126, 8721, 18, '2011-10-20 02:38:15', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (127, 8721, 18, '2011-10-20 02:51:35', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Идентифицирован</strong>'),
  (128, 8721, 18, '2011-10-20 02:52:30', 'Пользователь <strong>18</strong> изменил статус с <strong>Идентифицирован</strong> на <strong>Оценён</strong>'),
  (129, 8721, 18, '2011-10-20 02:53:11', 'Пользователь <strong>18</strong> изменил статус с <strong>Оценён</strong> на <strong>Оценён</strong>'),
  (130, 8721, 18, '2011-10-20 02:53:50', 'Пользователь <strong>18</strong> изменил статус с <strong>Оценён</strong> на <strong>Оценён</strong>');

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
  (31, 13, 21, 'Defect', '1', 'NEW', '2011-09-04 16:52:49', 'апрвапрвапрвапрвпарвапр', '', NULL),
  (42, 15, 24, 'Defect', '1', 'NEW', '2011-09-14 23:35:28', 'Aga', '', 1),
  (43, 1, 23, 'Defect', '2', 'NEW', '2011-09-15 00:41:45', 'fghfghfghghf', 'tgrhgfh', NULL),
  (70, 15, 47, 'Task', '1', 'NEW', '2011-10-03 22:35:06', 'сделать $100000000', 'делать самому', NULL),
  (71, 18, 48, 'Task', '0', 'RESOLVED', '2011-10-06 00:55:55', 'Реализовать выделение текущего раздела меню', 'В настоящий момент меня всегда выделяется на разделе &quot;Мои отчёты&quot;. Нужно, чтобы выделялся на текущем.', 1),
  (72, 1, 48, 'Task', '2', 'RESOLVED', '2011-10-06 02:24:18', 'Реализовать возможность нзначения айтема на пользователя текущего проекта', 'Должно работать поле, которое при выборе проекта обновляется и содержит список всех участников проекта, включая владельца. Логин создающего данный айтем должно находиться на первом месте, также если пустое значение, то айтем не будет назначен кому-либо.', 1),
  (73, 1, 48, 'Task', '1', 'RESOLVED', '2011-10-06 02:29:50', 'Удаление всех выделенных айтемов из грида в Моих отчётах', '1. Переходим на страницу Мои отчёты.\r\n2. Пользователь должен удалять все выделенные айтемы в гриде, нажав чекбокс в вернем углу грида. При нажатии &quot;Удалить выделенные&quot; должно быть показано диалоговое окно подтверждения. \r\n3. Если нажато [Да] - у', 1),
  (76, 1, 48, 'Defect', '0', 'RESOLVED', '2011-10-06 02:52:31', 'В комментариях у грида не подсвечивается текущее поле, по которому сортируем', 'Поле грида, по которому сортируем, должно выделяться в красный цвет.', 1),
  (77, 18, 48, 'Defect', '2', 'RESOLVED', '2011-10-06 03:01:38', 'Если отсутствует скомпилированный шаблон для страниц &quot;Мои отчёты&quot; и &quot;Отчёты проекта&quot;, происходит исключение в Apache', 'Краш отчёт:\r\n\r\nСигнатура проблемы:\r\n  Имя события проблемы:\tAPPCRASH\r\n  Имя приложения:\thttpd.exe\r\n  Версия приложения:\t2.2.4.0\r\n  Отметка времени приложения:\t45a476e3\r\n  Имя модуля с ошибкой:\tphp5ts.dll\r\n  Версия модуля с ошибкой:\t5.2.4.4\r\n  Отметка врем', 1),
  (78, 18, 48, 'Defect', '1', 'NEW', '2011-10-06 03:05:15', 'При создании айтема урезается текст в полях &quot;Описание&quot; и &quot;Действия, которые привели к ошибке&quot;', 'Текст после создания айтема урезается, что неверно. Нужно увеличить до 1024 символов поля &quot;Описание&quot; и &quot;Действия, которые привели к ошибке&quot;.\r\n\r\nНадо пофиксить.', 1),
  (79, 1, 48, 'Task', '2', 'RESOLVED', '2011-10-06 21:56:30', 'Редактирование статуса айтема', 'Сделать возможность редактирования айтема при просмотре. Менять состояние может только владелец айтема, на кого заасайненно и владелец проекта. Закрыть айтем может только владелец айтема и владелец проекта. При отображении состояния показывается текущее и', 1),
  (80, 1, 48, 'Task', '2', 'NEW', '2011-10-06 22:02:55', 'Отображение заявок на проект', 'Сделать отображения всех запросов на вступление в проект, называемые &quot;Заявки&quot;.\r\n\r\nОтображение имени пользователя, даты подачи заявки и кнопок &quot;Подтвердить&quot; и &quot;Отклонить&quot;.\r\n\r\nСделать чекбоксы для грида, которые позволяют подтв', 18),
  (82, 1, 48, 'Task', '2', 'IDENTIFIED', '2011-10-06 22:08:45', 'Поиск проектов', 'Реализовать полнотекстовый поиск по проекту и его описанию. На первом месте должны находиться проекты более совпадающие с поисковым критерием. \r\n\r\nПри удалении проекта - удалять полнотекстовый индекс проекта. Если у проекта пользователь был удалён, то ото', 18),
  (83, 1, 48, 'Task', '1', 'NEW', '2011-10-06 22:14:28', 'В настройках профиля добавить вкладку Дополнительные настройки', 'В данной вкладке добавить выпадающий список, который настраивает проект по-умолчанию. Для это есть специальный метод в классе ConcreteUser (бизнес-логика) ConcreteUser ::setDefaultProject($projectID), ConcreteUser::deleteDefaultProject().\r\n\r\nВ выпадающем ', 18),
  (84, 1, 48, 'Defect', '1', 'RESOLVED', '2011-10-06 22:29:37', 'Обрезается текст и заголовок при создании дефекта', 'При превышении размера в 256 символов - обрезается текст в описании и действиях, которые привели к ошибке. В хранимую процедуру AddItem стоит ограничение на входной текст.', 1),
  (85, 1, 48, 'Task', '0', 'RESOLVED', '2011-10-06 22:33:58', 'Добавить возможность замены переносов на &lt;br/&gt;', 'Текст линейно отображается, было бы неплохо добавлять реальне переносы строки.', 1),
  (87, 1, 48, 'Defect', '1', 'CLOSED', '2011-10-09 04:09:33', 'Перевести меню на компонент', 'Текущая менюшка статическая и не меняет текущий номер', 1),
  (88, 18, 48, 'Task', '1', 'NEW', '2011-10-09 13:45:49', 'Сделать подсветку пунктов', 'При наведении курсора на пункты необходимо сделать подсветку (opacity либо мануальное проставление цвета)', 18),
  (91, 1, 48, 'Defect', '1', 'RESOLVED', '2011-10-09 14:16:06', 'Не могу менять Проекты в выпадающем списке в &quot;Мои Отчёты&quot; и &quot;Отчёты проекта&quot;', 'Если произошёл POST запрос, то ставим тот проект, который был выбран из списка', 1),
  (97, 15, 47, 'Task', '1', 'NEW', '2011-10-10 00:13:47', 'ghdgjhdgfhj', 'ghjfgjfghjfghj', 0),
  (98, 15, 47, 'Task', '1', 'NEW', '2011-10-10 00:14:04', 'ghdgjhdgfhj', 'ghjfgjfghjfghj', 0),
  (99, 15, 47, 'Task', '1', 'NEW', '2011-10-10 00:15:56', 'fghfhgfh', 'ghfghfgj', 0),
  (101, 15, 47, 'Task', '1', 'NEW', '2011-10-10 00:18:15', 'fghfhgfh', 'ghfghfgj', 0),
  (102, 15, 47, 'Task', '1', 'NEW', '2011-10-10 00:23:21', 'Дададад', 'Нетнетнет', 0),
  (103, 15, 47, 'Task', '1', 'NEW', '2011-10-10 00:24:25', 'апрпарпарпар', 'рпопроапроапро', 15),
  (106, 1, 48, 'Defect', '2', 'NEW', '2011-10-10 01:34:01', 'Смена пароля неправильно реализована', 'При смене пароля, даже если не введён старый пароль, изменения сохраняются.<br />\r\nТакже Имя, Фамилия, Отчество, Время входа становятся пустыми.', 18),
  (107, 1, 48, 'Defect', '1', 'RESOLVED', '2011-10-10 23:00:41', 'Ссылки на участников проекта не работают', 'Ссылки на участников проекта не работают.', 1),
  (108, 1, 48, 'Task', '1', 'RESOLVED', '2011-10-10 23:02:45', 'Реализовать&quot;количество комментариев&quot;', 'Необходимо отображать количество комментариев, оставленные пользователем для каждого участника', 1),
  (128, 1, 48, 'Task', '2', 'RESOLVED', '2011-10-12 23:17:39', 'Произвести рефакторинг классов MyBugsPage ProjectBugsPage', 'В данных класса большое количество повторяющихся методов - вынести в базовый класс', 1),
  (129, 1, 48, 'Task', '2', 'RESOLVED', '2011-10-12 23:23:39', 'Реализовать удаление проектов', '1. При удалении проекта должны удаляться:<br />\r\n   1. 1. Все его подписчики<br />\r\n   1. 2. Все его айтемы<br />\r\n   1. 3. Все связанные элементы', 1),
  (136, 1, 22, 'Task', '1', 'NEW', '2011-10-14 01:17:11', '1', 'bgfgfghg', 0),
  (1137, 1, 22, 'Task', '1', 'NEW', '2011-10-14 01:22:15', 'bnmvbnmbnm', 'bnmvbnmvbnmvbnm', 0),
  (8688, 1, 48, 'Task', '2', 'NEW', '2011-10-14 02:46:52', 'Реализовать вывод назначенных айтемов отдельно', 'На данный момент отображаются только созданные пользователем айтемы. Также нужно реализовать айтемы, которые назначены пользователю.', 1),
  (8689, 1, 22, 'Defect', '2', 'NEW', '2011-10-16 22:27:40', 'jhgjghj', 'fghjghj', 1),
  (8690, 1, 23, 'Task', '1', 'IDENTIFIED', '2011-10-16 22:28:47', 'jhgjghj', 'fghjghj', 1),
  (8691, 1, 23, 'Task', '1', 'NEW', '2011-10-17 20:11:48', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 1),
  (8692, 1, 23, 'Task', '1', 'NEW', '2011-10-17 20:12:56', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 1),
  (8693, 1, 23, 'Task', '1', 'NEW', '2011-10-17 23:03:44', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 1),
  (8694, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:21:01', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 1),
  (8695, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:21:29', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 1),
  (8696, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:22:38', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 0),
  (8697, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:23:01', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 0),
  (8698, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:23:32', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 0),
  (8699, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:24:00', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 0),
  (8700, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:24:13', 'fghghfgh', 'dfghdgfjhgjhkjjhk', 0),
  (8701, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:24:35', 'gfhgfh', 'fghfghdfghdh', 0),
  (8702, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:25:53', 'gfhgfh', 'fghfghdfghdh', 0),
  (8703, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:29:02', 'gfhgfh', 'fghfghdfghdh', 0),
  (8704, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:29:33', 'hdfgjghj', 'ghjfghfjghj', 0),
  (8705, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:31:21', 'hdfgjghjghfh', 'ghjfghfjghjfghdfgh', 0),
  (8706, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:32:02', 'hdfgjghjghfh', 'ghjfghfjghjfghdfghhfghgfh', 0),
  (8707, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:32:32', 'hdfgjghjghfh', 'ghjfghfjghjfghdfghhfghgfh', 0),
  (8708, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:32:49', 'fghfghf', 'hfhfg', 0),
  (8709, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:35:19', 'hjmhj,mjh,k', 'njchgn', 0),
  (8710, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:36:09', 'fdghdfghdg', 'fgfhgf', 0),
  (8711, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:38:34', 'fdghdfghdg', 'fgfhgf', 0),
  (8713, 1, 22, 'Task', '1', 'NEW', '2011-10-17 23:42:53', 'fgghfgh', 'gfhgth', 0),
  (8714, 1, 22, 'Task', '1', 'NEW', '2011-10-18 04:23:37', 'прарвпар', 'вапрапрвапр', 15),
  (8715, 1, 22, 'Task', '1', 'NEW', '2011-10-19 00:03:57', 'bncvbn', 'vbncvbnvnb', 7),
  (8716, 15, 22, 'Task', '1', 'NEW', '2011-10-19 00:51:21', 'fghfh', 'fghngfh', 0),
  (8717, 18, 49, 'Task', '1', 'NEW', '2011-10-19 21:14:11', 'првапрапр', 'арпрап', 0),
  (8718, 18, 49, 'Task', '1', 'NEW', '2011-10-19 21:14:22', 'рврпар', 'апрапрвапр', 18),
  (8719, 1, 22, 'Task', '1', 'NEW', '2011-10-19 21:17:36', 'fdgfdhg', 'dfdg', 0),
  (8720, 1, 22, 'Task', '1', 'NEW', '2011-10-19 21:18:13', 'gfhdghdgh', 'fgdfgdfg', 0),
  (8721, 18, 49, 'Defect', '2', 'ASSESSED', '2011-10-19 21:29:47', '85846546973596735849637', 'Блочное кеширование на стороне клиента\r\n\r\nВ последнее время в высоконагруженных сайтах стали все чаще применять технику Partial Caching или блочного кеширования. Достигается это, как правило, за счет применения, казалось бы уже давно забытого, SSI или близких ему технологий (например, ESI). Например, в связках Nginx + Memcached + SSI или Varnish + ESI.\r\n\r\nНедавно и на Хабре тоже появился топик в котором автор описывал данный метод кеширования.\r\n\r\nВ данном топике в 3м варианте решения автор предложил читателям топика привести свои варианты решения относительно данной задачи.\r\n\r\nЭтому, собственно, и посвящается этот топик.\r\n\r\nПостановка задачи\r\n\r\nВ большинстве случаев веб-страница состоит из блоков. Например, для простейшей страницы это блоки: шапка, подвал, правый или левый блок, и блок основного контента. Если сайт более сложный, то и соответственно, таких блоков будет больше, например для хабра это блоки: «последние посты», «последние коментарии», «похожие посты» и т.д. Соответственно возникают проблемы если мы хотим кешировать страницу на уровне представаления, т.е. непосредственно сгенерированный html, т.к. инвалидировать кеш для такой страницы пришлось бы при изменении любого из блоков, размещенного на данной странице.\r\nПоэтому в большинстве случаев применяется кеширование на уровне модели или данных, которые впоследствии заполняют некий шаблон страницы.\r\n\r\nЗдесь-то на помощь и приходит SSI, благодаря данной технологии мы собственно и разбиваем страницу на эти самые логические блоки, и кешируем каждый блок отдельно.\r\n\r\nПример страницы, использующей SSI вставки:\r\n<html>\r\n<body>\r\n\r\n<div class="header">\r\n<!--# include virtual="/header.php" -->\r\n</div>\r\n\r\n<div class="main_content">\r\n<!--# include virtual="/main.php" -->\r\n</div>\r\n\r\n\r\n<!--# include virtual="/footer.php" -->\r\n\r\n</body>\r\n</html>\r\n\r\n\r\n\r\nЗдесь-то, казалось бы и все хорошо, но есть несколько НО, на которых и хотелось бы задержаться.\r\n\r\nПроблемы\r\n\r\nПерсонализированные блоки — это блоки, содержащие персональные данные какого-то пользователя, например, «Привет, %username%!». На самом деле таких данных может быть очень много, взять ту же анкету на вконтакте. Не путайте их с блоками для авторизированных пользователей! Экземпляров вторых у вас в кеше всего два (для залогиненых и нет), для первых представление прийдется хранить в кеше для каждого пользователя! Cохраняя в мемкеше ключи такого вида {%block_id%}_{%PHPSESSID|user_id%}. А так как у нас кеширование на уровне представления, т.е. помимо данных мы храним еще и кучу html кода, который будет повторяться у нас для каждого пользователя, следовательно, расход памяти под кеш (Memcached) в данном случае очень сильно растет. Я уже не говорю про то, что в большой ферме мемкеш серверов, некоторые сервера время от времени отваливаются и даже с алгоритмом Consistent hashing проблемы все равно остаются\r\nНа разогревание кеша (обычно после перезагрузок, релизов новых версий и пр.) уходит очень много времени\r\n\r\n\r\nЧто предлагается?\r\n\r\nА предлагается, собственно следующий механизм кеширования:\r\nБлоки, отвечающие за представление, обобщаем для всех пользователей, т.е. выносим из них все персонифицированные данные, чтобы хранить всего один экземпляр блока в кеше для всех пользователей сайта. Что же остается от этих блоков? Правильно, остаются обычные темплейты представления, которые мы и будем передавать пользователю, а каждый пользователь заполнит данный шаблон сам, на стороне клиента, с помощью, Javascript. Т.е. клиент по запросу к странице получит страницу, состоящую из логических блоков, каждый блок, в свою очередь будет являтся шаблоном. Например\r\n<html>\r\n<body>\r\n    <div id="head_block">\r\n        Some {%personified%} data here\r\n    </div>\r\n    <div id="main_block">\r\n        Hello {%username%}!\r\n    </div>\r\n</body>\r\n</html>\r\n\r\n\r\n\r\nНу или, например, так\r\n<html>\r\n<body>\r\n    <div id="head_block">\r\n        Some <div id="{%personified%}"></div> data here\r\n    </div>\r\n    <div id="main_block">\r\n        Hello <div id="{%username%}"></div>!\r\n    </div>\r\n</body>\r\n</html>\r\n\r\n', 0);

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
  (47, 'проект', 'проекта описание\\r\\n', 15, '2011-10-03 22:33:35'),
  (48, 'Tarakaning', 'Баг-треккер, таскер', 1, '2011-10-05 23:45:25'),
  (49, 'Тестовый проект от имени Тимура', 'Да просто тестовый проект)))', 18, '2011-10-10 02:21:23'),
  (50, 'ммарпрпвапрпа', 'птрьрпар', 18, '2011-10-19 20:51:01');

-- 
-- Вывод данных для таблицы ReportComment
--
INSERT INTO ReportComment VALUES 
  (35, 70, 15, '2011-10-03 22:35:43', ' привет'),
  (36, 70, 15, '2011-10-03 22:35:50', ' сделаем это!'),
  (53, 80, 18, '2011-10-09 03:53:21', ' рпрапр'),
  (54, 80, 18, '2011-10-09 03:53:56', ' Эй, ты почему переоткрыл баг?'),
  (55, 80, 1, '2011-10-09 04:00:05', 'Потому что пивко много не пил!'),
  (56, 80, 18, '2011-10-09 04:00:43', ' Я пил!!!'),
  (57, 80, 1, '2011-10-09 04:00:55', ' Нет, не пил.'),
  (58, 80, 18, '2011-10-09 04:01:07', ' Какашка'),
  (59, 80, 1, '2011-10-09 04:01:24', ' Ага, какундрянский)'),
  (60, 80, 18, '2011-10-09 04:01:43', ' Ладно, лааадно, исправлю я этот баг.'),
  (61, 80, 1, '2011-10-09 04:01:50', ' Да уж постарайся)'),
  (62, 80, 18, '2011-10-09 04:01:56', ' Хорошо.'),
  (63, 80, 1, '2011-10-09 04:02:09', ' Завтра приду - проверю)))'),
  (64, 80, 18, '2011-10-09 04:02:25', ' Ага-ага)'),
  (65, 79, 18, '2011-10-09 04:05:24', ' Работает, но надо проверить при отдельном владельце и руководителе проекта.'),
  (66, 77, 1, '2011-10-10 01:04:58', ' Короче, когда в смарти объявляешь вложенный {block}, то смарти падает. Почитал документацию, сделал правильно.'),
  (67, 82, 18, '2011-10-10 22:54:00', ' Пришло знание о работе индексатора Zend_Lucene\r\nначата работа над созданием класса поисковика-индексатора'),
  (68, 82, 1, '2011-10-10 22:55:59', 'Реализуй два класса: Factory и абстрактный класс для реализации, который принимает фабрику.'),
  (69, 107, 1, '2011-10-10 23:22:44', ' Также заработала ссылка - владелец проекта.'),
  (72, 8690, 1, '2011-10-16 22:45:02', ' fghbfghfghjhgj'),
  (73, 136, 1, '2011-10-18 21:53:56', ' паравпрвапопро');

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
  (74, 'edit', '', '', 10, 0, 72, 1),
  (75, 'ajax', '', '', 11, 0, 67, 0);

-- 
-- Вывод данных для таблицы Users
--
INSERT INTO Users VALUES 
  (1, 'EntityFX', 'Артём', 'Солопий', 'Валерьевич', '408edad392248bc60f0e7ddaed995fe5', 0, 1, 'artem.solopiy@gmail.com', NULL, 48),
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
  (18, 'Timur', 'Тимур', 'Юсупзянов', 'Равхатович', 'a25960829aeaba68de7c7a0a669a5023', 0, 1, 'gtimur7@gmail.com', NULL, 48);

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