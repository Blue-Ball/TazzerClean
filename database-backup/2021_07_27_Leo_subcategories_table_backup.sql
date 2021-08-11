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

/*Table structure for table `subcategories` */

DROP TABLE IF EXISTS `subcategories`;

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `subcategory_name` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subcategory_image` varchar(500) DEFAULT NULL,
  `thumb_image` varchar(500) DEFAULT NULL,
  `status` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `subcategories` */

insert  into `subcategories`(`id`,`category`,`subcategory_name`,`subcategory_image`,`thumb_image`,`status`,`created_at`,`updated_on`) values 
(244,1,'Domestic Cleaning Normal','uploads/subcategory_images/1627394852domestic_cleaning_normal.jpg','uploads/subcategory_images/thu_1627398717domestic_cleaning_normal_50_50.jpg',1,'2021-05-12 23:57:21',NULL),
(246,2,'Clearance And Rubbish Removal','uploads/subcategory_images/1627395200Clearance_And_Rubbish_Removal.jpg','uploads/subcategory_images/thu_1627398723clearance_and_rubbish_removal_50_50.jpg',1,NULL,NULL),
(248,1,'Appliances Cleaning','uploads/subcategory_images/1627395494Appliances_Cleaning.jpg','uploads/subcategory_images/thu_1627398728appliances_cleaning_50_50.jpg',1,'2021-05-13 15:22:16',NULL),
(249,1,'End & Pre Tenancy/ Student accommodation','uploads/subcategory_images/1627395743End_and_Pre_Tenancy_or_Student_accommodation.jpg','uploads/subcategory_images/thu_1627398735end___pre_tenancy__student_accommodation_50_50.jpg',1,'2021-05-13 15:24:06',NULL),
(250,1,'Oven/BBQ Deep Clean','uploads/subcategory_images/1627395962Oven_and_BBQ_Deep_Clean.jpg','uploads/subcategory_images/thu_1627398740oven_bbq_deep_clean_50_50.jpg',1,'2021-05-13 18:36:27',NULL),
(251,1,'Window Cleaning','uploads/subcategory_images/1627396041Window_Cleaning.jpg','uploads/subcategory_images/thu_1627398746window_cleaning_50_50.jpg',1,'2021-05-13 19:22:16',NULL),
(252,1,'Upholstery and Furniture Cleaning','uploads/subcategory_images/1627396307Upholstery_and_Furniture_Cleaning.png','uploads/subcategory_images/thu_1627398751upholstery_and_furniture_cleaning_50_50.jpg',1,'2021-05-13 20:35:55',NULL),
(253,1,'Carpet and Rug Cleaning','uploads/subcategory_images/1627396349Carpet_and_Rug_Cleaning.jpg','uploads/subcategory_images/thu_1627398757carpet_and_rug_cleaning_50_50.jpg',1,'2021-05-13 20:40:13',NULL),
(254,1,'Commercial Cleaning','uploads/subcategory_images/1627396443Commercial_Cleaning.jpg','uploads/subcategory_images/thu_1627398762commercial_cleaning_50_50.jpg',1,'2021-05-13 23:11:44',NULL),
(255,1,'Industrial Cleaning','uploads/subcategory_images/1627396515Industrial_Cleaning.jpg','uploads/subcategory_images/thu_1627398768industrial_cleaning_50_50.jpg',1,'2021-05-13 23:53:26',NULL),
(256,1,'Office Cleaning','uploads/subcategory_images/1627396625Office_Cleaning.jpg','uploads/subcategory_images/thu_1627398775office_cleaning_50_50.jpg',1,'2021-05-14 01:50:20',NULL),
(257,1,'Building-Construction / After build Cleaning','uploads/subcategory_images/1627396681Building-Construction_and_After_build_Cleaning.jpg','uploads/subcategory_images/thu_1627398783building_construction___after_build_cleaning_50_50.jpg',1,'2021-05-14 01:54:48',NULL),
(258,1,'TRAIN AND BUS CLEANING','uploads/subcategory_images/1627396801TRAIN_AND_BUS_CLEANING.jpg','uploads/subcategory_images/thu_1627398789train_and_bus_cleaning_50_50.jpg',1,'2021-05-14 02:41:38',NULL),
(259,1,'School Cleaning','uploads/subcategory_images/1627398986School_Cleaning.jpg','uploads/subcategory_images/thu_1627398986School_Cleaning_50_50.jpg',1,'2021-05-14 03:14:40',NULL),
(260,1,'AIRCRAFT AND AIRPORT CLEANING','uploads/subcategory_images/1627399074AIRCRAFT_AND_AIRPORT_CLEANING.jpg','uploads/subcategory_images/thu_1627399074AIRCRAFT_AND_AIRPORT_CLEANING_50_50.jpg',1,'2021-05-14 03:52:15',NULL),
(261,1,'Medical cleaning services','uploads/subcategory_images/1627399189Medical_cleaning_services.jpg','uploads/subcategory_images/thu_1627399189Medical_cleaning_services_50_50.jpg',1,'2021-05-14 04:23:38',NULL),
(262,1,'Apartment / Block Maintenance','uploads/subcategory_images/1627399480Apartment_and_Block_Maintenance.jpg','uploads/subcategory_images/thu_1627399480Apartment_and_Block_Maintenance_50_50.jpg',1,'2021-05-14 05:39:24',NULL),
(263,1,'Biohazard /Specialized Cleaning','uploads/subcategory_images/1627399539Biohazard_and_Specialized_Cleaning.jpg','uploads/subcategory_images/thu_1627399539Biohazard_and_Specialized_Cleaning_50_50.jpg',1,'2021-05-14 10:26:21',NULL),
(264,1,'Car Washing & Valeting','uploads/subcategory_images/1627399594Car_Washing_Valeting.jpg','uploads/subcategory_images/thu_1627399594Car_Washing_Valeting_50_50.jpg',1,'2021-05-14 13:42:04',NULL),
(269,4,'Facilities Management','uploads/subcategory_images/1627399786Facilities_Management.jpg','uploads/subcategory_images/thu_1627399786Facilities_Management_50_50.jpg',1,'2021-05-14 20:53:43',NULL),
(270,6,'Gardening And Landscaping','uploads/subcategory_images/1627399910Gardening_And_Landscaping.jpg','uploads/subcategory_images/thu_1627399910Gardening_And_Landscaping_50_50.jpg',1,'2021-05-15 00:44:29',NULL),
(272,3,'Domestic helpers Service','uploads/subcategory_images/1627400005Domestic_helpers_Service.jpg','uploads/subcategory_images/thu_1627400005Domestic_helpers_Service_50_50.jpg',1,'2021-05-15 02:56:05',NULL),
(273,5,'Handyman Services','uploads/subcategory_images/1627400077Handyman_Services.jpg','uploads/subcategory_images/thu_1627400077Handyman_Services_50_50.jpg',1,'2021-05-15 03:52:37',NULL),
(277,1,'Domestic home Deep Clean','uploads/subcategory_images/1627400112Domestic_home_Deep_Clean.jpg','uploads/subcategory_images/thu_1627400112Domestic_home_Deep_Clean_50_50.jpg',1,'2021-05-20 04:06:41',NULL),
(282,1,'Boats and Marine cleaning','uploads/subcategory_images/1627400216Boats_and_Marine_cleaning.jpg','uploads/subcategory_images/thu_1627400216Boats_and_Marine_cleaning_50_50.jpg',1,'2021-07-05 08:46:36',NULL),
(283,6,'Gates, Railings & Fences','uploads/subcategory_images/1627400342Gates_and_Railings_Fences.jpg','uploads/subcategory_images/thu_1627400342Gates_and_Railings_Fences_50_50.jpg',1,'2021-07-05 15:27:51',NULL),
(284,14,'Dog walk and pet sitting','uploads/subcategory_images/1627400418Dog_walk_and_pet_sitting.jpg','uploads/subcategory_images/thu_1627400418Dog_walk_and_pet_sitting_50_50.jpg',1,'2021-07-05 15:31:57',NULL),
(285,12,'Scaffolding And Netting','uploads/subcategory_images/1627400463Scaffolding_And_Netting.jpg','uploads/subcategory_images/thu_1627400463Scaffolding_And_Netting_50_50.jpg',1,'2021-07-05 15:33:24',NULL),
(286,15,'Locksmith, Security & Fire Safety','uploads/subcategory_images/1627400572Locksmith,_Security_Fire_Safety.jpg','uploads/subcategory_images/thu_1627400572Locksmith,_Security_Fire_Safety_50_50.jpg',1,'2021-07-05 15:35:18',NULL),
(287,13,'Painters & Decorators','uploads/subcategory_images/1627400654Painters_Decorators.jpg','uploads/subcategory_images/thu_1627400654Painters_Decorators_50_50.jpg',1,'2021-07-05 15:36:36',NULL),
(288,13,'Pest Control Services','uploads/subcategory_images/1627400737Pest_Control_Services.jpg','uploads/subcategory_images/thu_1627400737Pest_Control_Services_50_50.jpg',1,'2021-07-05 15:37:53',NULL),
(289,13,'Plastering, Rendering & Insulation','uploads/subcategory_images/1627400785Plastering,_Rendering_Insulation.jpg','uploads/subcategory_images/thu_1627400785Plastering,_Rendering_Insulation_50_50.jpg',1,'2021-07-05 15:40:06',NULL),
(290,13,'Plumbing & Drainage','uploads/subcategory_images/1627400820Plumbing_Drainage.jpg','uploads/subcategory_images/thu_1627400820Plumbing_Drainage_50_50.jpg',1,'2021-07-05 15:41:11',NULL),
(291,13,'Removals & Property Management','uploads/subcategory_images/1627400907Removals_Property_Management.jpg','uploads/subcategory_images/thu_1627400907Removals_Property_Management_50_50.jpg',1,'2021-07-05 15:43:20',NULL),
(292,13,'Roofing & Guttering','uploads/subcategory_images/1627400964Roofing_Guttering.jpg','uploads/subcategory_images/thu_1627400964Roofing_Guttering_50_50.jpg',1,'2021-07-05 15:44:36',NULL),
(293,13,'Specialist Tradesmen','uploads/subcategory_images/1627401014Specialist_Tradesmen.jpg','uploads/subcategory_images/thu_1627401014Specialist_Tradesmen_50_50.jpg',1,'2021-07-05 15:45:53',NULL),
(294,13,'Swimming Pools & Hot Tubs','uploads/subcategory_images/1627401181Swimming_Pools_Hot_Tubs.png','uploads/subcategory_images/thu_1627401181Swimming_Pools_Hot_Tubs_50_50.png',1,'2021-07-05 15:47:21',NULL),
(295,13,'Tiling','uploads/subcategory_images/1627401221Tiling.jpg','uploads/subcategory_images/thu_1627401221Tiling_50_50.jpg',1,'2021-07-05 15:48:18',NULL),
(296,13,'Heating, Hot Water & Air Conditioning','uploads/subcategory_images/1627401303Heating,_Hot_Water_Air_Conditioning.jpg','uploads/subcategory_images/thu_1627401303Heating,_Hot_Water_Air_Conditioning_50_50.jpg',1,'2021-07-05 15:49:34',NULL),
(297,13,'Ironmongery & Blacksmiths','uploads/subcategory_images/1627401400Ironmongery_Blacksmiths.jpg','uploads/subcategory_images/thu_1627401400Ironmongery_Blacksmiths_50_50.jpg',1,'2021-07-05 15:50:18',NULL),
(298,13,'Kitchens','uploads/subcategory_images/1627401483Kitchens.jpg','uploads/subcategory_images/thu_1627401483Kitchens_50_50.jpg',1,'2021-07-05 15:51:00',NULL),
(299,13,'Driveways, Decking, Patios & Porches','uploads/subcategory_images/1627401546Driveways,_Decking,_Patios_Porches.jpg','uploads/subcategory_images/thu_1627401546Driveways,_Decking,_Patios_Porches_50_50.jpg',1,'2021-07-05 15:52:06',NULL),
(300,13,'Electrical & Lighting','uploads/subcategory_images/1627401613Electrical_Lighting.jpg','uploads/subcategory_images/thu_1627401613Electrical_Lighting_50_50.jpg',1,'2021-07-05 15:53:01',NULL),
(301,13,'Energy Efficient Home','uploads/subcategory_images/1627401648Energy_Efficient_Home.jpg','uploads/subcategory_images/thu_1627401648Energy_Efficient_Home_50_50.jpg',1,'2021-07-05 15:54:20',NULL),
(302,13,'Flooring','uploads/subcategory_images/1627401711Flooring.jpg','uploads/subcategory_images/thu_1627401711Flooring_50_50.jpg',1,'2021-07-05 15:55:27',NULL),
(303,1,'Birds and Pest Control','uploads/subcategory_images/1627401772Birds_and_Pest_Control.jpg','uploads/subcategory_images/thu_1627401772Birds_and_Pest_Control_50_50.jpg',1,'2021-07-06 23:48:19',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
