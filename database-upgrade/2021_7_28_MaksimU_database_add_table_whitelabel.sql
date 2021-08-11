CREATE TABLE `whitelabel` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL, `brandname` VARCHAR(255), `country` VARCHAR(255), `logofile` VARCHAR(255), `color` VARCHAR(40), `hostaddress` VARCHAR(255), PRIMARY KEY (`id`) ) CHARSET=utf8; 

ALTER TABLE `whitelabel` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL AFTER `hostaddress`, ADD COLUMN `updated_at` DATETIME NULL AFTER `created_at`; 

ALTER TABLE `whitelabel` ADD COLUMN `favicon` VARCHAR(255) NULL AFTER `logofile`; 

ALTER TABLE `whitelabel` ADD COLUMN `status` TINYINT DEFAULT 1 NOT NULL AFTER `hostaddress`; 


CREATE TABLE `whitelalbel_categories` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `whila_id` INT UNSIGNED NOT NULL, `categ_id` INT, `subcate_id` INT, `status` TINYINT NOT NULL DEFAULT 1, `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `updated_at` DATETIME, PRIMARY KEY (`id`) ) CHARSET=utf8; 