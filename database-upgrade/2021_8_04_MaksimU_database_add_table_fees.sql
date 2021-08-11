
CREATE TABLE `fees` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `type` int(5) DEFAULT 0,
  `fee` double(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;

INSERT INTO `developer_data_work`.`fees` (`name`, `type`, `fee`) VALUES ('Transaction Fee', '1', '2.00'); 
INSERT INTO `developer_data_work`.`fees` (`name`, `type`, `fee`) VALUES ('Sales Fee', '2', '3.00'); 
INSERT INTO `developer_data_work`.`fees` (`name`, `type`, `fee`) VALUES ('Commission', '3', '5.00'); 

