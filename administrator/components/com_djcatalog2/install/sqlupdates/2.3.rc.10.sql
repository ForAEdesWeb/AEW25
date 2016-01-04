ALTER TABLE `#__djc2_items` ADD `metatitle` VARCHAR( 255 ) NULL AFTER `price` ;

ALTER TABLE `#__djc2_categories` ADD `metatitle` VARCHAR( 255 ) NULL AFTER `parent_id` ;

ALTER TABLE `#__djc2_producers` ADD `metatitle` VARCHAR( 255 ) NULL AFTER `description` ;