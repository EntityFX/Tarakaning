-- phpMyAdmin SQL Dump
-- version 3.3.5
-- http://www.phpmyadmin.net
--
-- ����: sql-4.ayola.net
-- ����� ��������: ��� 01 2012 �., 21:59
-- ������ �������: 5.1.60
-- ������ PHP: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- ���� ������: `entityfx616`
--
CREATE DATABASE IF NOT EXISTS Tarakaning DEFAULT CHARACTER SET cp1251 COLLATE cp1251_general_ci;
USE Tarakaning;

-- --------------------------------------------------------

--
-- ��������� ������� `ITEM`
--

CREATE TABLE IF NOT EXISTS `ITEM` (
  `ITEM_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) unsigned DEFAULT NULL,
  `PROJ_ID` int(11) NOT NULL,
  `KIND` enum('Defect','Task') NOT NULL,
  `PRTY_LVL` enum('0','1','2') NOT NULL,
  `STAT` enum('NEW','ASSESSED','IDENTIFIED','RESOLVED','CLOSED') NOT NULL,
  `CRT_TM` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `TITLE` varchar(150) NOT NULL,
  `DESCR` text NOT NULL,
  `ASSGN_TO` int(11) DEFAULT NULL,
  PRIMARY KEY (`ITEM_ID`),
  KEY `IX__ITEM__PROJ_ID` (`PROJ_ID`),
  KEY `IX__ITEM__USER_ID` (`USER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=29 ;

--
-- ���� ������ ������� `ITEM`
--

INSERT INTO `ITEM` (`ITEM_ID`, `USER_ID`, `PROJ_ID`, `KIND`, `PRTY_LVL`, `STAT`, `CRT_TM`, `TITLE`, `DESCR`, `ASSGN_TO`) VALUES
(2, 1, 1, 'Defect', '1', 'NEW', '2012-01-16 19:43:49', 'ghfhdfgh', 'dfghdfhgfh', 1),
(3, 22, 3, 'Task', '1', 'NEW', '2012-01-17 00:02:55', '�������...', '��������� ������ tarakaining. ����� ������ )', 22),
(4, 22, 3, 'Task', '1', 'IDENTIFIED', '2012-01-18 18:39:30', '����������', '�������� �� ����� ', 0),
(5, 1, 1, 'Task', '1', 'NEW', '2012-01-18 18:48:43', '==', '==', 0),
(6, 1, 4, 'Task', '2', 'RESOLVED', '2012-02-11 00:51:51', '�������� ��', '* �������� ��������� ��\r\n* ������������ ������\r\n* �������� ������', 1),
(7, 1, 4, 'Task', '2', 'ASSESSED', '2012-02-11 00:53:16', '����������� �������', '', 0),
(8, 24, 4, 'Task', '1', 'IDENTIFIED', '2012-02-11 01:04:38', '������ ������-������', '������� ����� ������-�����', 20),
(9, 24, 4, 'Task', '1', 'CLOSED', '2012-02-11 23:10:47', '������ Yii �� �����������', '������ �� ����������� �������� ���������. ', 24),
(10, 24, 5, 'Defect', '1', 'NEW', '2012-02-11 23:14:50', '������ ����� �������', '��� ������:\r\n\r\nHOST: sql-4.ayola.net\r\nDATABASE: entityfx616\r\nDB Type: MySQL\r\nBAD QUERY: UPDATE `ITEM` SET `Status`=''IDENTIFIED'' WHERE ITEM_ID=9\r\nMySQL message: Unknown column ''Status'' in ''field list''', 0),
(11, 24, 4, 'Task', '1', 'NEW', '2012-02-11 23:21:46', '�������� ������ �������� �����������', '������� ����� �������� �����������. ������ ���� �������� ��������� ��������.', 24),
(12, 24, 4, 'Task', '1', 'ASSESSED', '2012-02-12 00:27:06', '����� �����', '�������� ��� ���� �����', 1),
(13, 1, 4, 'Task', '2', 'ASSESSED', '2012-02-13 21:36:18', '�������� ������������ �����', '* ������� ������ ������������\r\n* �������� ������� ������������\r\n����� 2 ������:\r\n1. ����\r\n2. �����������������', 0),
(16, 1, 4, 'Task', '1', 'NEW', '2012-02-14 02:03:03', '�������� ��������� �������', '* ������� �������� ���� ������� � ��������� Balzamiq Mockups\r\n\r\n������ �� ���������:\r\nhttp://www.balsamiq.com/products/mockups', 0),
(17, 1, 4, 'Task', '1', 'NEW', '2012-02-14 02:03:40', '¸����� �������', '��������� ������ ���� �������', 20),
(18, 1, 4, 'Task', '2', 'ASSESSED', '2012-02-15 22:43:48', '�������� ������ ����� ', '* ������� ������ �� �������� ��:\r\n* User\r\n* City\r\n* CompanyLogical\r\n* CompanyPhysical\r\n* CompanyPhone\r\n* CompanyType\r\n* Tag\r\n* CompanyTagXRef\r\n* Country\r\n* Language\r\n* Region\r\n* Message\r\n* VoteType', 24),
(19, 1, 4, 'Task', '2', 'RESOLVED', '2012-02-18 22:38:52', '������� ������������ ������ � ��������', '* ����� ���� ������ � ��������\r\n* ������� ������������ ������ � ��������� ��������\r\n* �������� ������ �� �����������\r\n\r\n(��������� �����: 2 ����)', 1),
(20, 1, 4, 'Task', '2', 'ASSESSED', '2012-02-21 19:30:23', '����������� �����������', '* ������� ������ �����������\r\n* ��������� ������ � ������� � �� (User)\r\n* ����� � ������ � CAPTCHA\r\n* ����������� ��� �����������\r\n* �������������', 0),
(23, 1, 4, 'Task', '2', 'NEW', '2012-02-23 15:23:13', '������-������ ���������� ��������', '* ���������� ���������� ��������\r\n* ��������� ����������\r\n* �������� ���������� ��������\r\n* ������� ���������', 0),
(24, 1, 4, 'Task', '1', 'RESOLVED', '2012-03-03 16:35:01', '������������ �� �������', '* ���������� ������ ������\r\n* �����������������', 1),
(25, 1, 4, 'Task', '1', 'ASSESSED', '2012-03-03 22:22:20', '�������� �������� ������������', '* ����������� ���������� ������������:\r\n* ��������������\r\n\r\n������� ������������:\r\n1. �����/�����\r\n2. ���\r\n����� ������ -??\r\n3. ������\r\n4. ���\r\n5. ���� ��������', 1),
(26, 1, 4, 'Task', '2', 'ASSESSED', '2012-03-13 00:14:16', '����������� autocomplete ������� ', '������� �������� �������� ������ ������.\r\n\r\n�������� ������ ������� ������� �� ������� � ����������� �� ���������� �������� � ������ ��-��������.\r\n�����: 25 ��������� �������.\r\n�������� � �������� � ����� ���������� ������\r\n', 24),
(27, 1, 4, 'Defect', '2', 'RESOLVED', '2012-03-13 00:32:38', '������ ��� �����������', '�� ����� ������������������ ������������.\r\n\r\n������ � �����:\r\n2012/03/13 01:14:20 [error] [system.db.CDbCommand] CDbCommand::execute() failed: SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`m1`.`user`, CONSTRAINT `FK__user__language__language_code_id` FOREIGN KEY (`language_code_id`) REFERENCES `language` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION). The SQL statement executed was: INSERT INTO `user` (`user_type`, `active`, `email`, `create_datetime`, `language_code_id`, `password_hash`, `city_id`, `country_id`, `gender`, `burthday`) VALUES (:yp0, :yp1, :yp2, :yp3, :yp4, :yp5, :yp6, :yp7, :yp8, :yp9).\r\nin Z:\\home\\m1.ru\\www\\protected\\models\\repositories\\UserRepository.php (9)\r\nin Z:\\home\\m1.ru\\www\\protected\\controllers\\UserController.php (175)\r\nin Z:\\home\\m1.ru\\www\\index.php (14)\r\n2012/03/13 01:14:21 [error] [exception.CHttpException.404] exception ''CHttpException'' with message ''Unable to resolve the request "favicon.ico".'' in Z:\\home\\m1.ru\\framework\\web\\CWebApplication.php:280\r\nStack trace:\r\n#0 Z:\\home\\m1.ru\\framework\\web\\CWebApplication.php(135): CWebApplication->runController(''favicon.ico'')\r\n#1 Z:\\home\\m1.ru\\framework\\base\\CApplication.php(162): CWebApplication->processRequest()\r\n#2 Z:\\home\\m1.ru\\www\\index.php(14): CApplication->run()\r\n#3 {main}\r\n\r\n�������:\r\n����������� ������ ����� � ��.\r\n���� ��-���������: 1.', 1),
(28, 1, 7, 'Task', '0', 'IDENTIFIED', '2012-06-22 12:54:17', '�������', '�� ��������� ��� ������.', 1);

-- --------------------------------------------------------

--
-- ��������� ������� `ITEM_CMMENT`
--

CREATE TABLE IF NOT EXISTS `ITEM_CMMENT` (
  `ITEM_CMMENT_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ITEM_ID` int(11) NOT NULL,
  `USER_ID` int(11) unsigned DEFAULT NULL,
  `CRT_TM` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CMMENT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ITEM_CMMENT_ID`),
  KEY `IX__ITEM_CMMENT__ITEM_ID` (`ITEM_ID`),
  KEY `IX__ITEM_CMMENT__USER_ID` (`USER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=16 ;

--
-- ���� ������ ������� `ITEM_CMMENT`
--

INSERT INTO `ITEM_CMMENT` (`ITEM_CMMENT_ID`, `ITEM_ID`, `USER_ID`, `CRT_TM`, `CMMENT`) VALUES
(2, 2, 1, '0000-00-00 00:00:00', ' rgtryuiofghyjthgfghj'),
(3, 3, 22, '0000-00-00 00:00:00', '������ ���!\\r\\n'),
(4, 3, 22, '0000-00-00 00:00:00', ' �����'),
(5, 3, 22, '0000-00-00 00:00:00', ' ���\\r\\n����\\r\\n����\\r\\n'),
(6, 8, 24, '0000-00-00 00:00:00', '������'),
(7, 8, 20, '0000-00-00 00:00:00', ' ���-��-��)'),
(11, 6, 1, '2012-02-16 23:56:33', ' ��������� ��. ��������� ������� &quot;company_physical_tag_xref&quot;'),
(15, 8, 1, '2012-02-17 00:40:30', ' �����');

-- --------------------------------------------------------

--
-- ��������� ������� `ITEM_DEFECT`
--

CREATE TABLE IF NOT EXISTS `ITEM_DEFECT` (
  `ITEM_DEFECT_ID` int(11) NOT NULL,
  `DEFECT_TYP` enum('Crash','Cosmetic','Error Handling','Functional','Minor','Major','Setup','Block') NOT NULL,
  `STP_TXT` text NOT NULL,
  PRIMARY KEY (`ITEM_DEFECT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `ITEM_DEFECT`
--

INSERT INTO `ITEM_DEFECT` (`ITEM_DEFECT_ID`, `DEFECT_TYP`, `STP_TXT`) VALUES
(2, 'Major', 'dfghdfgh'),
(10, 'Block', '�������� �� �������� http://entityfx.xe0.ru/bug/show/9/    ��� ����� ������� ������ �� ���������������'),
(27, 'Major', '1. �������� ��� ���������������\r\n2. ����� �� ���������� ������');

-- --------------------------------------------------------

--
-- ��������� ������� `ITEM_HIST`
--

CREATE TABLE IF NOT EXISTS `ITEM_HIST` (
  `ITEM_HIST_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ITEM_ID` int(11) NOT NULL,
  `USER_ID` int(11) unsigned DEFAULT NULL,
  `OLD_TM` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DESCR` text,
  PRIMARY KEY (`ITEM_HIST_ID`),
  KEY `IX__ITEM_HIST__ITEM_ID` (`ITEM_ID`),
  KEY `IX__ITEM_HIST__USER_ID` (`USER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=82 ;

--
-- ���� ������ ������� `ITEM_HIST`
--

INSERT INTO `ITEM_HIST` (`ITEM_HIST_ID`, `ITEM_ID`, `USER_ID`, `OLD_TM`, `DESCR`) VALUES
(2, 2, 1, '2012-01-16 19:43:50', '������ ���������'),
(3, 3, 22, '2012-01-17 00:02:55', '������ ���������'),
(4, 4, 22, '2012-01-18 18:39:30', '������ ���������'),
(5, 5, 1, '2012-01-18 18:48:43', '������ ���������'),
(6, 3, 22, '2012-01-19 00:45:24', '������������ <strong>22</strong> ������� ������ � <strong>�����</strong> �� <strong>�����</strong>'),
(7, 4, 22, '2012-01-19 18:28:58', '������������ <strong>22</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(8, 6, 1, '2012-02-11 00:51:51', '������ ���������'),
(9, 7, 1, '2012-02-11 00:53:16', '������ ���������'),
(10, 8, 24, '2012-02-11 01:04:38', '������ ���������'),
(11, 9, 24, '2012-02-11 23:10:47', '������ ���������'),
(12, 10, 24, '2012-02-11 23:14:50', '������ ���������'),
(13, 6, 1, '2012-02-11 23:21:19', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(14, 11, 24, '2012-02-11 23:21:46', '������ ���������'),
(15, 6, 1, '2012-02-11 23:23:10', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(16, 6, 1, '2012-02-11 23:23:18', '������������ <strong>1</strong> ������� ������ � <strong>� ��������</strong> �� <strong>�����</strong>'),
(17, 6, 1, '2012-02-11 23:23:29', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>�����</strong>'),
(18, 12, 24, '2012-02-12 00:27:06', '������ ���������'),
(19, 8, 24, '2012-02-12 16:16:30', '������������ <strong>24</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(20, 6, 1, '2012-02-12 16:36:20', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(21, 6, 1, '2012-02-12 16:36:24', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(22, 9, 24, '2012-02-13 21:32:39', '������������ <strong>24</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(23, 9, 24, '2012-02-13 21:32:45', '������������ <strong>24</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(24, 9, 24, '2012-02-13 21:32:50', '������������ <strong>24</strong> ������� ������ � <strong>� ��������</strong> �� <strong>�����</strong>'),
(25, 12, 24, '2012-02-13 21:33:51', '������������ <strong>24</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(26, 12, 24, '2012-02-13 21:33:57', '������������ <strong>24</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(27, 7, 1, '2012-02-13 21:35:11', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(28, 7, 1, '2012-02-13 21:35:20', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(29, 13, 1, '2012-02-13 21:36:18', '������ ���������'),
(30, 13, 1, '2012-02-13 21:36:53', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>�����</strong>'),
(31, 13, 1, '2012-02-13 21:37:02', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>�����</strong>'),
(33, 8, 24, '2012-02-14 01:50:54', '������������ <strong>24</strong> ������� ������ � <strong>���������������</strong> �� <strong>���������������</strong>'),
(34, 8, 24, '2012-02-14 01:51:04', '������������ <strong>24</strong> ������� ������ � <strong>���������������</strong> �� <strong>���������������</strong>'),
(36, 16, 1, '2012-02-14 02:03:03', '������ ���������'),
(37, 17, 1, '2012-02-14 02:03:40', '������ ���������'),
(38, 8, 1, '2012-02-14 02:12:51', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>���������������</strong>'),
(39, 6, 1, '2012-02-14 23:42:44', '������������ <strong>1</strong> ������� ������ � <strong>� ��������</strong> �� <strong>�����</strong>'),
(40, 8, 1, '2012-02-14 23:43:23', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>���������������</strong>'),
(41, 18, 1, '2012-02-15 22:43:48', '������ ���������'),
(42, 18, 1, '2012-02-15 22:43:54', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(43, 18, 1, '2012-02-15 22:43:58', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(44, 18, 1, '2012-02-15 22:49:31', '������������ <strong>1</strong> ������� ������ � <strong>� ��������</strong> �� <strong>� ��������</strong>'),
(45, 13, 1, '2012-02-15 22:49:51', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(46, 13, 1, '2012-02-15 22:49:56', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(47, 9, 1, '2012-02-17 00:48:24', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>������</strong>'),
(48, 19, 1, '2012-02-18 22:38:52', '������ ���������'),
(49, 19, 1, '2012-02-18 22:39:50', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(50, 19, 1, '2012-02-18 23:29:31', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(51, 20, 1, '2012-02-21 19:30:23', '������ ���������'),
(52, 20, 1, '2012-02-21 19:31:19', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>�����</strong>'),
(59, 19, 1, '2012-02-23 00:29:20', '������������ <strong>1</strong> ������� ������ � <strong>� ��������</strong> �� <strong>�����</strong>'),
(60, 23, 1, '2012-02-23 15:23:14', '������ ���������'),
(61, 20, 1, '2012-02-27 23:19:37', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(62, 20, 1, '2012-02-29 20:22:23', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(63, 24, 1, '2012-03-03 16:35:01', '������ ���������'),
(64, 24, 1, '2012-03-03 16:43:42', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(65, 24, 1, '2012-03-03 20:27:06', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(66, 24, 1, '2012-03-03 20:27:10', '������������ <strong>1</strong> ������� ������ � <strong>� ��������</strong> �� <strong>�����</strong>'),
(67, 25, 1, '2012-03-03 22:22:20', '������ ���������'),
(68, 25, 1, '2012-03-03 22:23:51', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(69, 25, 1, '2012-03-04 20:25:33', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(70, 16, 1, '2012-03-05 22:19:01', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>�����</strong>'),
(71, 26, 1, '2012-03-13 00:14:16', '������ ���������'),
(72, 12, 1, '2012-03-13 00:15:05', '������������ <strong>1</strong> ������� ������ � <strong>� ��������</strong> �� <strong>� ��������</strong>'),
(73, 26, 1, '2012-03-13 00:15:37', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(74, 26, 1, '2012-03-13 00:15:41', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(75, 27, 1, '2012-03-13 00:32:38', '������ ���������'),
(76, 27, 1, '2012-03-13 00:33:31', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>'),
(77, 27, 1, '2012-03-13 00:35:38', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>���������������</strong>'),
(78, 27, 1, '2012-03-13 00:58:02', '������������ <strong>1</strong> ������� ������ � <strong>���������������</strong> �� <strong>� ��������</strong>'),
(79, 27, 1, '2012-03-13 00:58:08', '������������ <strong>1</strong> ������� ������ � <strong>� ��������</strong> �� <strong>�����</strong>'),
(80, 28, 1, '2012-06-22 12:54:17', '������ ���������'),
(81, 28, 1, '2012-06-22 12:54:25', '������������ <strong>1</strong> ������� ������ � <strong>�����</strong> �� <strong>���������������</strong>');

-- --------------------------------------------------------

--
-- ��������� ������� `Modules`
--

CREATE TABLE IF NOT EXISTS `Modules` (
  `moduleId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID ������',
  `name` varchar(100) NOT NULL COMMENT '��������� ������',
  `descr` tinytext NOT NULL COMMENT '�������� ������',
  `path` varchar(100) NOT NULL COMMENT '���� � ������',
  PRIMARY KEY (`moduleId`),
  UNIQUE KEY `path` (`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=36 COMMENT='������� - ������ �������' AUTO_INCREMENT=19 ;

--
-- ���� ������ ������� `Modules`
--

INSERT INTO `Modules` (`moduleId`, `name`, `descr`, `path`) VALUES
(1, '��������� ������', '��������� ������', 'Text'),
(2, '������ ������', '', 'Error'),
(6, 'Auth', '������ ��������������', 'Auth'),
(10, '', '', 'Profile'),
(11, 'Tarakaning', '', 'Tarakaning'),
(18, 'TestModule', '�������� ������', 'TestModule');

-- --------------------------------------------------------

--
-- ��������� ������� `PROJ`
--

CREATE TABLE IF NOT EXISTS `PROJ` (
  `PROJ_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROJ_NM` varchar(100) NOT NULL,
  `DESCR` tinytext,
  `USER_ID` int(11) unsigned DEFAULT NULL,
  `CRT_TM` datetime NOT NULL,
  PRIMARY KEY (`PROJ_ID`),
  KEY `IX__PROJ__USER_ID` (`USER_ID`),
  KEY `IX__PROJ__PROJ_NM` (`PROJ_NM`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=8 ;

--
-- ���� ������ ������� `PROJ`
--

INSERT INTO `PROJ` (`PROJ_ID`, `PROJ_NM`, `DESCR`, `USER_ID`, `CRT_TM`) VALUES
(1, '123', '1', 1, '2012-01-16 00:58:05'),
(3, 'test', '����', 22, '2012-01-16 23:59:40'),
(4, 'M1', 'Catalogue, company', 1, '2012-02-11 00:50:02'),
(5, '����������', '���������', 24, '2012-02-11 23:12:13'),
(6, 'Tarakaning', '������ ����� ����������� ���� ������ ����.', 1, '2012-03-29 13:45:51'),
(7, 'FotoGrad.org', '������ ��������� ����� ����', 1, '2012-06-22 12:52:03');

-- --------------------------------------------------------

--
-- ��������� ������� `SUBSCR_RQST`
--

CREATE TABLE IF NOT EXISTS `SUBSCR_RQST` (
  `SUBSCR_RQST_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) unsigned DEFAULT NULL,
  `PROJ_ID` int(11) NOT NULL,
  `RQST_TM` datetime NOT NULL,
  PRIMARY KEY (`SUBSCR_RQST_ID`),
  UNIQUE KEY `IX__SUBSCR_RQST__PROJ_ID__USER_ID` (`PROJ_ID`,`USER_ID`),
  KEY `IX__SUBSCR_RQST__PROJ_ID` (`PROJ_ID`),
  KEY `IX__SUBSCR_RQST__USER_ID` (`USER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

--
-- ���� ������ ������� `SUBSCR_RQST`
--

INSERT INTO `SUBSCR_RQST` (`SUBSCR_RQST_ID`, `USER_ID`, `PROJ_ID`, `RQST_TM`) VALUES
(1, 1, 3, '2012-01-19 18:31:06'),
(2, 24, 4, '2012-02-11 01:01:17');

-- --------------------------------------------------------

--
-- ��������� ������� `URL`
--

CREATE TABLE IF NOT EXISTS `URL` (
  `id` int(11) NOT NULL COMMENT 'Id �������',
  `link` varchar(255) NOT NULL DEFAULT '/' COMMENT '����� �������',
  `title` varchar(100) NOT NULL COMMENT '���������',
  `title_tag` varchar(255) NOT NULL COMMENT '��� ���� title',
  `module` int(11) NOT NULL DEFAULT '1' COMMENT '��� ������',
  `position` int(11) NOT NULL COMMENT '������� � �������',
  `pid` int(11) NOT NULL DEFAULT '1' COMMENT 'ID ������������� �������',
  `use_parameters` tinyint(4) NOT NULL COMMENT '������������ ���������',
  PRIMARY KEY (`id`),
  KEY `IX_URL_module` (`module`),
  KEY `IX_URL_pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AVG_ROW_LENGTH=28 COMMENT='������� URL ������� � ��������������� �������';

--
-- ���� ������ ������� `URL`
--

INSERT INTO `URL` (`id`, `link`, `title`, `title_tag`, `module`, `position`, `pid`, `use_parameters`) VALUES
(1, '/', '', '', 11, 0, 0, 0),
(56, 'login', '��������������', '��������������', 6, 0, 1, 0),
(57, 'logout', '', '', 6, 0, 1, 0),
(58, 'registration', '', '', 6, 0, 1, 0),
(59, 'do', '', '', 6, 0, 56, 0),
(60, 'do', '', '', 6, 0, 58, 0),
(61, 'my', '', '', 11, 0, 1, 0),
(62, 'projects', '', '', 11, 0, 61, 0),
(63, 'bugs', '', '', 11, 0, 61, 0),
(64, 'project', '', '', 11, 0, 61, 0),
(65, 'new', '', '', 11, 0, 64, 0),
(67, 'bug', '', '', 11, 0, 1, 0),
(68, 'show', '', '', 11, 0, 67, 1),
(69, 'add', '', '', 11, 0, 67, 0),
(70, 'show', '��������� � �������', '��������� � �������', 11, 0, 64, 1),
(71, 'edit', '', '', 11, 0, 64, 0),
(72, 'profile', '', '', 10, 0, 1, 0),
(73, 'show', '', '', 10, 0, 72, 1),
(74, 'edit', '', '', 10, 0, 72, 0),
(75, 'ajax', '', '', 11, 0, 67, 0),
(76, 'search', '', '', 11, 0, 1, 0),
(77, 'result', '', '', 11, 0, 76, 0),
(78, 'newpass', '', '', 10, 0, 74, 0),
(79, 'requests', '', '', 11, 0, 1, 0),
(87, 'bugs', '', '', 11, 0, 64, 1),
(888, 'test', '', '', 18, 0, 1, 0);

-- --------------------------------------------------------

--
-- ��������� ������� `USER`
--

CREATE TABLE IF NOT EXISTS `USER` (
  `USER_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `NICK` varchar(32) NOT NULL,
  `FRST_NM` varchar(50) DEFAULT NULL,
  `LAST_NM` varchar(50) DEFAULT NULL,
  `SECND_NM` varchar(50) DEFAULT NULL,
  `PASSW_HASH` varchar(32) NOT NULL,
  `USR_TYP` tinyint(1) NOT NULL,
  `ACTIVE` tinyint(1) NOT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `LANG_ID` int(11) DEFAULT NULL,
  `DFLT_PROJ_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`USER_ID`),
  UNIQUE KEY `IX__USER__NICK` (`NICK`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=26 ;

--
-- ���� ������ ������� `USER`
--

INSERT INTO `USER` (`USER_ID`, `NICK`, `FRST_NM`, `LAST_NM`, `SECND_NM`, `PASSW_HASH`, `USR_TYP`, `ACTIVE`, `EMAIL`, `LANG_ID`, `DFLT_PROJ_ID`) VALUES
(1, 'EntityFX', '�����', '�������', '����������', '408edad392248bc60f0e7ddaed995fe5', 0, 1, 'artem.solopiy@gmail.com', NULL, 4),
(3, 'Vasiliy', '����', '�������', '����������', 'f188f8028be984727e58c6aed3cbe2d3', 0, 1, 'tym_@mail.ru', NULL, 7),
(6, 'Oliya', NULL, NULL, NULL, '', 0, 0, NULL, NULL, NULL),
(7, 'Marat', '�����', '�������', '�����������', '408edad392248bc60f0e7ddaed995fe5', 0, 1, NULL, NULL, NULL),
(8, 'Dmitry', '�������', '�����������', '��������', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 0, 'dmitry@medved.net', NULL, NULL),
(9, 'Ivan', '', '', '', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 0, '', NULL, NULL),
(10, 'Misha', '', '', '', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 0, '', NULL, NULL),
(11, 'edf', '', '', '', 'baee68b86198d8fe688d0c7fb695e8d8', 0, 0, '', NULL, NULL),
(13, 'Artem', 'Artem', 'Solopiy', 'Valer''evich', '408edad392248bc60f0e7ddaed995fe5', 0, 1, '', NULL, NULL),
(14, 'Kolya', '', '', '', 'fc5dd6fdd66051e8a732b5ea5f532993', 0, 1, '', NULL, NULL),
(15, 'Android', '����', 'Android', '����������', '5a0cf5667029ac6bbad1c4ecdc3f659e', 0, 1, 'artem.solopiy@gmail.com', NULL, NULL),
(16, 'Los', '', '', '', '408edad392248bc60f0e7ddaed995fe5', 0, 0, '', NULL, NULL),
(19, 'testtest', 'Name', 'Surname', 'SecondName', '1aa2b2ad9df19416c64defac32050dc8', 0, 1, '', NULL, NULL),
(20, 'GreenDragon', '�����', '�������', '�����������', '07c100dcae659c3fe410e98e3177aa4d', 0, 1, 'green_dragon_88@mail.ru', NULL, NULL),
(21, 'Irina', '', '', '', '408edad392248bc60f0e7ddaed995fe5', 0, 0, '', NULL, NULL),
(22, 'zion', '����', '', '', '92266582e5664b308ec00fdda7b8d10a', 0, 1, 'mikhail.zagorny@gmail.com', NULL, NULL),
(23, 'byaka', '', '', '', '6101d1720309786ca725670c6f905ba5', 0, 1, '', NULL, NULL),
(24, 'Timur', 'Timur', '', '', 'f0c1071c737742ce5650a53b143240c9', 0, 1, '', NULL, 4),
(25, 'vasya', '����', '������', '����', 'fb375e2c998c1ed7d8c51824e12e187a', 0, 1, 'vasya@mail.ru', NULL, NULL);

-- --------------------------------------------------------

--
-- ��������� ������� `USER_ACTIV`
--

CREATE TABLE IF NOT EXISTS `USER_ACTIV` (
  `USER_ID` int(11) unsigned NOT NULL,
  `ACTIV_KEY` varchar(34) NOT NULL,
  `EXPRY_DT` datetime NOT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- ���� ������ ������� `USER_ACTIV`
--

INSERT INTO `USER_ACTIV` (`USER_ID`, `ACTIV_KEY`, `EXPRY_DT`) VALUES
(1, '6a6ea4a185f7f3768d4a6f8f4586bf', '2012-02-12 16:29:18');

-- --------------------------------------------------------

--
-- ��������� ������� `USER_IN_PROJ`
--

CREATE TABLE IF NOT EXISTS `USER_IN_PROJ` (
  `USER_IN_PROJ_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROJ_ID` int(11) NOT NULL,
  `USER_ID` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`USER_IN_PROJ_ID`),
  UNIQUE KEY `IX__USER_IN_PROJ__USER_ID__PROJ_ID` (`USER_ID`,`PROJ_ID`),
  KEY `IX__USER_IN_PROJ__PROJ_ID` (`PROJ_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

--
-- ���� ������ ������� `USER_IN_PROJ`
--

INSERT INTO `USER_IN_PROJ` (`USER_IN_PROJ_ID`, `PROJ_ID`, `USER_ID`) VALUES
(2, 4, 20),
(1, 4, 24);

-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_AllUserProjects`
--
CREATE TABLE IF NOT EXISTS `view_AllUserProjects` (
`ProjectID` int(11)
,`Name` varchar(100)
,`UserID` int(11) unsigned
,`NickName` varchar(32)
,`My` varchar(1)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_CommentsDetail`
--
CREATE TABLE IF NOT EXISTS `view_CommentsDetail` (
`ID` int(11) unsigned
,`ItemID` int(11)
,`UserID` int(11) unsigned
,`Time` timestamp
,`Comment` varchar(255)
,`NickName` varchar(32)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ErrorCommentsCount`
--
CREATE TABLE IF NOT EXISTS `view_ErrorCommentsCount` (
`UserID` int(11) unsigned
,`ReportID` int(11)
,`ProjectID` int(11)
,`ItemUserComment` bigint(21)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ItemFullInfo`
--
CREATE TABLE IF NOT EXISTS `view_ItemFullInfo` (
`ID` int(11)
,`Kind` enum('Defect','Task')
,`UserID` int(11) unsigned
,`AssignedTo` int(11)
,`ProjectID` int(11)
,`PriorityLevel` enum('0','1','2')
,`Status` enum('NEW','ASSESSED','IDENTIFIED','RESOLVED','CLOSED')
,`CreateDateTime` timestamp
,`Title` varchar(150)
,`ErrorType` enum('Crash','Cosmetic','Error Handling','Functional','Minor','Major','Setup','Block')
,`Description` text
,`StepsText` text
,`ProjectName` varchar(100)
,`NickName` varchar(32)
,`AssignedNickName` varchar(32)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ProjectAndErrors`
--
CREATE TABLE IF NOT EXISTS `view_ProjectAndErrors` (
`ProjectID` int(11)
,`ProjectName` varchar(100)
,`Description` varchar(25)
,`ProjectOwnerID` int(11) unsigned
,`OwnerNickName` varchar(32)
,`CreateDateTime` datetime
,`CountSubscribeRequests` bigint(21)
,`CountUsers` bigint(22)
,`NEW` bigint(21)
,`IDENTIFIED` bigint(21)
,`ASSESSED` bigint(21)
,`RESOLVED` bigint(21)
,`CLOSED` bigint(21)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ProjectAndOwnerNick`
--
CREATE TABLE IF NOT EXISTS `view_ProjectAndOwnerNick` (
`ProjectID` int(11)
,`Name` varchar(100)
,`Description` tinytext
,`OwnerID` int(11) unsigned
,`CreateDate` datetime
,`OwnerNickName` varchar(32)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ProjectCommentsCount`
--
CREATE TABLE IF NOT EXISTS `view_ProjectCommentsCount` (
`ProjectID` int(11)
,`UserID` int(11) unsigned
,`CountComments` decimal(41,0)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ProjectInfoWithoutOwner`
--
CREATE TABLE IF NOT EXISTS `view_ProjectInfoWithoutOwner` (
`ProjectID` int(11)
,`ProjectName` varchar(100)
,`Description` varchar(25)
,`ProjectOwnerID` int(11) unsigned
,`OwnerNickName` varchar(32)
,`CreateDateTime` datetime
,`CountSubscribeRequests` bigint(21)
,`CountUsers` bigint(22)
,`NEW` bigint(21)
,`IDENTIFIED` bigint(21)
,`ASSESSED` bigint(21)
,`RESOLVED` bigint(21)
,`CLOSED` bigint(21)
,`UserID` int(11) unsigned
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ProjectRequestsCounters`
--
CREATE TABLE IF NOT EXISTS `view_ProjectRequestsCounters` (
`ProjectID` int(11)
,`Name` varchar(100)
,`Description` varchar(25)
,`OwnerID` int(11) unsigned
,`OwnerNickName` varchar(32)
,`CreateDate` datetime
,`CountUsers` bigint(22)
,`CountRequests` bigint(21)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ProjectUsersAndSubscribes`
--
CREATE TABLE IF NOT EXISTS `view_ProjectUsersAndSubscribes` (
`ProjectID` int(11)
,`ProjectName` varchar(100)
,`Description` varchar(25)
,`ProjectOwnerID` int(11) unsigned
,`OwnerNickName` varchar(32)
,`CreateDateTime` datetime
,`CountUsers` bigint(22)
,`CountSubscribeRequests` bigint(21)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_ProjectUsersCount`
--
CREATE TABLE IF NOT EXISTS `view_ProjectUsersCount` (
`ProjectID` int(11)
,`Name` varchar(100)
,`Description` varchar(25)
,`OwnerID` int(11) unsigned
,`OwnerNickName` varchar(32)
,`CreateDate` datetime
,`CountUsers` bigint(22)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_SubscribesDetails`
--
CREATE TABLE IF NOT EXISTS `view_SubscribesDetails` (
`ID` int(11)
,`UserID` int(11) unsigned
,`ProjectID` int(11)
,`RequestTime` datetime
,`ProjectName` varchar(100)
,`OwnerID` int(11) unsigned
,`ProjectOwnerNick` varchar(32)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_SubscribesUserNick`
--
CREATE TABLE IF NOT EXISTS `view_SubscribesUserNick` (
`ID` int(11)
,`UserID` int(11) unsigned
,`ProjectID` int(11)
,`RequestTime` datetime
,`NickName` varchar(32)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_UserInProjectErrors`
--
CREATE TABLE IF NOT EXISTS `view_UserInProjectErrors` (
`UserID` int(11) unsigned
,`ProjectID` int(11)
,`NickName` varchar(32)
,`NEW` bigint(21)
,`IDENTIFIED` bigint(21)
,`ASSESSED` bigint(21)
,`RESOLVED` bigint(21)
,`CLOSED` bigint(21)
,`CountErrors` bigint(21)
,`Owner` bigint(20)
);
-- --------------------------------------------------------

--
-- ����������� ��������� ��� ������������� `view_UserInProjectErrorsAndComments`
--
CREATE TABLE IF NOT EXISTS `view_UserInProjectErrorsAndComments` (
`UserID` int(11) unsigned
,`ProjectID` int(11)
,`NickName` varchar(32)
,`NEW` bigint(21)
,`IDENTIFIED` bigint(21)
,`ASSESSED` bigint(21)
,`RESOLVED` bigint(21)
,`CLOSED` bigint(21)
,`CountErrors` bigint(21)
,`Owner` bigint(20)
,`CountCreated` bigint(21)
,`CountComments` decimal(41,0)
);
-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_AllUserProjects`
--
DROP TABLE IF EXISTS `view_AllUserProjects`;

CREATE VIEW `view_AllUserProjects` AS select `P`.`PROJ_ID` AS `ProjectID`,`P`.`PROJ_NM` AS `Name`,`P`.`USER_ID` AS `UserID`,`U`.`NICK` AS `NickName`,_utf8'1' AS `My` from (`PROJ` `P` left join `USER` `U` on((`P`.`USER_ID` = `U`.`USER_ID`))) union select `UP`.`PROJ_ID` AS `ProjectID`,`P`.`PROJ_NM` AS `Name`,`UP`.`USER_ID` AS `UserID`,`U`.`NICK` AS `NickName`,_utf8'0' AS `My` from ((`USER_IN_PROJ` `UP` join `PROJ` `P` on((`UP`.`PROJ_ID` = `P`.`PROJ_ID`))) left join `USER` `U` on((`UP`.`USER_ID` = `U`.`USER_ID`)));

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_CommentsDetail`
--
DROP TABLE IF EXISTS `view_CommentsDetail`;

CREATE VIEW `view_CommentsDetail` AS select `IC`.`ITEM_CMMENT_ID` AS `ID`,`IC`.`ITEM_ID` AS `ItemID`,`IC`.`USER_ID` AS `UserID`,`IC`.`CRT_TM` AS `Time`,`IC`.`CMMENT` AS `Comment`,`U`.`NICK` AS `NickName` from (`ITEM_CMMENT` `IC` left join `USER` `U` on((`IC`.`USER_ID` = `U`.`USER_ID`)));

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ErrorCommentsCount`
--
DROP TABLE IF EXISTS `view_ErrorCommentsCount`;

CREATE VIEW `view_ErrorCommentsCount` AS select `IC`.`USER_ID` AS `UserID`,`IC`.`ITEM_ID` AS `ReportID`,`I`.`PROJ_ID` AS `ProjectID`,count(`IC`.`ITEM_CMMENT_ID`) AS `ItemUserComment` from (`ITEM_CMMENT` `IC` join `ITEM` `I` on((`IC`.`ITEM_ID` = `I`.`ITEM_ID`))) group by `IC`.`ITEM_ID`,`IC`.`USER_ID`;

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ItemFullInfo`
--
DROP TABLE IF EXISTS `view_ItemFullInfo`;

CREATE VIEW `view_ItemFullInfo` AS select `I`.`ITEM_ID` AS `ID`,`I`.`KIND` AS `Kind`,`I`.`USER_ID` AS `UserID`,`I`.`ASSGN_TO` AS `AssignedTo`,`I`.`PROJ_ID` AS `ProjectID`,`I`.`PRTY_LVL` AS `PriorityLevel`,`I`.`STAT` AS `Status`,`I`.`CRT_TM` AS `CreateDateTime`,`I`.`TITLE` AS `Title`,`IDF`.`DEFECT_TYP` AS `ErrorType`,`I`.`DESCR` AS `Description`,`IDF`.`STP_TXT` AS `StepsText`,`P`.`PROJ_NM` AS `ProjectName`,`U`.`NICK` AS `NickName`,`U1`.`NICK` AS `AssignedNickName` from ((((`ITEM` `I` join `PROJ` `P` on((`I`.`PROJ_ID` = `P`.`PROJ_ID`))) left join `USER` `U` on((`I`.`USER_ID` = `U`.`USER_ID`))) left join `USER` `U1` on((`I`.`ASSGN_TO` = `U1`.`USER_ID`))) left join `ITEM_DEFECT` `IDF` on((`I`.`ITEM_ID` = `IDF`.`ITEM_DEFECT_ID`)));

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ProjectAndErrors`
--
DROP TABLE IF EXISTS `view_ProjectAndErrors`;

CREATE VIEW `view_ProjectAndErrors` AS select `P`.`ProjectID` AS `ProjectID`,`P`.`ProjectName` AS `ProjectName`,`P`.`Description` AS `Description`,`P`.`ProjectOwnerID` AS `ProjectOwnerID`,`P`.`OwnerNickName` AS `OwnerNickName`,`P`.`CreateDateTime` AS `CreateDateTime`,`P`.`CountSubscribeRequests` AS `CountSubscribeRequests`,`P`.`CountUsers` AS `CountUsers`,count((case when (`I`.`STAT` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`I`.`STAT` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`I`.`STAT` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`I`.`STAT` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`I`.`STAT` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED` from (`view_ProjectUsersAndSubscribes` `P` left join `ITEM` `I` on((`I`.`PROJ_ID` = `P`.`ProjectID`))) group by `P`.`ProjectID`;

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ProjectAndOwnerNick`
--
DROP TABLE IF EXISTS `view_ProjectAndOwnerNick`;

CREATE VIEW `view_ProjectAndOwnerNick` AS select `P`.`PROJ_ID` AS `ProjectID`,`P`.`PROJ_NM` AS `Name`,`P`.`DESCR` AS `Description`,`P`.`USER_ID` AS `OwnerID`,`P`.`CRT_TM` AS `CreateDate`,`U`.`NICK` AS `OwnerNickName` from (`PROJ` `P` left join `USER` `U` on((`P`.`USER_ID` = `U`.`USER_ID`)));

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ProjectCommentsCount`
--
DROP TABLE IF EXISTS `view_ProjectCommentsCount`;

CREATE VIEW `view_ProjectCommentsCount` AS select `UP`.`ProjectID` AS `ProjectID`,`UP`.`UserID` AS `UserID`,ifnull(sum(`EC`.`ItemUserComment`),0) AS `CountComments` from (`view_AllUserProjects` `UP` left join `view_ErrorCommentsCount` `EC` on(((`UP`.`ProjectID` = `EC`.`ProjectID`) and (`UP`.`UserID` = `EC`.`UserID`)))) group by `UP`.`UserID`,`UP`.`ProjectID`;

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ProjectInfoWithoutOwner`
--
DROP TABLE IF EXISTS `view_ProjectInfoWithoutOwner`;

CREATE VIEW `view_ProjectInfoWithoutOwner` AS select `P`.`ProjectID` AS `ProjectID`,`P`.`ProjectName` AS `ProjectName`,`P`.`Description` AS `Description`,`P`.`ProjectOwnerID` AS `ProjectOwnerID`,`P`.`OwnerNickName` AS `OwnerNickName`,`P`.`CreateDateTime` AS `CreateDateTime`,`P`.`CountSubscribeRequests` AS `CountSubscribeRequests`,`P`.`CountUsers` AS `CountUsers`,`P`.`NEW` AS `NEW`,`P`.`IDENTIFIED` AS `IDENTIFIED`,`P`.`ASSESSED` AS `ASSESSED`,`P`.`RESOLVED` AS `RESOLVED`,`P`.`CLOSED` AS `CLOSED`,`U`.`USER_ID` AS `UserID` from (`USER_IN_PROJ` `U` join `view_ProjectAndErrors` `P` on((`P`.`ProjectID` = `U`.`PROJ_ID`)));

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ProjectRequestsCounters`
--
DROP TABLE IF EXISTS `view_ProjectRequestsCounters`;

CREATE VIEW `view_ProjectRequestsCounters` AS select `P`.`ProjectID` AS `ProjectID`,`P`.`Name` AS `Name`,`P`.`Description` AS `Description`,`P`.`OwnerID` AS `OwnerID`,`P`.`OwnerNickName` AS `OwnerNickName`,`P`.`CreateDate` AS `CreateDate`,`P`.`CountUsers` AS `CountUsers`,count(`S`.`PROJ_ID`) AS `CountRequests` from (`view_ProjectUsersCount` `P` left join `SUBSCR_RQST` `S` on((`P`.`ProjectID` = `S`.`PROJ_ID`))) group by `P`.`ProjectID`;

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ProjectUsersAndSubscribes`
--
DROP TABLE IF EXISTS `view_ProjectUsersAndSubscribes`;

CREATE VIEW `view_ProjectUsersAndSubscribes` AS select `P`.`PROJ_ID` AS `ProjectID`,`P`.`PROJ_NM` AS `ProjectName`,left(`P`.`DESCR`,25) AS `Description`,`P`.`USER_ID` AS `ProjectOwnerID`,`U`.`NICK` AS `OwnerNickName`,`P`.`CRT_TM` AS `CreateDateTime`,(count(`UP`.`PROJ_ID`) + (case when (`P`.`USER_ID` is not null) then 1 else 0 end)) AS `CountUsers`,count(`SR`.`PROJ_ID`) AS `CountSubscribeRequests` from (((`PROJ` `P` left join `USER` `U` on((`P`.`USER_ID` = `U`.`USER_ID`))) left join `USER_IN_PROJ` `UP` on((`UP`.`PROJ_ID` = `P`.`PROJ_ID`))) left join `SUBSCR_RQST` `SR` on((`SR`.`PROJ_ID` = `P`.`PROJ_ID`))) group by `P`.`PROJ_ID`;

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_ProjectUsersCount`
--
DROP TABLE IF EXISTS `view_ProjectUsersCount`;

CREATE VIEW `view_ProjectUsersCount` AS select `P`.`PROJ_ID` AS `ProjectID`,`P`.`PROJ_NM` AS `Name`,left(`P`.`DESCR`,25) AS `Description`,`P`.`USER_ID` AS `OwnerID`,`U`.`NICK` AS `OwnerNickName`,`P`.`CRT_TM` AS `CreateDate`,(count(`UP`.`PROJ_ID`) + (`P`.`USER_ID` is not null)) AS `CountUsers` from ((`PROJ` `P` left join `USER_IN_PROJ` `UP` on((`UP`.`PROJ_ID` = `P`.`PROJ_ID`))) left join `USER` `U` on((`P`.`USER_ID` = `U`.`USER_ID`))) group by `P`.`PROJ_ID`;

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_SubscribesDetails`
--
DROP TABLE IF EXISTS `view_SubscribesDetails`;

CREATE VIEW `view_SubscribesDetails` AS select `SR`.`SUBSCR_RQST_ID` AS `ID`,`SR`.`USER_ID` AS `UserID`,`SR`.`PROJ_ID` AS `ProjectID`,`SR`.`RQST_TM` AS `RequestTime`,`P`.`PROJ_NM` AS `ProjectName`,`P`.`USER_ID` AS `OwnerID`,`U`.`NICK` AS `ProjectOwnerNick` from ((`SUBSCR_RQST` `SR` join `PROJ` `P` on((`SR`.`PROJ_ID` = `P`.`PROJ_ID`))) left join `USER` `U` on((`P`.`USER_ID` = `U`.`USER_ID`)));

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_SubscribesUserNick`
--
DROP TABLE IF EXISTS `view_SubscribesUserNick`;

CREATE VIEW `view_SubscribesUserNick` AS select `SR`.`SUBSCR_RQST_ID` AS `ID`,`SR`.`USER_ID` AS `UserID`,`SR`.`PROJ_ID` AS `ProjectID`,`SR`.`RQST_TM` AS `RequestTime`,`U`.`NICK` AS `NickName` from (`SUBSCR_RQST` `SR` left join `USER` `U` on((`SR`.`USER_ID` = `U`.`USER_ID`)));

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_UserInProjectErrors`
--
DROP TABLE IF EXISTS `view_UserInProjectErrors`;

CREATE VIEW `view_UserInProjectErrors` AS select `P`.`USER_ID` AS `UserID`,`P`.`PROJ_ID` AS `ProjectID`,`U`.`NICK` AS `NickName`,count((case when (`I`.`STAT` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`I`.`STAT` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`I`.`STAT` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`I`.`STAT` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`I`.`STAT` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED`,count(`I`.`ITEM_ID`) AS `CountErrors`,1 AS `Owner` from ((`PROJ` `P` left join `USER` `U` on((`P`.`USER_ID` = `U`.`USER_ID`))) left join `ITEM` `I` on(((`P`.`PROJ_ID` = `I`.`PROJ_ID`) and (`P`.`USER_ID` = `I`.`ASSGN_TO`)))) group by `P`.`PROJ_ID`,`P`.`USER_ID`,`U`.`USER_ID`,`I`.`ASSGN_TO` union select `UP`.`USER_ID` AS `UserID`,`UP`.`PROJ_ID` AS `ProjectID`,`U`.`NICK` AS `NickName`,count((case when (`I`.`STAT` = _cp1251'NEW') then _utf8'NEW' else NULL end)) AS `NEW`,count((case when (`I`.`STAT` = _cp1251'IDENTIFIED') then _utf8'IDENTIFIED' else NULL end)) AS `IDENTIFIED`,count((case when (`I`.`STAT` = _cp1251'ASSESSED') then _utf8'ASSESSED' else NULL end)) AS `ASSESSED`,count((case when (`I`.`STAT` = _cp1251'RESOLVED') then _utf8'RESOLVED' else NULL end)) AS `RESOLVED`,count((case when (`I`.`STAT` = _cp1251'CLOSED') then _utf8'CLOSED' else NULL end)) AS `CLOSED`,count(`I`.`ITEM_ID`) AS `CountErrors`,0 AS `Owner` from ((`USER_IN_PROJ` `UP` left join `USER` `U` on((`UP`.`USER_ID` = `U`.`USER_ID`))) left join `ITEM` `I` on(((`UP`.`PROJ_ID` = `I`.`PROJ_ID`) and (`UP`.`USER_ID` = `I`.`ASSGN_TO`)))) group by `UP`.`PROJ_ID`,`UP`.`USER_ID`;

-- --------------------------------------------------------

--
-- ��������� ��� ������������� `view_UserInProjectErrorsAndComments`
--
DROP TABLE IF EXISTS `view_UserInProjectErrorsAndComments`;

CREATE VIEW `view_UserInProjectErrorsAndComments` AS select `P`.`UserID` AS `UserID`,`P`.`ProjectID` AS `ProjectID`,`P`.`NickName` AS `NickName`,`P`.`NEW` AS `NEW`,`P`.`IDENTIFIED` AS `IDENTIFIED`,`P`.`ASSESSED` AS `ASSESSED`,`P`.`RESOLVED` AS `RESOLVED`,`P`.`CLOSED` AS `CLOSED`,`P`.`CountErrors` AS `CountErrors`,`P`.`Owner` AS `Owner`,count(`I`.`ITEM_ID`) AS `CountCreated`,`PC`.`CountComments` AS `CountComments` from ((`view_UserInProjectErrors` `P` left join `ITEM` `I` on(((`P`.`ProjectID` = `I`.`PROJ_ID`) and (`P`.`UserID` = `I`.`USER_ID`)))) join `view_ProjectCommentsCount` `PC` on(((`P`.`ProjectID` = `PC`.`ProjectID`) and (`P`.`UserID` = `PC`.`UserID`)))) group by `P`.`ProjectID`,`P`.`UserID`;

--
-- ����������� �������� ����� ����������� ������
--

--
-- ����������� �������� ����� ������� `ITEM`
--
ALTER TABLE `ITEM`
  ADD CONSTRAINT `FK__ITEM__PROJ__PROJ_ID` FOREIGN KEY (`PROJ_ID`) REFERENCES `PROJ` (`PROJ_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK__ITEM__USER__USER_ID` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- ����������� �������� ����� ������� `ITEM_CMMENT`
--
ALTER TABLE `ITEM_CMMENT`
  ADD CONSTRAINT `FK__ITEM_CMMENT__ITEM__ITEM_ID` FOREIGN KEY (`ITEM_ID`) REFERENCES `ITEM` (`ITEM_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK__ITEM_CMMENT__USER__USER_ID` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- ����������� �������� ����� ������� `ITEM_DEFECT`
--
ALTER TABLE `ITEM_DEFECT`
  ADD CONSTRAINT `FK__ITEM_DEFECT__ITEM__ITEM_DEFECT_ID` FOREIGN KEY (`ITEM_DEFECT_ID`) REFERENCES `ITEM` (`ITEM_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- ����������� �������� ����� ������� `ITEM_HIST`
--
ALTER TABLE `ITEM_HIST`
  ADD CONSTRAINT `FK__ITEM_HIST__ITEM__ITEM_ID` FOREIGN KEY (`ITEM_ID`) REFERENCES `ITEM` (`ITEM_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK__ITEM_HIST__USER__USER_ID` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- ����������� �������� ����� ������� `PROJ`
--
ALTER TABLE `PROJ`
  ADD CONSTRAINT `FK__PROJ__USER__USER_ID` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- ����������� �������� ����� ������� `USER_ACTIV`
--
ALTER TABLE `USER_ACTIV`
  ADD CONSTRAINT `FK__USER_ACTIV__USER__USER_ID` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- ����������� �������� ����� ������� `USER_IN_PROJ`
--
ALTER TABLE `USER_IN_PROJ`
  ADD CONSTRAINT `FK__USER_IN_PROJ__PROJ__PROJ_ID` FOREIGN KEY (`PROJ_ID`) REFERENCES `PROJ` (`PROJ_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK__USER_IN_PROJ__USER__USER_ID` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`);

DELIMITER $$
--
-- ���������
--
CREATE  PROCEDURE `AcceptRequest`(IN _ProjectID INT, IN ItemsList TEXT)
BEGIN
    DECLARE SymbolPosition INT;
    DECLARE ItemString     INT;
    DECLARE ItemValue      INT;

    DECLARE sID            INT;
    DECLARE UserID         INT;


    DECLARE CursorCounter  INT DEFAULT 1;
    DECLARE CountItems     INT;
    DECLARE CursorEnd      INT DEFAULT 0;
    DECLARE ItemsCursor CURSOR FOR
    SELECT *
    FROM
        SubscribesToAssign;

    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET CursorEnd = 1;

    SET SymbolPosition = 1;

    CREATE TEMPORARY TABLE ItemsTable(
        ItemID INT
    );

    CREATE TEMPORARY TABLE SubscribesToAssign(
        ID INT,
        UserID INT,
        PRIMARY KEY (ID)
    );

-- Parsing incoming data
    z1:
    WHILE SymbolPosition > 0
    DO
        SELECT locate(';', ItemsList, SymbolPosition)
        INTO
            SymbolPosition;
        SELECT substring(ItemsList, SymbolPosition + 1, locate(';', ItemsList, SymbolPosition + 1) - SymbolPosition - 1)
        INTO
            ItemString;
        SELECT cast(ItemString AS SIGNED)
        INTO
            ItemValue;
        INSERT INTO ItemsTable VALUES (ItemValue);
        IF SymbolPosition = 0 THEN
            LEAVE z1;
        END IF;
        SET SymbolPosition = SymbolPosition + 1;
    END WHILE;

    -- Populate Subscribes to assign
    INSERT INTO SubscribesToAssign
        SELECT 
            SR.SUBSCR_RQST_ID, 
            SR.USER_ID
        FROM
            SUBSCR_RQST SR
        INNER JOIN ItemsTable I
            ON SR.SUBSCR_RQST_ID = I.ItemID
        WHERE
            SR.PROJ_ID = _ProjectID;

    SELECT count(*) INTO CountItems FROM SubscribesToAssign;

    OPEN ItemsCursor;
    START TRANSACTION;

    WHILE CursorEnd = 0
    DO
        FETCH ItemsCursor INTO sID, UserID;
        IF CursorCounter <= CountItems THEN
            DELETE FROM SUBSCR_RQST WHERE SUBSCR_RQST_ID = sID;
            INSERT INTO USER_IN_PROJ VALUES(0,_ProjectID,UserID);
        END IF;
        SET CursorCounter = CursorCounter + 1;
    END WHILE;

    COMMIT;
    CLOSE ItemsCursor;

END$$

CREATE  PROCEDURE `AddItem`(IN UserID INT, IN ProjectID INT, IN AssignedTo INT, IN PriorityLevel VARCHAR(1), IN StatusValue VARCHAR(50), IN `CreateDateTime` DATETIME, IN Title VARCHAR(255), IN Kind VARCHAR(50), IN Description TEXT, IN ItemType VARCHAR(50), IN StepsText TEXT)
BEGIN
    DECLARE LAST_ID INT;
    INSERT INTO ITEM 
        (
            USER_ID,
            PROJ_ID,
            PRTY_LVL,
            STAT,
            CRT_TM,
            TITLE,
            KIND,
            DESCR,
            ASSGN_TO
        ) 
        VALUES
        (
            UserID,
            ProjectID,
            PriorityLevel,
            StatusValue,
            `CreateDateTime`,
            Title,
            Kind,
            Description,
            AssignedTo
        );

    SET LAST_ID=(SELECT last_insert_id() FROM ITEM LIMIT 0,1);

    IF Kind = 'Defect' THEN
        INSERT INTO ITEM_DEFECT (ITEM_DEFECT_ID,DEFECT_TYP,STP_TXT) 
            VALUES (LAST_ID,ItemType,StepsText);
    END IF; 
END$$

CREATE  PROCEDURE `DeleteCommentsFromList`(IN _UserID INT, IN ItemsList TEXT)
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

    INSERT INTO CommentsForDelete SELECT ITEM_CMMENT_ID FROM 
        ITEM_CMMENT RC
    INNER JOIN  ItemsTable I
        ON RC.ITEM_CMMENT_ID=I.ItemID
    WHERE USER_ID=_UserID;

    DELETE FROM ITEM_CMMENT WHERE ITEM_CMMENT_ID IN (SELECT ItemID FROM CommentsForDelete);

END$$

CREATE  PROCEDURE `DeleteItemsFromList`(IN _UserID INT, IN _ProjectID INT, IN ItemsList TEXT)
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

    SELECT USER_ID INTO _OwnerID FROM PROJ WHERE PROJ_ID=_ProjectID;

    CREATE TEMPORARY TABLE ItemsForDelete (
        ItemID INT
    );

    /*
    Is current user project owner
    */
    IF _UserID=_OwnerID THEN
        INSERT INTO ItemsForDelete (ItemID) (SELECT 
                I.ITEM_ID
            FROM 
                ITEM I
            INNER JOIN ItemsTable T
                ON I.ITEM_ID=`T`.ItemID
            WHERE I.PROJ_ID=_ProjectID);

    ELSE
        INSERT INTO ItemsForDelete (ItemID) (SELECT 
                I.ITEM_ID
            FROM 
                ITEM I
            INNER JOIN ItemsTable T
                ON I.ITEM_ID=`T`.ItemID
            WHERE I.USER_ID=_UserID AND I.PROJ_ID=_ProjectID);
    END IF;

    DELETE FROM ITEM WHERE ITEM_ID IN (SELECT ItemID FROM ItemsForDelete);

END$$

CREATE  PROCEDURE `DeleteProjects`(IN _UserID INT, IN ItemsList TEXT)
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

    INSERT INTO ProjectsForDelete SELECT PROJ_ID FROM 
        PROJ P
    INNER JOIN ItemsTable I
        ON P.PROJ_ID=I.ItemID
    WHERE P.USER_ID=_UserID;

    DELETE FROM PROJ WHERE PROJ_ID IN (SELECT ItemID FROM ProjectsForDelete);

END$$

CREATE  PROCEDURE `EditItem`(IN _ItemID INT, IN _Title VARCHAR(255), IN _PriorityLevel VARCHAR(1), IN _StatusValue VARCHAR(50), IN _AssignedTo INT, IN _Description TEXT, IN _DefectType VARCHAR(50), IN _StepsText TEXT)
BEGIN
    DECLARE ItemProjectID INT;
    DECLARE ItemKind VARCHAR(50);
    DECLARE AssignedProjectID INT;

    SELECT PROJ_ID, KIND INTO ItemProjectID,ItemKind FROM ITEM WHERE ITEM_ID=_ItemID;

    IF _AssignedTo!=NULL THEN
        SET AssignedProjectID=(SELECT PROJ_ID FROM USER_IN_PROJ WHERE USER_ID=_AssignedTo
        UNION
        SELECT PROJ_ID FROM PROJ WHERE USER_ID=_AssignedTo);

        IF ItemProjectID<>AssignedProjectID THEN 
            SET _AssignedTo=NULL;
        END IF;
    END IF;

    IF _Title<>'' THEN
        UPDATE ITEM SET 
            TITLE=_Title, 
            PRTY_LVL=_PriorityLevel,
            STAT=_StatusValue,
            DESCR=_Description,
            ASSGN_TO=_AssignedTo
        WHERE 
            ITEM_ID=_ItemID;
    
        IF ItemKind='Defect' THEN
            UPDATE ITEM_DEFECT SET DEFECT_TYP=_DefectType, STP_TXT=_StepsText WHERE ITEM_DEFECT_ID=_ItemID;
        END IF;
    END IF;
END$$

DELIMITER ;
