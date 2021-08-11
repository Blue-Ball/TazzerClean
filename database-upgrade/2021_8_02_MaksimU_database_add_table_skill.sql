
CREATE TABLE `user_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('cleaner'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('cleaning'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('cooking'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('gardening'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('pinter'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('electrician'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('carpenter'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('plumber'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('joiner'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('cook'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('domestic cleaner'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('commercial cleaner'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('industrial cleaner'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('handyman'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('maid'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('dog walker'); 
INSERT INTO `developer_data_work`.`user_skills` (`name`) VALUES ('house keeper'); 

CREATE TABLE `partner_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Comparison site'); 
INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Referral site'); 
INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Supermarket'); 
INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Shop'); 
INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Restaurant'); 
INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Shipping company'); 
INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Post office'); 
INSERT INTO `developer_data_work`.`partner_department` (`name`) VALUES ('Freight company'); 

CREATE TABLE `user_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT 0,
  `skill_id` int(11) DEFAULT 0,
  `department_id` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 

