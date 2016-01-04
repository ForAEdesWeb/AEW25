ALTER TABLE `#__djc2_images` ADD INDEX ( `type` );

ALTER TABLE `#__djc2_images` ADD INDEX ( `item_id` );

ALTER TABLE `#__djc2_files` ADD INDEX ( `type` );

ALTER TABLE `#__djc2_files` ADD INDEX ( `item_id` );

ALTER TABLE `#__djc2_items_categories` ADD PRIMARY KEY ( `category_id` , `item_id` );

ALTER TABLE `#__djc2_items_categories` ADD UNIQUE `item_category` ( `item_id` , `category_id` ); 

ALTER TABLE `#__djc2_items_related` ADD PRIMARY KEY ( `related_item` , `item_id` );

ALTER TABLE `#__djc2_items_related` ADD UNIQUE `item_related` ( `item_id` , `related_item` );

CREATE TABLE IF NOT EXISTS `#__djc2_items_extra_fields_values_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` date NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

ALTER TABLE `#__djc2_items_extra_fields_values_date` ADD UNIQUE `item_field` ( `item_id` , `field_id` );

ALTER TABLE `#__djc2_items_extra_fields_values_int` ADD INDEX `item_field` ( `item_id` , `field_id` );

ALTER TABLE `#__djc2_items_extra_fields_values_text` ADD UNIQUE `item_field` ( `item_id` , `field_id` );
