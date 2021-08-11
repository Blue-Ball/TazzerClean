ALTER TABLE `services`   
  CHANGE `serviceamounttype` `serviceamounttype` ENUM('Fixed','Hourly','Monthly') CHARSET latin1 COLLATE latin1_swedish_ci DEFAULT 'Fixed' NOT NULL,
  ADD COLUMN `measurement_value` FLOAT NOT NULL AFTER `serviceamounttype`,
  ADD COLUMN `measurement_type` ENUM('none','per sqft','tones','cm','mm','height','length','width') NOT NULL AFTER `measurement_value`;

ALTER TABLE `services`   
  DROP COLUMN `measurement_value`, 
  CHANGE `measurement_type` `measurement` ENUM('none','per sqft','tones','cm','mm','height','length','width') CHARSET utf8 COLLATE utf8_general_ci DEFAULT 'none' NOT NULL;