CREATE TABLE `developer_data_work`.`call_room` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `room_identifier` VARCHAR(40) NOT NULL, `send_user_id` INT UNSIGNED NOT NULL, `recv_user_id` INT NOT NULL, `status` TINYINT NOT NULL DEFAULT 0 COMMENT '0-call,1-calling,2-canceled 3-rejected, 4> end', `time_millisecond` INT NOT NULL DEFAULT 0, `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `update_at` DATETIME, PRIMARY KEY (`id`) ) CHARSET=utf8; 
ALTER TABLE `developer_data_work`.`call_room` ADD COLUMN `call_type` TINYINT DEFAULT 1 NOT NULL COMMENT '1-video, 2-voice' AFTER `room_identifier`; 
ALTER TABLE `developer_data_work`.`call_room` ADD COLUMN `user_type` TINYINT DEFAULT 1 NOT NULL COMMENT '1-users, 2-administrators, 3-providers' AFTER `room_identifier`; 

CREATE TABLE `cron` (
	`id` INT(5) NOT NULL,
	`name` VARCHAR(100) DEFAULT NULL,
	`command` VARCHAR(255) NOT NULL,
	`interval_sec` INT(10) NOT NULL,
	`last_run_at` DATETIME DEFAULT NULL,
	`next_run_at` DATETIME DEFAULT NULL,
	`is_active` TINYINT(1) NOT NULL DEFAULT '1'
	) ENGINE=INNODB DEFAULT CHARSET=utf8;
ALTER TABLE `developer_data_work`.`cron` CHANGE `id` `id` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT, ADD KEY(`id`); 