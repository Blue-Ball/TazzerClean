
CREATE TABLE `request_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT 0,
  `subcategory_id` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '1:pending,2:inprogress,3:completed,4:rejected',
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
