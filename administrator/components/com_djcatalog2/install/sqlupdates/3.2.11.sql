ALTER TABLE `#__djc2_images` 
ADD `path` VARCHAR( 255 ) NULL AFTER `ext`,
ADD `fullpath` VARCHAR( 255 ) NULL AFTER `path`;

UPDATE `#__djc2_images` SET fullpath = fullname WHERE path is NULL;

UPDATE `#__djc2_images` SET fullpath = CONCAT(path, '/', fullname) WHERE path is NOT NULL;

ALTER TABLE `#__djc2_files` 
ADD `path` VARCHAR( 255 ) NULL AFTER `ext`,
ADD `fullpath` VARCHAR( 255 ) NULL AFTER `path`;

UPDATE `#__djc2_files` SET fullpath = fullname WHERE path is NULL;

UPDATE `#__djc2_files` SET fullpath = CONCAT(path, '/', fullname) WHERE path is NOT NULL;

ALTER TABLE `#__djc2_items` 
ADD `special_price` decimal(12,2) DEFAULT NULL AFTER `price`; 
