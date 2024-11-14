/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.41-0ubuntu0.12.04.1 : Database - core_ci_devel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `sys_backup_app` */

DROP TABLE IF EXISTS `sys_backup_app`;

CREATE TABLE `sys_backup_app` (
  `BackupId` int(11) NOT NULL AUTO_INCREMENT,
  `BackupName` varchar(250) DEFAULT NULL,
  `BackupTime` datetime DEFAULT NULL,
  `BackupUnitId` int(11) DEFAULT NULL,
  `BackupAddUserId` int(11) DEFAULT NULL,
  `BackupAddTime` datetime DEFAULT NULL,
  `BackupUpdateUserId` int(11) DEFAULT NULL,
  `BackupUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`BackupId`),
  KEY `FK_ci_backup` (`BackupUnitId`),
  KEY `FK_ci_backup_add_user` (`BackupAddUserId`),
  KEY `FK_ci_backup_update_user` (`BackupUpdateUserId`),
  CONSTRAINT `FK_sys_backup_app` FOREIGN KEY (`BackupUnitId`) REFERENCES `sys_unit` (`UnitId`) ON DELETE NO ACTION,
  CONSTRAINT `FK_sys_backup_appu` FOREIGN KEY (`BackupAddUserId`) REFERENCES `sys_user` (`UserId`) ON DELETE NO ACTION,
  CONSTRAINT `FK_sys_backup_appuu` FOREIGN KEY (`BackupUpdateUserId`) REFERENCES `sys_user` (`UserId`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_backup_app` */

/*Table structure for table `sys_backup_db` */

DROP TABLE IF EXISTS `sys_backup_db`;

CREATE TABLE `sys_backup_db` (
  `BackupId` int(11) NOT NULL AUTO_INCREMENT,
  `BackupName` varchar(250) DEFAULT NULL,
  `BackupTime` datetime DEFAULT NULL,
  `BackupUnit` varchar(255) DEFAULT NULL,
  `BackupAddUser` varchar(255) DEFAULT NULL,
  `BackupAddTime` datetime DEFAULT NULL,
  `BackupUpdateUser` varchar(255) DEFAULT NULL,
  `BackupUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`BackupId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `sys_backup_db` */

insert  into `sys_backup_db`(`BackupId`,`BackupName`,`BackupTime`,`BackupUnit`,`BackupAddUser`,`BackupAddTime`,`BackupUpdateUser`,`BackupUpdateTime`) values (1,'backup_2013_10_01_20_56.zip','2013-10-01 20:56:00','1','1','2013-10-02 03:56:25','1','2013-10-02 03:56:25'),(2,'backup_2013_10_17_14_35.zip','2013-10-17 14:35:00','1','1','2013-10-17 21:35:30','1','2013-10-17 21:35:30'),(3,'backup_2013_10_18_07_54.zip','2013-10-18 07:54:00','1','1','2013-10-18 14:54:07','1','2013-10-18 14:54:07');

/*Table structure for table `sys_captcha` */

DROP TABLE IF EXISTS `sys_captcha`;

CREATE TABLE `sys_captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `word` varchar(20) NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_captcha` */

/*Table structure for table `sys_config` */

DROP TABLE IF EXISTS `sys_config`;

CREATE TABLE `sys_config` (
  `ConfigId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ConfigCode` varchar(25) DEFAULT NULL,
  `ConfigName` varchar(50) DEFAULT NULL,
  `ConfigType` enum('text','radio','checkbox','combo','file') DEFAULT NULL,
  `ConfigValue` varchar(255) DEFAULT NULL,
  `ConfigUnitId` int(11) DEFAULT NULL,
  `ConfigAddUser` varchar(255) DEFAULT NULL,
  `ConfigAddTime` datetime DEFAULT NULL,
  `ConfigUpdateUser` varchar(255) DEFAULT NULL,
  `ConfigUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`ConfigId`),
  UNIQUE KEY `NewIndex1a` (`ConfigCode`,`ConfigUnitId`),
  KEY `FK_ci_config` (`ConfigUnitId`),
  CONSTRAINT `FK_ci_config` FOREIGN KEY (`ConfigUnitId`) REFERENCES `sys_unit` (`UnitId`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `sys_config` */

insert  into `sys_config`(`ConfigId`,`ConfigCode`,`ConfigName`,`ConfigType`,`ConfigValue`,`ConfigUnitId`,`ConfigAddUser`,`ConfigAddTime`,`ConfigUpdateUser`,`ConfigUpdateTime`) values (1,'COUNTER_PAS','Nomor yang dilompati','text','5,6,7,8,9,10',NULL,NULL,'2016-01-28 09:13:58',NULL,NULL),(2,'ACTIVE_TAHUN','Tahun aktif system','text','2016',NULL,NULL,'2016-01-28 09:13:49',NULL,NULL);

/*Table structure for table `sys_fav` */

DROP TABLE IF EXISTS `sys_fav`;

CREATE TABLE `sys_fav` (
  `FavId` int(11) NOT NULL AUTO_INCREMENT,
  `FavUserId` int(11) DEFAULT NULL,
  `FavMenuId` int(11) DEFAULT NULL,
  `FavCount` int(11) DEFAULT NULL,
  PRIMARY KEY (`FavId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_fav` */

/*Table structure for table `sys_group` */

DROP TABLE IF EXISTS `sys_group`;

CREATE TABLE `sys_group` (
  `GroupId` int(11) NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(150) DEFAULT NULL,
  `GroupDescription` varchar(255) DEFAULT NULL,
  `GroupAddUser` varchar(255) DEFAULT NULL,
  `GroupAddTime` datetime DEFAULT NULL,
  `GroupUpdateUser` varchar(255) DEFAULT NULL,
  `GroupUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`GroupId`),
  UNIQUE KEY `NewIndex1a` (`GroupName`),
  KEY `NewIndex1as` (`GroupDescription`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `sys_group` */

insert  into `sys_group`(`GroupId`,`GroupName`,`GroupDescription`,`GroupAddUser`,`GroupAddTime`,`GroupUpdateUser`,`GroupUpdateTime`) values (0,'Root','Super Administrator','system','2015-10-12 14:15:04','admin','2017-04-27 05:53:03');

/*Table structure for table `sys_group_detail` */

DROP TABLE IF EXISTS `sys_group_detail`;

CREATE TABLE `sys_group_detail` (
  `GroupDetailMenuActionId` int(11) NOT NULL,
  `GroupDetailGroupId` int(11) NOT NULL,
  `GroupDetailAddUser` varchar(255) DEFAULT NULL,
  `GroupDetailAddTime` datetime DEFAULT NULL,
  `GroupDetailUpdateUser` varchar(255) DEFAULT NULL,
  `GroupDetailUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`GroupDetailMenuActionId`,`GroupDetailGroupId`),
  KEY `FK_ci_group_menu_dummy_menu` (`GroupDetailMenuActionId`),
  KEY `FK_ci_group_menu_aksi` (`GroupDetailGroupId`),
  CONSTRAINT `FK_sys_group_detail_group` FOREIGN KEY (`GroupDetailGroupId`) REFERENCES `sys_group` (`GroupId`) ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_group_detail_menu_action` FOREIGN KEY (`GroupDetailMenuActionId`) REFERENCES `sys_menu_action` (`MenuActionId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `sys_group_detail` */

insert  into `sys_group_detail`(`GroupDetailMenuActionId`,`GroupDetailGroupId`,`GroupDetailAddUser`,`GroupDetailAddTime`,`GroupDetailUpdateUser`,`GroupDetailUpdateTime`) values (1,0,NULL,'2017-04-27 05:53:03','admin',NULL),(2,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(3,0,NULL,'2017-04-27 05:53:03','admin',NULL),(4,0,NULL,'2017-04-27 05:53:03','admin',NULL),(5,0,NULL,'2017-04-27 05:53:03','admin',NULL),(6,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(7,0,NULL,'2017-04-27 05:53:03','admin',NULL),(8,0,NULL,'2017-04-27 05:53:03','admin',NULL),(9,0,NULL,'2017-04-27 05:53:03','admin',NULL),(10,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(11,0,NULL,'2017-04-27 05:53:03','admin',NULL),(12,0,NULL,'2017-04-27 05:53:03','admin',NULL),(13,0,NULL,'2017-04-27 05:53:03','admin',NULL),(14,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(15,0,NULL,'2017-04-27 05:53:03','admin',NULL),(16,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(17,0,NULL,'2017-04-27 05:53:03','admin',NULL),(18,0,NULL,'2017-04-27 05:53:03','admin',NULL),(19,0,NULL,'2017-04-27 05:53:03','admin',NULL),(20,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(21,0,NULL,'2017-04-27 05:53:03','admin',NULL),(22,0,NULL,'2017-04-27 05:53:03','admin',NULL),(23,0,NULL,'2017-04-27 05:53:03','admin',NULL),(24,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(25,0,NULL,'2017-04-27 05:53:03','admin',NULL),(26,0,NULL,'2017-04-27 05:53:03','admin','2017-04-27 05:53:03'),(27,0,NULL,'2017-04-27 05:53:03','admin',NULL),(28,0,NULL,'2017-04-27 05:53:03','admin',NULL),(29,0,NULL,'2017-04-27 05:53:03','admin',NULL),(30,0,NULL,'2017-04-27 05:53:03','admin',NULL),(31,0,NULL,'2017-04-27 05:53:03','admin',NULL),(32,0,NULL,'2017-04-27 05:53:03','admin',NULL),(33,0,NULL,'2017-04-27 05:53:03','admin',NULL),(128,0,NULL,'2017-04-27 05:53:03','admin',NULL),(129,0,NULL,'2017-04-27 05:53:03','admin',NULL);

/*Table structure for table `sys_log` */

DROP TABLE IF EXISTS `sys_log`;

CREATE TABLE `sys_log` (
  `LogId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `LogUserId` int(11) DEFAULT NULL,
  `LogTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `LogIpAddress` varchar(45) DEFAULT NULL,
  `LogActivities` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`LogId`),
  KEY `FK_ci_log` (`LogUserId`),
  KEY `NewIndex1` (`LogActivities`),
  CONSTRAINT `FK_ci_log` FOREIGN KEY (`LogUserId`) REFERENCES `sys_user` (`UserId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_log` */

/*Table structure for table `sys_login_attempts` */

DROP TABLE IF EXISTS `sys_login_attempts`;

CREATE TABLE `sys_login_attempts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `sys_login_attempts` */

/*Table structure for table `sys_menu` */

DROP TABLE IF EXISTS `sys_menu`;

CREATE TABLE `sys_menu` (
  `MenuId` int(11) NOT NULL AUTO_INCREMENT,
  `MenuParentId` int(11) DEFAULT NULL,
  `MenuName` varchar(150) DEFAULT NULL,
  `MenuDescription` varchar(250) DEFAULT NULL,
  `MenuModule` varchar(150) DEFAULT NULL,
  `MenuIsShow` enum('Ya','Tidak') DEFAULT 'Ya',
  `MenuIcon` varchar(50) DEFAULT NULL,
  `MenuIconClass` varchar(250) DEFAULT NULL,
  `MenuOrder` int(2) DEFAULT NULL,
  PRIMARY KEY (`MenuId`),
  UNIQUE KEY `NewIndex12` (`MenuParentId`,`MenuName`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `sys_menu` */

insert  into `sys_menu`(`MenuId`,`MenuParentId`,`MenuName`,`MenuDescription`,`MenuModule`,`MenuIsShow`,`MenuIcon`,`MenuIconClass`,`MenuOrder`) values (1,NULL,'System','Manajemen System','system/Menu','Ya',NULL,NULL,99),(2,1,'Unit','Kepemilikan data, bersifat hirarki','system/Unit','Tidak',NULL,NULL,1),(3,1,'Group','Menu dan aksi suatu kelompok pada suatu unit','system/Group','Ya',NULL,NULL,2),(4,1,'User','Pengguna sistem dengan multi group','system/User','Ya',NULL,NULL,3),(5,1,'Log','Merekam beberapa aksi manipulasi data','system/Log','Tidak',NULL,NULL,4),(6,1,'Backup DB','Backup dan restore database','system/BackupDb','Tidak',NULL,NULL,5),(7,1,'Backup App','Backup dan restore aplikasi','system/BackupApp','Tidak',NULL,NULL,6),(8,1,'Config','Konfigurasi pada aplikasi','system/Config','Ya',NULL,NULL,7),(9,1,'Generate Module','Generate Module','generate/Module','Tidak',NULL,NULL,8);

/*Table structure for table `sys_menu_action` */

DROP TABLE IF EXISTS `sys_menu_action`;

CREATE TABLE `sys_menu_action` (
  `MenuActionId` int(11) NOT NULL AUTO_INCREMENT,
  `MenuActionMenuId` int(11) DEFAULT NULL,
  `MenuActionName` varchar(100) DEFAULT NULL,
  `MenuActionFunction` varchar(100) DEFAULT NULL,
  `MenuActionSegmen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MenuActionId`),
  UNIQUE KEY `UNIQUE_MENU` (`MenuActionMenuId`,`MenuActionSegmen`),
  KEY `FK_ci_dummy_menu_aksi` (`MenuActionMenuId`),
  CONSTRAINT `FK_sys_menu_action_menu` FOREIGN KEY (`MenuActionMenuId`) REFERENCES `sys_menu` (`MenuId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `sys_menu_action` */

insert  into `sys_menu_action`(`MenuActionId`,`MenuActionMenuId`,`MenuActionName`,`MenuActionFunction`,`MenuActionSegmen`) values (1,1,'View','index','system/Home/index'),(2,2,'View','index',NULL),(3,2,'Add','add',NULL),(4,2,'Update','update',NULL),(5,2,'Delete','delete',NULL),(6,3,'View','index','system/Group/index'),(7,3,'Add','add','system/Group/add'),(8,3,'Update','update','system/Group/update'),(9,3,'Delete','delete','system/Group/delete'),(10,4,'View','index','system/User/index'),(11,4,'Add','add','system/User/add'),(12,4,'Update','update','system/User/update'),(13,4,'Delete','delete','system/User/delete'),(14,5,'View','index','system/Log/index'),(15,5,'Detail','detail','system/Log/detail'),(16,6,'View','index','system/BackupDb/index'),(17,6,'Restore','restore','system/BackupDb/restore'),(18,6,'Detail','detail','system/BackupDb/detail'),(19,6,'Delete','delete','system/BackupDb/delete'),(20,7,'View','index','system/BackupApp/index'),(21,7,'Restore','restore','system/BackupApp/restore'),(22,7,'Detail','detail','system/BackupApp/detail'),(23,7,'Delete','delete','system/BackupApp/delete'),(24,8,'View','index','system/Config/index'),(25,8,'Update','update','system/Config/update'),(26,9,'View','index',NULL),(27,9,'Add','add',NULL),(28,9,'Update','update',NULL),(29,9,'Delete','delete',NULL),(30,6,'Download','download','system/BackupDb/Download'),(31,7,'Download','download','system/BackupApp/Download'),(32,6,'Backup','backup','system/BackupDb/backup'),(33,7,'Backup','backup','system/BackupApp/backup'),(128,3,'Ajax','ajax','system/Group/ajax'),(129,4,'Ajax','ajax','system/User/ajax');

/*Table structure for table `sys_notif` */

DROP TABLE IF EXISTS `sys_notif`;

CREATE TABLE `sys_notif` (
  `NotifId` int(11) NOT NULL AUTO_INCREMENT,
  `NotifTipe` enum('Files','Message','Person') DEFAULT NULL,
  `NotifTitle` varchar(250) DEFAULT NULL,
  `NotifContent` varchar(250) DEFAULT NULL,
  `NotifLink` varchar(250) DEFAULT NULL,
  `NotifNoUser` varchar(250) NOT NULL DEFAULT '',
  `NotifIsDone` enum('1','0') DEFAULT NULL,
  `NotifTime` datetime DEFAULT NULL,
  PRIMARY KEY (`NotifId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_notif` */

/*Table structure for table `sys_role` */

DROP TABLE IF EXISTS `sys_role`;

CREATE TABLE `sys_role` (
  `roleId` tinyint(4) NOT NULL DEFAULT '0',
  `roleNama` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `sys_role` */

insert  into `sys_role`(`roleId`,`roleNama`) values (0,'Normal');

/*Table structure for table `sys_sessions` */

DROP TABLE IF EXISTS `sys_sessions`;

CREATE TABLE `sys_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `sys_sessions` */

insert  into `sys_sessions`(`id`,`ip_address`,`timestamp`,`data`) values ('h1ud0ui0i1en5h1avgs6vopsmd7oo9k2','127.0.0.1',1500271365,'__ci_last_regenerate|i:1500271361;'),('t8imlr6vi0jmihmfcmqnt0s6ed7hsvrj','127.0.0.1',1502768255,'__ci_last_regenerate|i:1502768226;'),('t0rqsf8870h31jq1goher0vhuedp54s0','127.0.0.1',1503459702,'__ci_last_regenerate|i:1503459701;');

/*Table structure for table `sys_setting` */

DROP TABLE IF EXISTS `sys_setting`;

CREATE TABLE `sys_setting` (
  `SettingId` int(11) NOT NULL,
  `SettingCategoryId` int(11) NOT NULL,
  `SettingValue` varchar(255) DEFAULT NULL,
  `UserAddUserId` int(11) DEFAULT NULL,
  `UserAddTime` datetime DEFAULT NULL,
  `UserUpdateUserId` int(11) DEFAULT NULL,
  `UserUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`SettingId`),
  KEY `FK_sys_setting_user_add` (`UserAddUserId`),
  KEY `FK_sys_setting_user_update` (`UserUpdateUserId`),
  CONSTRAINT `FK_sys_setting_user_add` FOREIGN KEY (`UserAddUserId`) REFERENCES `sys_user` (`UserId`) ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_setting_user_update` FOREIGN KEY (`UserUpdateUserId`) REFERENCES `sys_user` (`UserId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_setting` */

/*Table structure for table `sys_setting_category` */

DROP TABLE IF EXISTS `sys_setting_category`;

CREATE TABLE `sys_setting_category` (
  `CategoryId` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(255) NOT NULL,
  `UserAddUserId` int(11) DEFAULT NULL,
  `UserAddTime` datetime DEFAULT NULL,
  `UserUpdateUserId` int(11) DEFAULT NULL,
  `UserUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`CategoryId`),
  KEY `FK_sys_setting_category_user_add` (`UserAddUserId`),
  KEY `FK_sys_setting_category_user_update` (`UserUpdateUserId`),
  CONSTRAINT `FK_sys_setting_category_user_add` FOREIGN KEY (`UserAddUserId`) REFERENCES `sys_user` (`UserId`) ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_setting_category_user_update` FOREIGN KEY (`UserUpdateUserId`) REFERENCES `sys_user` (`UserId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_setting_category` */

/*Table structure for table `sys_syncron` */

DROP TABLE IF EXISTS `sys_syncron`;

CREATE TABLE `sys_syncron` (
  `SyncronId` int(11) NOT NULL AUTO_INCREMENT,
  `SyncronTime` datetime DEFAULT NULL,
  `SyncronQuery` text,
  `SyncronIsDone` enum('0','1') DEFAULT '0',
  `SyncronUnitId` int(11) DEFAULT NULL,
  `SyncronUser` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`SyncronId`),
  KEY `NewIndex1` (`SyncronIsDone`,`SyncronUnitId`),
  KEY `FK_sys_syncron` (`SyncronUnitId`),
  CONSTRAINT `FK_sys_syncron_unt` FOREIGN KEY (`SyncronUnitId`) REFERENCES `sys_unit` (`UnitId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sys_syncron` */

/*Table structure for table `sys_unit` */

DROP TABLE IF EXISTS `sys_unit`;

CREATE TABLE `sys_unit` (
  `UnitId` int(11) NOT NULL AUTO_INCREMENT,
  `UnitKode` varchar(20) DEFAULT NULL,
  `UnitName` varchar(150) DEFAULT NULL,
  `UnitUrlApi` varchar(150) DEFAULT NULL,
  `UnitKeyApi` varchar(150) DEFAULT NULL,
  `UnitServiceAddress` varchar(255) DEFAULT NULL,
  `UnitIsHirarki` enum('Ya','Tidak') DEFAULT 'Tidak',
  `UnitDescription` varchar(255) DEFAULT NULL,
  `UnitAddUserId` int(11) DEFAULT NULL,
  `UnitAddTime` datetime DEFAULT NULL,
  `UnitUpdateUserId` int(11) DEFAULT NULL,
  `UnitUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`UnitId`),
  UNIQUE KEY `NewIndex1` (`UnitName`),
  KEY `NewIndex1s` (`UnitDescription`),
  KEY `FK_ci_unit_add_user` (`UnitAddUserId`),
  KEY `FK_ci_unit_update_user` (`UnitUpdateUserId`),
  CONSTRAINT `FK_sys_unit_user_add` FOREIGN KEY (`UnitAddUserId`) REFERENCES `sys_user` (`UserId`) ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_unit_user_update` FOREIGN KEY (`UnitUpdateUserId`) REFERENCES `sys_user` (`UserId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

/*Data for the table `sys_unit` */

insert  into `sys_unit`(`UnitId`,`UnitKode`,`UnitName`,`UnitUrlApi`,`UnitKeyApi`,`UnitServiceAddress`,`UnitIsHirarki`,`UnitDescription`,`UnitAddUserId`,`UnitAddTime`,`UnitUpdateUserId`,`UnitUpdateTime`) values (1,'1011001','FMIPA',NULL,NULL,'http://172.10.27.4/simak_mipa/portal_services/index.service.php','Ya','F.MIPA',1,'2013-07-04 10:42:48',1,'2013-07-04 10:42:48'),(2,'1011002','F.EKONOMI',NULL,NULL,'http://172.10.27.4/simak_ekonomi/portal_services/index.service.php','Ya','F.EKONOMI',NULL,NULL,NULL,NULL),(3,'1011003','F.FARMASI',NULL,NULL,'http://172.10.27.4/portal_services_demo/index.service.php','Ya','F.FARMASI',NULL,NULL,NULL,NULL),(4,'1011004','FKIP',NULL,NULL,'http://172.10.27.4/simak_fkip/portal_services/index.service.php','Ya','FKIP',NULL,NULL,NULL,NULL),(5,'1011005','FKM',NULL,NULL,'http://172.10.27.4/simak_fkm/portal_services/index.service.php','Ya','FKM',NULL,NULL,NULL,NULL),(6,'1011006','F.HUKUM',NULL,NULL,'http://172.10.27.4/simak_hukum/portal_services/index.service.php','Ya','F.HUKUM',NULL,NULL,NULL,NULL),(7,'1011007','FAI',NULL,NULL,'http://172.10.27.4/simak_fai/portal_services/index.service.php','Ya','FAI',NULL,NULL,NULL,NULL),(8,'1011008','F.PSIKOLOGI',NULL,NULL,'http://172.10.27.4/simak_psikologi/portal_services/index.service.php','Ya','F.PSIKOLOGI',NULL,NULL,NULL,NULL),(9,'1011009','F.SASTRA',NULL,NULL,'http://172.10.27.4/simak_sastra/portal_services/index.service.php','Ya','F.SASTRA',NULL,NULL,NULL,NULL),(10,'1011010','F.TEKNOLOGI INDUSTRI',NULL,NULL,'http://172.10.27.4/simak_teknik/portal_services/index.service.php','Ya','F.TEKNOLOGI INDUSTRI',NULL,NULL,NULL,NULL),(11,'1011011','PASCA',NULL,NULL,'http://172.10.27.4/simak_pasca/portal_services/index.service.php','Ya','PASCA',NULL,NULL,NULL,NULL),(12,'1011012','PASCA_FARMASI',NULL,NULL,'http://172.10.27.4/simak_pasca_farmasi/portal_services/index.service.php','Ya','PASCA_FARMASI',NULL,NULL,NULL,NULL),(13,'1011013','PASCA_PSIKOLOGI',NULL,NULL,'http://172.10.27.4/simak_pasca_psikologi/portal_services/index.service.php','Ya','PASCA_PSIKOLOGI',NULL,NULL,NULL,NULL),(14,'1021001','portal',NULL,NULL,'http://portal.uad.c.id/index.service.php','Tidak','portal',NULL,NULL,NULL,NULL),(15,'1031001','LPSI',NULL,NULL,'http://aik.uad.ac.id/service_demo/index.service.php','Tidak','LPSI',NULL,NULL,NULL,NULL),(16,'1031002','SKM',NULL,NULL,'http://skk.uad.ac.id/service/index.service.php','Tidak','SKM',NULL,NULL,NULL,NULL),(17,'1031003','BEASISWA',NULL,NULL,'http://biskom-uad.local/beasiswa/service/index.service.php','Tidak','BEASISWA',NULL,NULL,NULL,NULL),(18,'1031004','LPM','https://api.uad.ac.id/index.php?d=simkat','075531610493c4391a9da6c00c4599adf90f9589','http://simkat.uad.ac.id/service_demo/index.service.php','Tidak','LPM',NULL,NULL,NULL,NULL),(19,'1031005','SIMPUS',NULL,NULL,'http://172.10.27.9/service_simpus/index.service.php','Tidak','SIMPUS',NULL,NULL,NULL,NULL),(20,'1031006','LPP',NULL,NULL,'http://simpel.uad.ac.id/service_demo/index.service.php','Tidak','LPP',NULL,NULL,NULL,NULL),(21,'1031099','SDM','https://api.uad.ac.id/index.php?d=sdm','12a1f5642667e9326d49edce24c025b016c50eb7',NULL,'Tidak','SDM',NULL,NULL,NULL,NULL),(22,NULL,'SIMPOT','https://api.uad.ac.id/index.php?d=simpot','075531610493c4391a9da6c00c4599adf90f9589',NULL,'Tidak',NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `sys_user` */

DROP TABLE IF EXISTS `sys_user`;

CREATE TABLE `sys_user` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `UserRealName` varchar(250) DEFAULT NULL,
  `UserName` varchar(150) DEFAULT NULL,
  `UserEmail` varchar(150) DEFAULT NULL,
  `UserPassword` varchar(80) DEFAULT NULL,
  `UserSalt` varchar(10) DEFAULT NULL,
  `UserKey` varchar(100) DEFAULT NULL,
  `UserFoto` varchar(250) DEFAULT NULL,
  `UserIsActive` enum('1','0') DEFAULT '1',
  `UserUnitId` int(11) DEFAULT NULL,
  `UserRoleId` tinyint(4) DEFAULT NULL,
  `UserBanned` tinyint(1) DEFAULT '0',
  `UserBanText` text,
  `UserNewEmail` varchar(100) DEFAULT NULL,
  `UserNewEmailKey` varchar(50) DEFAULT NULL,
  `UserLastIp` varchar(45) DEFAULT NULL,
  `UserLastLogin` datetime DEFAULT NULL,
  `UserPassUpdatedUser` varchar(255) DEFAULT NULL,
  `UserPassUpdatedTime` datetime DEFAULT NULL,
  `UserAddUser` varchar(255) DEFAULT NULL,
  `UserAddTime` datetime DEFAULT NULL,
  `UserUpdateUser` varchar(255) DEFAULT NULL,
  `UserUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `NewIndex1` (`UserName`),
  KEY `FK_sys_userrole` (`UserRoleId`),
  KEY `FK_sys_user_unit` (`UserUnitId`),
  CONSTRAINT `FK_sys_userrole` FOREIGN KEY (`UserRoleId`) REFERENCES `sys_role` (`roleId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_sys_user_unit` FOREIGN KEY (`UserUnitId`) REFERENCES `sys_unit` (`UnitId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `sys_user` */

insert  into `sys_user`(`UserId`,`UserRealName`,`UserName`,`UserEmail`,`UserPassword`,`UserSalt`,`UserKey`,`UserFoto`,`UserIsActive`,`UserUnitId`,`UserRoleId`,`UserBanned`,`UserBanText`,`UserNewEmail`,`UserNewEmailKey`,`UserLastIp`,`UserLastLogin`,`UserPassUpdatedUser`,`UserPassUpdatedTime`,`UserAddUser`,`UserAddTime`,`UserUpdateUser`,`UserUpdateTime`) values (1,'Administrator','admin','admin@uad','20sqgUiJ81mJRLHPzgIgIU7qR8VkOWE3YjIzNGU4','d9a7b234e8','44408skwkkowsk08c4so8kw0kcw8kss0oog00c40','admin.png','1',NULL,NULL,0,NULL,NULL,NULL,'127.0.0.1','2017-07-17 06:28:50','admin','2016-02-03 03:24:30','root','2013-07-04 10:43:46','admin','2017-01-24 14:06:50');

/*Table structure for table `sys_user_autologin` */

DROP TABLE IF EXISTS `sys_user_autologin`;

CREATE TABLE `sys_user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`),
  KEY `FK_autologin_user` (`user_id`),
  CONSTRAINT `FK_autologin_user` FOREIGN KEY (`user_id`) REFERENCES `sys_user` (`UserId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `sys_user_autologin` */

insert  into `sys_user_autologin`(`key_id`,`user_id`,`user_agent`,`last_ip`,`last_login`) values ('0c4a2e62a0029eea9d67eeef657379a9',1,'Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0 FirePHP/0.7.4','127.0.0.1','2015-10-21 09:33:53'),('2a71f2fa3367d75c48c49902bc680b34',1,'Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0','127.0.0.1','2015-10-31 08:51:38'),('4d8d85a8c92d885e9fdfdc91152fe973',1,'Mozilla/5.0 (Windows NT 6.1; rv:40.0) Gecko/20100101 Firefox/40.0','127.0.0.1','2015-09-28 08:58:22'),('5f1a85463c188a9e0e828146e97d703f',1,'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36','::1','2016-11-24 10:05:36'),('edeaf72ff956b300752b156274af4278',1,'Mozilla/5.0 (Windows NT 6.1; rv:41.0) Gecko/20100101 Firefox/41.0','172.10.154.144','2015-10-27 08:12:41'),('ee4ad6d8aa3e123bc8f91a13f9446bef',1,'Mozilla/5.0 (Windows NT 6.1; rv:40.0) Gecko/20100101 Firefox/40.0 FirePHP/0.7.4','127.0.0.1','2015-09-21 11:44:30');

/*Table structure for table `sys_user_group` */

DROP TABLE IF EXISTS `sys_user_group`;

CREATE TABLE `sys_user_group` (
  `UserGroupId` int(11) NOT NULL AUTO_INCREMENT,
  `UserGroupUserId` int(11) NOT NULL,
  `UserGroupGroupId` int(11) NOT NULL,
  `UserGroupIsDefault` enum('Ya','Tidak') DEFAULT 'Tidak',
  PRIMARY KEY (`UserGroupId`),
  UNIQUE KEY `UNIQUE` (`UserGroupUserId`,`UserGroupGroupId`),
  KEY `FK_ci_user_group` (`UserGroupUserId`),
  KEY `FK_ci_user_groupa` (`UserGroupGroupId`),
  CONSTRAINT `FK_sys_user_group_group` FOREIGN KEY (`UserGroupGroupId`) REFERENCES `sys_group` (`GroupId`) ON DELETE CASCADE,
  CONSTRAINT `FK_sys_user_group_user` FOREIGN KEY (`UserGroupUserId`) REFERENCES `sys_user` (`UserId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

/*Data for the table `sys_user_group` */

insert  into `sys_user_group`(`UserGroupId`,`UserGroupUserId`,`UserGroupGroupId`,`UserGroupIsDefault`) values (47,1,0,'Ya');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
