/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.4.8-MariaDB : Database - developer_data_work
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`developer_data_work` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `developer_data_work`;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(100) NOT NULL,
  `category_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) NOT NULL,
  `icon` varchar(225) NOT NULL,
  `category_image` varchar(500) NOT NULL,
  `thumb_image` varchar(225) NOT NULL,
  `category_mobile_icon` varchar(225) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `categories` */

insert  into `categories`(`id`,`unique_id`,`category_name`,`description`,`icon`,`category_image`,`thumb_image`,`category_mobile_icon`,`status`,`created_at`) values 
(1,'','Cleaning Services','The objective of cleaning is not just to clean, but to feel happiness living within that environment.','assets/img/services/icon1.png','uploads/category_images/1627403506banner1.jpg','uploads/category_images/thu_1627403506banner1_50_50.jpg','',1,'2021-05-09 13:11:45'),
(2,'','Clearance And Rubbish Removal Services','Put your office, home, and garage clearance in your hands. We also do removal of rubbish & goods as well as transfers of all types.','assets/img/services/icon2.png','uploads/category_images/1627403535banner2.png','uploads/category_images/thu_1627403535banner2_50_50.png','',1,'2021-05-09 13:12:40'),
(3,'','Domestic Helpers Services ','This service would support you with lots of things around your home such as shopping, completing paperwork & many others.','assets/img/services/icon3.png','uploads/category_images/1627403570banner3.jpg','uploads/category_images/thu_1627403570banner3_50_50.jpg','',1,'2021-05-09 13:13:52'),
(4,'','Property And Facilities Management Services','Every Day we help many different people with many different things.','assets/img/services/icon4.png','uploads/category_images/1627403604banner4.jpg','uploads/category_images/thu_1627403604banner4_50_50.jpg','',1,'2021-05-09 13:22:44'),
(5,'','Handyman Services','Anything broke we can fix it , No job is too big.','assets/img/services/icon6.png','uploads/category_images/1627403628banner5.png','uploads/category_images/thu_1627403628banner5_50_50.png','',1,'2021-05-09 16:19:39'),
(6,'','Gardening And Landscaping Services','Maintenance comes with the job of gardening','assets/img/services/icon5.png','uploads/category_images/1627403654banner6.png','uploads/category_images/thu_1627403654banner6_50_50.png','',1,'2021-05-09 16:20:09'),
(12,'','Scaffolding And Netting Services','We offer both commercial & Private services therefore do not hesitate to give us a call or book through our platform.','assets/img/services/icon8.png','uploads/category_images/1627403684banner12.png','uploads/category_images/thu_1627403684banner12_50_50.png','',1,'2021-07-05 07:06:39'),
(13,'','Construction And Builders Services','Put any of your construction work into the hand of our tradesmen and women and you won\'t be disappointed.','assets/img/services/icon10.png','uploads/category_images/1627403709banner13.jpg','uploads/category_images/thu_1627403709banner13_50_50.jpg','',1,'2021-07-05 07:10:04'),
(14,'','Dog Walking And Pet Services','You don’t need to strain or worry about your pets anymore just get to our platform & look for someone to help you with it.','assets/img/services/icon7.png','uploads/category_images/1627404474banner14.jpg','uploads/category_images/thu_1627404474banner14_50_50.jpg','',1,'2021-07-05 07:13:50'),
(15,'','Security Services','Our security men and women are well trained & know our customer expectations as well. we also cover Locksmith & Fire Safety.','assets/img/services/icon9.png','uploads/category_images/1627403743banner15.jpg','uploads/category_images/thu_1627403743banner15_50_50.jpg','',1,'2021-07-05 07:25:08');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
