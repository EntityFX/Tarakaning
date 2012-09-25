-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 4.50.303.1
-- Дата: 31.01.2011 13:46:04
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
  INDEX fk_ErorrReportHistory_ErrorReport (ErrorReportID),
  INDEX fk_ErorrReportHistory_Users1 (UserID),
  CONSTRAINT fk_ErorrReportHistory_ErrorReport FOREIGN KEY (ErrorReportID)
  REFERENCES errorreport (ID),
  CONSTRAINT fk_ErorrReportHistory_Users1 FOREIGN KEY (UserID)
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
  `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  Title VARCHAR(150) NOT NULL,
  ErrorType ENUM('Crash', 'Cosmetic', 'Error Handling', 'Functional', 'Minor', 'Major', 'Setup', 'Block') NOT NULL,
  Description TEXT NOT NULL,
  StepsText TEXT NOT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ErrorReport_Projects_ProjectID (ProjectID),
  INDEX FK_ErrorReport_Users_UserID (UserID),
  CONSTRAINT fk_ErrorReport_Projects1 FOREIGN KEY (ProjectID)
  REFERENCES projects (ProjectID),
  CONSTRAINT fk_ErrorReport_Users1 FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE Projects(
  ProjectID INT(11) NOT NULL AUTO_INCREMENT,
  Name VARCHAR(100) NOT NULL,
  Description TINYTEXT DEFAULT NULL,
  OwnerID INT(10) UNSIGNED NOT NULL,
  CreateDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ProjectID),
  INDEX fk_Projects_Users1 (OwnerID),
  UNIQUE INDEX Name (Name),
  CONSTRAINT fk_Projects_Users1 FOREIGN KEY (OwnerID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 2
AVG_ROW_LENGTH = 16384
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE ReportComment(
  ID INT(11) NOT NULL,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED NOT NULL,
  `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `Comment` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportComment_ErrorReport_ID (ReportID),
  INDEX FK_ReportComment_Users_UserID (UserID),
  CONSTRAINT fk_ReportComment_ErrorReport1 FOREIGN KEY (ReportID)
  REFERENCES errorreport (ID),
  CONSTRAINT fk_ReportComment_Users1 FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE ReportsUsersHandling(
  ID INT(11) NOT NULL AUTO_INCREMENT,
  ReportID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (ID),
  INDEX FK_ReportsUsersHandling_ErrorReport_ID (ReportID),
  INDEX FK_ReportsUsersHandling_Users_UserID (UserID),
  CONSTRAINT FK_ReportsUsersHandling_ErrorReport_ID FOREIGN KEY (ReportID)
  REFERENCES errorreport (ID),
  CONSTRAINT FK_ReportsUsersHandling_Users_UserID FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE SubscribesRequest(
  ID INT(11) NOT NULL AUTO_INCREMENT,
  UserID INT(10) UNSIGNED DEFAULT NULL,
  ProjectID INT(11) DEFAULT NULL,
  PRIMARY KEY (ID),
  INDEX fk_SubscribesRequest_Projects1 (ProjectID),
  INDEX fk_SubscribesRequest_Users1 (UserID),
  CONSTRAINT fk_SubscribesRequest_Projects1 FOREIGN KEY (ProjectID)
  REFERENCES projects (ProjectID),
  CONSTRAINT fk_SubscribesRequest_Users1 FOREIGN KEY (UserID)
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
  UserType TINYINT(1) NOT NULL DEFAULT 0,
  Active TINYINT(1) NOT NULL DEFAULT 0,
  Email VARCHAR(255) DEFAULT NULL,
  DefaultProjectID INT(11) DEFAULT NULL,
  PRIMARY KEY (UserID),
  INDEX FK_Users_Projects_ProjectID (DefaultProjectID),
  UNIQUE INDEX NickName (NickName),
  CONSTRAINT FK_Users_Projects_ProjectID FOREIGN KEY (DefaultProjectID)
  REFERENCES projects (ProjectID)
)
ENGINE = INNODB
AUTO_INCREMENT = 9
AVG_ROW_LENGTH = 8192
CHARACTER SET cp1251
COLLATE cp1251_general_ci;

CREATE TABLE UsersInProjects(
  RecordID INT(11) NOT NULL AUTO_INCREMENT,
  ProjectID INT(11) NOT NULL,
  UserID INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (RecordID),
  INDEX FK_UsersInProjects_Users_UserID (UserID),
  INDEX IX_UsersInProjects_ProjectID (ProjectID),
  CONSTRAINT fk_UsersInProjects_Projects1 FOREIGN KEY (ProjectID)
  REFERENCES projects (ProjectID),
  CONSTRAINT fk_UsersInProjects_Users1 FOREIGN KEY (UserID)
  REFERENCES users (UserID)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET cp1251
COLLATE cp1251_general_ci;