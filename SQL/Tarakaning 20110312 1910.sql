-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 4.50.303.1
-- Дата: 12.03.2011 19:12:26
-- Версия сервера: 5.0.45-community-nt
-- Версия клиента: 4.1
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

SET NAMES 'utf8';

USE Tarakaning;

DROP TABLE IF EXISTS ErorrReportHistory;
CREATE TABLE IF NOT EXISTS ErorrReportHistory (
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
    REFERENCES errorreport(ID),
  CONSTRAINT FK_ErorrReportHistory_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DROP TABLE IF EXISTS ErrorReport;
CREATE TABLE IF NOT EXISTS ErrorReport (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  UserID INT(11) UNSIGNED NOT NULL,
  ProjectID INT(11) NOT NULL,
  PriorityLevel ENUM('0','1','2') NOT NULL,
  Status ENUM('NEW','ASSIGNED','CONFIRMED','SOLVED','CLOSED') NOT NULL,
  `Time` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
  Title VARCHAR(150) NOT NULL,
  ErrorType ENUM('Crash','Cosmetic','Error Handling','Functional','Minor','Major','Setup','Block') NOT NULL,
  Description TEXT NOT NULL,
  StepsText TEXT NOT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ErrorReport_Projects_ProjectID (ProjectID),
  INDEX FK_ErrorReport_Users_UserID (UserID),
  CONSTRAINT FK_ErrorReport_Projects_ProjectID FOREIGN KEY (ProjectID)
    REFERENCES projects(ProjectID),
  CONSTRAINT FK_ErrorReport_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 14
AVG_ROW_LENGTH = 1365
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DROP TABLE IF EXISTS Modules;
CREATE TABLE IF NOT EXISTS Modules (
  moduleId INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID модуля',
  name VARCHAR(100) NOT NULL COMMENT 'Заголовок модуля',
  descr TINYTEXT NOT NULL COMMENT 'Описание модуля',
  path VARCHAR(100) NOT NULL COMMENT 'Путь к модулю',
  PRIMARY KEY (moduleId),
  UNIQUE INDEX path (path)
)
ENGINE = MYISAM
AUTO_INCREMENT = 14
AVG_ROW_LENGTH = 26
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица - список модулей';

DROP TABLE IF EXISTS Projects;
CREATE TABLE IF NOT EXISTS Projects (
  ProjectID INT(11) NOT NULL AUTO_INCREMENT,
  Name VARCHAR(100) NOT NULL,
  Description TINYTEXT DEFAULT NULL,
  OwnerID INT(11) UNSIGNED DEFAULT NULL,
  CreateDate DATETIME NOT NULL,
  PRIMARY KEY (ProjectID),
  INDEX fk_Projects_Users1 (OwnerID),
  UNIQUE INDEX Name (Name),
  CONSTRAINT fk_Projects_Users1 FOREIGN KEY (OwnerID)
    REFERENCES users(UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 15
AVG_ROW_LENGTH = 1170
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DROP TABLE IF EXISTS ReportComment;
CREATE TABLE IF NOT EXISTS ReportComment (
  ID INT(11) NOT NULL,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `Comment` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportComment_ErrorReport_ID (ReportID),
  INDEX FK_ReportComment_Users_UserID (UserID),
  CONSTRAINT FK_ReportComment_ErrorReport_ID FOREIGN KEY (ReportID)
    REFERENCES errorreport(ID),
  CONSTRAINT FK_ReportComment_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 16384
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DROP TABLE IF EXISTS ReportsUsersHandling;
CREATE TABLE IF NOT EXISTS ReportsUsersHandling (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportsUsersHandling_Users_UserID (UserID),
  INDEX IX_ReportsUsersHandling_ReportID (ReportID),
  UNIQUE INDEX ReportID (ReportID),
  CONSTRAINT FK_ReportsUsersHandling_ErrorReport_ID FOREIGN KEY (ReportID)
    REFERENCES errorreport(ID),
  CONSTRAINT FK_ReportsUsersHandling_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DROP TABLE IF EXISTS SubscribesRequest;
CREATE TABLE IF NOT EXISTS SubscribesRequest (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  UserID INT(10) UNSIGNED DEFAULT NULL,
  ProjectID INT(11) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX fk_SubscribesRequest_Projects1 (ProjectID),
  INDEX fk_SubscribesRequest_Users1 (UserID),
  UNIQUE INDEX UK_SubscribesRequest (ProjectID, UserID),
  CONSTRAINT fk_SubscribesRequest_Projects1 FOREIGN KEY (ProjectID)
    REFERENCES projects(ProjectID),
  CONSTRAINT fk_SubscribesRequest_Users1 FOREIGN KEY (UserID)
    REFERENCES users(UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 5461
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DROP TABLE IF EXISTS TextModule;
CREATE TABLE IF NOT EXISTS TextModule (
  textID INT(11) NOT NULL,
  `data` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (textID)
)
ENGINE = MYISAM
AVG_ROW_LENGTH = 3636
CHARACTER SET cp1251
COLLATE cp1251_general_ci
ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS URL;
CREATE TABLE IF NOT EXISTS URL (
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
AUTO_INCREMENT = 39
AVG_ROW_LENGTH = 28
CHARACTER SET cp1251
COLLATE cp1251_general_ci
COMMENT = 'Таблица URL адресов и соответствующих модулей';

DROP TABLE IF EXISTS Users;
CREATE TABLE IF NOT EXISTS Users (
  UserID INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  NickName VARCHAR(32) NOT NULL,
  Name VARCHAR(50) DEFAULT NULL,
  Surname VARCHAR(50) DEFAULT NULL,
  SecondName VARCHAR(50) DEFAULT NULL,
  PasswordHash VARCHAR(32) NOT NULL,
  UserType TINYINT(1) NOT NULL,
  Active TINYINT(1) NOT NULL,
  Email VARCHAR(255) DEFAULT NULL,
  DefaultProjectID INT(11) DEFAULT NULL,
  PRIMARY KEY (UserID),
  UNIQUE INDEX NickName (NickName)
)
ENGINE = INNODB
AUTO_INCREMENT = 8
AVG_ROW_LENGTH = 4096
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DROP TABLE IF EXISTS UsersInProjects;
CREATE TABLE IF NOT EXISTS UsersInProjects (
  RecordID INT(11) NOT NULL AUTO_INCREMENT,
  ProjectID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (RecordID),
  INDEX IX_UsersInProjects_ProjectID (ProjectID),
  UNIQUE INDEX UK_UsersInProjects (UserID, ProjectID),
  CONSTRAINT FK_UsersInProjects_Projects_ProjectID FOREIGN KEY (ProjectID)
    REFERENCES projects(ProjectID),
  CONSTRAINT FK_UsersInProjects_Users_UserID FOREIGN KEY (UserID)
    REFERENCES users(UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 13
AVG_ROW_LENGTH = 1820
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DELIMITER $$

DROP PROCEDURE IF EXISTS getMyProjectsInfo$$
CREATE PROCEDURE getMyProjectsInfo(IN userID INT, IN orderField VARCHAR(255))
BEGIN
    SELECT totalprojectsinfo.*,Count(SubscribesRequest.ProjectID) as 'Requests' FROM 
        totalprojectsinfo 
        left JOIN SubscribesRequest
        on totalprojectsinfo.ProjectID=SubscribesRequest.ProjectID
    WHERE OwnerID=userID
        GROUP BY SubscribesRequest.ProjectID
    order by orderField;
END
$$

DROP PROCEDURE IF EXISTS getProjectReportsInfo$$
CREATE PROCEDURE getProjectReportsInfo(IN usrID INT)
BEGIN
  (SELECT
    Projects.ProjectID,
    Projects.`Name`,
    OwnerID,
    Left(Projects.Description,25) as 'Description',
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'NEW') THEN _utf8 'NEW' ELSE NULL END)) AS `New`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'CONFIRMED') THEN _utf8 'CONFIRMED' ELSE NULL END)) AS `Confirmed`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'ASSIGNED') THEN _utf8 'ASSIGNED' ELSE NULL END)) AS `Assigned`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'SOLVED') THEN _utf8 'SOLVED' ELSE NULL END)) AS `Solved`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'CLOSED') THEN _utf8 'CLOSED' ELSE NULL END)) AS `Closed`,  
    Projects.CreateDate
  FROM
    Projects
    LEFT JOIN `errorreport`
        ON `errorreport`.`ProjectID` = `projects`.`ProjectID`
  WHERE
    Projects.OwnerID = usrID
  GROUP BY 
    `projects`.`ProjectID`)
  
  UNION
  
  (SELECT
    Projects.ProjectID,
    Projects.`Name`,
    OwnerID,
    Left(Projects.Description,25) as 'Description',
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'NEW') THEN _utf8 'NEW' ELSE NULL END)) AS `New`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'CONFIRMED') THEN _utf8 'CONFIRMED' ELSE NULL END)) AS `Confirmed`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'ASSIGNED') THEN _utf8 'ASSIGNED' ELSE NULL END)) AS `Assigned`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'SOLVED') THEN _utf8 'SOLVED' ELSE NULL END)) AS `Solved`,
    COUNT((CASE WHEN (`errorreport`.`Status` = _cp1251 'CLOSED') THEN _utf8 'CLOSED' ELSE NULL END)) AS `Closed`, 
    Projects.CreateDate
  FROM
    Projects
    LEFT JOIN `errorreport`
        ON `errorreport`.`ProjectID` = `projects`.`ProjectID`
  WHERE
   Projects.ProjectID IN (SELECT ProjectID From UsersInProjects WHERE UserID=usrID)  GROUP BY 
    `projects`.`ProjectID`);
END
$$

DELIMITER ;

DROP VIEW IF EXISTS projectusersinfo CASCADE;
CREATE OR REPLACE 
VIEW projectusersinfo
AS
	select `usersinprojects`.`ProjectID` AS `ProjectID`,`usersinprojects`.`UserID` AS `UserID`,`users`.`NickName` AS `NickName`,count(`errorreport`.`UserID`) AS `CountReports`,count(`reportcomment`.`UserID`) AS `CountComments`,count((case when (`errorreport`.`Status` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `New`,count((case when (`errorreport`.`Status` = _cp1251'CONFIRMED') then _utf8'CONFIRMED' else NULL end)) AS `Confirmed`,count((case when (`errorreport`.`Status` = _cp1251'ASSIGNED') then _utf8'ASSIGNED' else NULL end)) AS `Assigned`,count((case when (`errorreport`.`Status` = _cp1251'SOLVED') then _utf8'SOLVED' else NULL end)) AS `Solved`,count((case when (`errorreport`.`Status` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `Closed` from (((`usersinprojects` left join `errorreport` on(((`usersinprojects`.`UserID` = `errorreport`.`UserID`) and (`usersinprojects`.`ProjectID` = `errorreport`.`ProjectID`)))) left join `users` on((`usersinprojects`.`UserID` = `users`.`UserID`))) left join `reportcomment` on((`reportcomment`.`ReportID` = `errorreport`.`ID`))) group by `errorreport`.`UserID`,`users`.`UserID`,`reportcomment`.`UserID`,`usersinprojects`.`RecordID`;

DROP VIEW IF EXISTS totalprojectsinfo CASCADE;
CREATE OR REPLACE 
VIEW totalprojectsinfo
AS
	select `projects`.`ProjectID` AS `ProjectID`,`projects`.`Name` AS `Name`,`projects`.`OwnerID` AS `OwnerID`,`users`.`NickName` AS `NickName`,left(`projects`.`Description`,25) AS `Description`,count((case when (`errorreport`.`Status` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `New`,count((case when (`errorreport`.`Status` = _cp1251'CONFIRMED') then _utf8'CONFIRMED' else NULL end)) AS `Confirmed`,count((case when (`errorreport`.`Status` = _cp1251'ASSIGNED') then _utf8'ASSIGNED' else NULL end)) AS `Assigned`,count((case when (`errorreport`.`Status` = _cp1251'SOLVED') then _utf8'SOLVED' else NULL end)) AS `Solved`,count((case when (`errorreport`.`Status` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `Closed`,`projects`.`CreateDate` AS `CreateDate` from ((`projects` left join `errorreport` on((`errorreport`.`ProjectID` = `projects`.`ProjectID`))) left join `users` on((`users`.`UserID` = `projects`.`OwnerID`))) group by `projects`.`ProjectID`;


-- Таблица не содержит данных


INSERT INTO ErrorReport VALUES 
  (2, 3, 2, '1', 'CLOSED', '2011-02-06 18:05:52', 'Возник BSOD', 'Crash', 'При попытке вызвать экран, вышла критическая ошибка', ''),
  (3, 3, 3, '1', 'CONFIRMED', '2011-02-17 19:57:46', '', 'Major', 'dsvfdgfdgdfg', 'sdfasdfadsf'),
  (4, 3, 1, '1', 'SOLVED', '2011-02-17 19:58:23', 'dfdsfd', 'Error Handling', 'dsfsdf', 'dsfasdfasdfsdf'),
  (5, 3, 2, '2', 'SOLVED', '2011-02-17 20:12:23', 'sdfsd', 'Functional', 'sdfsadfasdfsdaf', 'asdfasdfasdfdsfasdfasdfasdf'),
  (6, 3, 7, '1', 'CONFIRMED', '2011-02-24 23:19:28', 'Взрыв', 'Crash', '', ''),
  (7, 3, 7, '2', 'NEW', '2011-02-24 23:20:08', 'Уничтожение', 'Error Handling', '', ''),
  (8, 3, 3, '0', 'SOLVED', '2011-02-24 23:20:24', 'Закрытие', 'Error Handling', '', ''),
  (9, 1, 3, '2', 'ASSIGNED', '2011-02-26 23:31:53', '', 'Crash', '', ''),
  (10, 1, 2, '1', 'CONFIRMED', '2011-02-26 23:40:22', '', 'Crash', '', ''),
  (11, 3, 6, '1', 'NEW', '2011-02-27 03:32:03', 'fsdfsdf', 'Crash', '', ''),
  (12, 1, 6, '2', 'ASSIGNED', '2011-02-27 03:34:34', 'sdfdsf', 'Crash', '', ''),
  (13, 3, 11, '0', 'CONFIRMED', '2011-02-28 00:41:17', 'Искривление зеркала', 'Crash', '', '');


INSERT INTO Modules VALUES 
  (1, 'Текстовый модуль', 'Текстовый модуль', 'Text'),
  (0, 'Модуль ошибок', '', 'Error'),
  (11, 'One', '', 'Zed'),
  (12, 'One', '', 'Zeda'),
  (13, 'One', '', 'O_Zeda');


INSERT INTO Projects VALUES 
  (1, 'Quki', 'вот наш один из долгостроев))', NULL, '0000-00-00 00:00:00'),
  (2, 'Tarakaning', 'Баг-треккер на начальной стадии', 1, '0000-00-00 00:00:00'),
  (3, 'Fuck', NULL, NULL, '0000-00-00 00:00:00'),
  (4, 'Siyfat', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (5, 'Spektr-kzn', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (6, 'IMG', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (7, 'VSOOO', 'dfhdfgh', 3, '0000-00-00 00:00:00'),
  (8, 'Test', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (9, 'Lustres', 'dfhdfgh', NULL, '0000-00-00 00:00:00'),
  (10, 'Basa16', 'fghfghfdgdhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhdrfh5ryrey56uy56g65nb76n76 u 67bthntyhnu657nu76un67un67nu67nun67un6un67un67un67un', NULL, '0000-00-00 00:00:00'),
  (11, 'Nocker', 'fghfghfdgdhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhdrfh5ryrey56uy56g65nb76n76 u 67bthntyhnu657nu76un67un67nu67nun67un6un67un67un67un', 3, '0000-00-00 00:00:00'),
  (12, 'bfgn', 'ndfnfgn', NULL, '0000-00-00 00:00:00'),
  (13, 'bfgnfghh', 'ndfnfgn', NULL, '0000-00-00 00:00:00'),
  (14, 'bfgnfghhfbfghgfh', 'ndfnfgnfghdfgh', NULL, '0000-00-00 00:00:00');


INSERT INTO ReportComment VALUES 
  (0, 9, 1, '2011-02-27 03:21:12', NULL);


-- Таблица не содержит данных


INSERT INTO SubscribesRequest VALUES 
  (1, 6, 7),
  (5, 7, 7),
  (6, 1, 11);


INSERT INTO TextModule VALUES 
  (19, '<DIV\r\nCLASS="NAVHEADER"\r\n><TABLE\r\nSUMMARY="Header navigation table"\r\nWIDTH="100%"\r\nBORDER="0"\r\nCELLPADDING="0"\r\nCELLSPACING="0"\r\n><TR\r\n><TH\r\nCOLSPAN="3"\r\nALIGN="center"\r\n>PHP Manual</TH\r\n></TR\r\n><TR\r\n><TD\r\nWIDTH="10%"\r\nALIGN="left"\r\nVALIGN="bottom"\r\n><A\r\nHREF="about.howtohelp.html"\r\nACCESSKEY="P"\r\n>Prev</A\r\n></TD\r\n><TD\r\nWIDTH="80%"\r\nALIGN="center"\r\nVALIGN="bottom"\r\n>Appendix R. About the manual</TD\r\n><TD\r\nWIDTH="10%"\r\nALIGN="right"\r\nVALIGN="bottom"\r\n><A\r\nHREF="about.translations.html"\r\nACCESSKEY="N"\r\n>Next</A\r\n></TD\r\n></TR\r\n></TABLE\r\n><HR\r\nALIGN="LEFT"\r\nWIDTH="100%"></DIV\r\n><DIV\r\nCLASS="sect1"\r\n><H1\r\nCLASS="sect1"\r\n><A\r\nNAME="about.generate"\r\n>How we generate the formats</A\r\n></H1\r\n><P\r\n>&#13;   This manual is written in <ACRONYM\r\nCLASS="acronym"\r\n>XML</ACRONYM\r\n> using the <A\r\nHREF="http://www.oasis-open.org/docbook/xml/"\r\nTARGET="_top"\r\n>DocBook XML DTD</A\r\n>, using <A\r\nHREF="http://www.jclark.com/dsssl/"\r\nTARGET="_top"\r\n><ACRONYM\r\nCLASS="acronym"\r\n>DSSSL</ACRONYM\r\n></A\r\n> (Document\r\n   Style and Semantics Specification Language) for formatting, and\r\n   experimentally the <A\r\nHREF="http://www.w3.org/TR/xslt"\r\nTARGET="_top"\r\n><ACRONYM\r\nCLASS="acronym"\r\n>XSLT</ACRONYM\r\n></A\r\n> \r\n   (Extensible Stylesheet Language Transformations)\r\n   for maintenance and formatting.\r\n  </P\r\n><P\r\n>&#13;   Using <ACRONYM\r\nCLASS="acronym"\r\n>XML</ACRONYM\r\n> as a source format gives \r\n   the ability to generate many output formats from the source\r\n   files, while only maintaining one source document for all formats.\r\n   The tools used for formatting <ACRONYM\r\nCLASS="acronym"\r\n>HTML</ACRONYM\r\n> and\r\n   <ACRONYM\r\nCLASS="acronym"\r\n>TeX</ACRONYM\r\n> versions are\r\n   <A\r\nHREF="http://www.jclark.com/jade/"\r\nTARGET="_top"\r\n>Jade</A\r\n>, written by <A\r\nHREF="http://www.jclark.com/bio.htm"\r\nTARGET="_top"\r\n>James Clark</A\r\n>; and <A\r\nHREF="http://docbook.sourceforge.net/projects/dsssl/"\r\nTARGET="_top"\r\n>The Modular DocBook Stylesheets</A\r\n>,\r\n   written by <A\r\nHREF="http://nwalsh.com/"\r\nTARGET="_top"\r\n>Norman Walsh</A\r\n>.\r\n   We use <A\r\nHREF="http://msdn.microsoft.com/library/en-us/htmlhelp/html/vsconhh1start.asp"\r\nTARGET="_top"\r\n>Microsoft HTML Help\r\n   Workshop</A\r\n> to generate the Windows HTML Help format\r\n   of the manual, and of course PHP itself to do some\r\n   additional conversions and formatting.\r\n  </P\r\n><P\r\n>&#13;   The PHP manual is generated in various languages and formats, see \r\n   <A\r\nHREF="http://www.php.net/docs.php"\r\nTARGET="_top"\r\n>http://www.php.net/docs.php</A\r\n> for additional details.\r\n   The <ACRONYM\r\nCLASS="acronym"\r\n>XML</ACRONYM\r\n> source code may be downloaded from CVS and\r\n   viewed at <A\r\nHREF="http://cvs.php.net/"\r\nTARGET="_top"\r\n>http://cvs.php.net/</A\r\n>. The\r\n   documentation is stored in the <VAR\r\nCLASS="literal"\r\n>phpdoc</VAR\r\n> module.\r\n  </P\r\n></DIV\r\n><DIV\r\nCLASS="NAVFOOTER"\r\n><HR\r\nALIGN="LEFT"\r\nWIDTH="100%"><TABLE\r\nSUMMARY="Footer navigation table"\r\nWIDTH="100%"\r\nBORDER="0"\r\nCELLPADDING="0"\r\nCELLSPACING="0"\r\n><TR\r\n><TD\r\nWIDTH="33%"\r\nALIGN="left"\r\nVALIGN="top"\r\n><A\r\nHREF="about.howtohelp.html"\r\nACCESSKEY="P"\r\n>Prev</A\r\n></TD\r\n><TD\r\nWIDTH="34%"\r\nALIGN="center"\r\nVALIGN="top"\r\n><A\r\nHREF="index.html"\r\nACCESSKEY="H"\r\n>Home</A\r\n></TD\r\n><TD\r\nWIDTH="33%"\r\nALIGN="right"\r\nVALIGN="top"\r\n><A\r\nHREF="about.translations.html"\r\nACCESSKEY="N"\r\n>Next</A\r\n></TD\r\n></TR\r\n><TR\r\n><TD\r\nWIDTH="33%"\r\nALIGN="left"\r\nVALIGN="top"\r\n>How to help improve the documentation</TD\r\n><TD\r\nWIDTH="34%"\r\nALIGN="center"\r\nVALIGN="top"\r\n><A\r\nHREF="about.html"\r\nACCESSKEY="U"\r\n>Up</A\r\n></TD\r\n><TD\r\nWIDTH="33%"\r\nALIGN="right"\r\nVALIGN="top"\r\n>Translations</TD\r\n></TR\r\n></TABLE\r\n></DIV\r\n>');


INSERT INTO URL VALUES 
  (19, '/', 'root', '', 1, 0, 0, 0),
  (20, 'uno', 'weferf', 'eferferf', 1, 0, 19, 0),
  (26, 'hi', 'dfgfd', '', 1, 0, 19, 0),
  (23, 'buro', 'sdfvdgd', 'fdgdfg', 1, 0, 20, 0),
  (28, 'hi', 'fdsfsd', '', 1, 0, 20, 0),
  (29, 'hi', '', '', 1, 0, 26, 0),
  (30, 'hi', '', '', 1, 0, 23, 0),
  (31, 'hi', '', '', 1, 0, 29, 0),
  (32, 'hi', '', '', 1, 0, 30, 0),
  (33, 'hi', '', '', 1, 0, 32, 0),
  (34, 'hi', '', '', 1, 0, 33, 0),
  (35, 'hi', '', '', 10, 0, 34, 0),
  (36, 'hi', '', '', 10, 0, 35, 0),
  (37, 'hi', '', '', 11, 0, 36, 0),
  (38, 'gallary', 'Галерея', 'Наша галерея', 1, 0, 19, 1);


INSERT INTO Users VALUES 
  (1, 'EntityFX', 'Артём', 'Солопий', 'Валерьевич', '408edad392248bc60f0e7ddaed995fe5', 0, 1, 'tym_@mail.ru', 2),
  (3, 'Vasiliy', 'Артём', 'Солопий', 'Валерьевич', 'f188f8028be984727e58c6aed3cbe2d3', 0, 1, 'tym_@mail.ru', 7),
  (6, 'Oliya', NULL, NULL, NULL, '', 0, 0, NULL, NULL),
  (7, 'Marat', 'Марат', 'Ахметов', 'Альбертович', '408edad392248bc60f0e7ddaed995fe5', 0, 1, NULL, NULL);


INSERT INTO UsersInProjects VALUES 
  (2, 1, 1),
  (4, 3, 1),
  (12, 6, 1),
  (6, 1, 3),
  (1, 2, 3),
  (10, 3, 3),
  (7, 5, 3),
  (8, 6, 3),
  (11, 3, 6);
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;