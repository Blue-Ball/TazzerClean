CREATE TABLE `faqs` (  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category` INT(11) NOT NULL,
  `question` VARCHAR(500) NOT NULL,
  `answer` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `total_view` INT(11) NOT NULL,
  PRIMARY KEY (`id`) 
) CHARSET=utf8 COLLATE=utf8_general_ci;