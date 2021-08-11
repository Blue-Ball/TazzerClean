CREATE TABLE `organization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'users table id',
  `director_name` varchar(255) DEFAULT '' COMMENT 'name of Company director',
  `company_number` varchar(255) DEFAULT '' COMMENT 'Company registration number',
  `business_name` varchar(255) DEFAULT '' COMMENT 'Business name',
  `business_file` varchar(255) DEFAULT '' COMMENT 'file path : Method statement of business',
  `address` varchar(255) DEFAULT '' COMMENT 'Business address',
  `proof_id_file` varchar(255) DEFAULT '' COMMENT 'Proof of ID of the responsible individual',
  `brand` varchar(255) DEFAULT '' COMMENT 'logo or brand ID',
  `location` varchar(255) DEFAULT '',
  `country` varchar(255) DEFAULT '',
  `languages` varchar(255) DEFAULT '',
  `status` int(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `staffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organ_id` int(11) DEFAULT NULL COMMENT 'organization table id',
  `user_id` int(11) DEFAULT NULL COMMENT 'users table id',
  `status` int(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `organization_book_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_service_id` int(11) DEFAULT NULL COMMENT 'book_service table id',
  `staff_id` int(11) DEFAULT 0 COMMENT 'staffs table id',
  `status` int(2) DEFAULT 1 COMMENT '1:pending, 2:inprogress, 3:accept, 4:reject, 5:completed, 6:cancelled',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


