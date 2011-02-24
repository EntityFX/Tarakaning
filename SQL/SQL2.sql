-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 4.50.303.1
-- Дата: 25.02.2011 0:42:37
-- Версия сервера: 5.0.45-community-nt
-- Версия клиента: 4.1

USE Tarakaning;

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
  REFERENCES errorreport (ID),
  CONSTRAINT FK_ErorrReportHistory_Users_UserID FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE ErrorReport(
  ID INT(11) NOT NULL AUTO_INCREMENT,
  UserID INT(11) UNSIGNED NOT NULL,
  ProjectID INT(11) NOT NULL,
  PriorityLevel ENUM('0', '1', '2') NOT NULL,
  Status ENUM('NEW', 'ASSIGNED', 'CONFIRMED', 'SOLVED', 'CLOSED') NOT NULL,
  `Time` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
  Title VARCHAR(150) NOT NULL,
  ErrorType ENUM('Crash', 'Cosmetic', 'Error Handling', 'Functional', 'Minor', 'Major', 'Setup', 'Block') NOT NULL,
  Description TEXT NOT NULL,
  StepsText TEXT NOT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ErrorReport_Projects_ProjectID (ProjectID),
  INDEX FK_ErrorReport_Users_UserID (UserID),
  CONSTRAINT FK_ErrorReport_Projects_ProjectID FOREIGN KEY (ProjectID)
  REFERENCES projects (ProjectID),
  CONSTRAINT FK_ErrorReport_Users_UserID FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 2340
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

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
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 15
AVG_ROW_LENGTH = 1170
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE ReportComment(
  ID INT(11) NOT NULL,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `Comment` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportComment_ErrorReport_ID (ReportID),
  INDEX FK_ReportComment_Users_UserID (UserID),
  CONSTRAINT FK_ReportComment_ErrorReport_ID FOREIGN KEY (ReportID)
  REFERENCES errorreport (ID),
  CONSTRAINT FK_ReportComment_Users_UserID FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE ReportsUsersHandling(
  ID INT(11) NOT NULL AUTO_INCREMENT,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportsUsersHandling_Users_UserID (UserID),
  INDEX IX_ReportsUsersHandling_ReportID (ReportID),
  UNIQUE INDEX ReportID (ReportID),
  CONSTRAINT FK_ReportsUsersHandling_ErrorReport_ID FOREIGN KEY (ReportID)
  REFERENCES errorreport (ID),
  CONSTRAINT FK_ReportsUsersHandling_Users_UserID FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

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
  DefaultProjectID INT(11) DEFAULT NULL,
  PRIMARY KEY (UserID),
  UNIQUE INDEX NickName (NickName)
)
ENGINE = INNODB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 5461
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE UsersInProjects(
  RecordID INT(11) NOT NULL AUTO_INCREMENT,
  ProjectID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (RecordID),
  INDEX IX_UsersInProjects_ProjectID (ProjectID),
  UNIQUE INDEX UK_UsersInProjects (UserID, ProjectID),
  CONSTRAINT FK_UsersInProjects_Projects_ProjectID FOREIGN KEY (ProjectID)
  REFERENCES projects (ProjectID),
  CONSTRAINT FK_UsersInProjects_Users_UserID FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 2340
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

DELIMITER $$

CREATE DEFINER = 'root'@'localhost'
PROCEDURE getProjectReportsInfo(IN userID INT)
BEGIN
                      DECLARE done INTEGER DEFAULT 0;
                      DECLARE BankCursor CURSOR FOR
                      SELECT
                        ProjectID,
                        `Status`,
                        COUNT(`Status`)
                      FROM
                        ErrorReport
                      WHERE
                        ProjectID IN
                        (
                        SELECT
                          ProjectID
                        FROM
                          UsersInProjects
                        WHERE
                          UserID = userID
                        UNION
                        SELECT
                          ProjectID
                        FROM
                          Projects P
                        WHERE
                          OwnerID = userID
                        )
                        AND
                        UserID = userID
                      GROUP BY
                        `Status`;
                      DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

                      OPEN BankCursor;
                      CLOSE BankCursor;
                    END
                    $$

DELIMITER ;

CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW totaluserreports
AS
SELECT
  COUNT(`errorreport`.`Status`) AS `Count`,
  `errorreport`.`Status` AS `Status`,
  `errorreport`.`UserID` AS `UserID`
FROM
  `errorreport`
GROUP BY
  `errorreport`.`UserID`,
  `errorreport`.`Status`;