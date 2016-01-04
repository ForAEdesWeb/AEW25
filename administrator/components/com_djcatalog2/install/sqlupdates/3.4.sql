ALTER TABLE `#__djc2_items` ADD `hits` INT NOT NULL DEFAULT '0' AFTER `featured`;

ALTER TABLE `#__djc2_items` 
ADD `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE `#__djc2_items` ADD `tax_rate_id` INT NOT NULL AFTER `special_price`;

ALTER TABLE `#__djc2_items` ADD `available` INT NOT NULL DEFAULT '0' AFTER `tax_rate_id`;

ALTER TABLE `#__djc2_items_extra_fields` ADD `separate_column` int(11) NOT NULL DEFAULT '0' AFTER `visibility`;

ALTER TABLE `#__djc2_items_extra_fields` ADD `sortable` SMALLINT NOT NULL DEFAULT '0';

ALTER TABLE `#__djc2_images` ADD `exclude` TINYINT NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `#__djc2_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `client_type` char(1) NOT NULL DEFAULT 'A',
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `company` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `vat_id` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `www` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_id` (`user_id`),
  KEY `idx_customer_group_id` (`customer_group_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djc2_countries` (
  `id` smallint(1) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` char(64) DEFAULT NULL,
  `country_3_code` char(3) DEFAULT NULL,
  `country_2_code` char(2) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `is_eu` smallint(6) NOT NULL,
  `is_default` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_country_3_code` (`country_3_code`),
  KEY `idx_country_2_code` (`country_2_code`)
) DEFAULT CHARSET=utf8;

INSERT INTO `#__djc2_countries` (`id`, `country_name`, `country_3_code`, `country_2_code`, `published`, `is_eu`, `is_default`) VALUES
(1, 'Afghanistan', 'AFG', 'AF', 1, 0, 0),
(2, 'Albania', 'ALB', 'AL', 1, 0, 0),
(3, 'Algeria', 'DZA', 'DZ', 1, 0, 0),
(4, 'American Samoa', 'ASM', 'AS', 1, 0, 0),
(5, 'Andorra', 'AND', 'AD', 1, 0, 0),
(6, 'Angola', 'AGO', 'AO', 1, 0, 0),
(7, 'Anguilla', 'AIA', 'AI', 1, 0, 0),
(8, 'Antarctica', 'ATA', 'AQ', 1, 0, 0),
(9, 'Antigua and Barbuda', 'ATG', 'AG', 1, 0, 0),
(10, 'Argentina', 'ARG', 'AR', 1, 0, 0),
(11, 'Armenia', 'ARM', 'AM', 1, 0, 0),
(12, 'Aruba', 'ABW', 'AW', 1, 0, 0),
(13, 'Australia', 'AUS', 'AU', 1, 0, 0),
(14, 'Austria', 'AUT', 'AT', 1, 1, 0),
(15, 'Azerbaijan', 'AZE', 'AZ', 1, 0, 0),
(16, 'Bahamas', 'BHS', 'BS', 1, 0, 0),
(17, 'Bahrain', 'BHR', 'BH', 1, 0, 0),
(18, 'Bangladesh', 'BGD', 'BD', 1, 0, 0),
(19, 'Barbados', 'BRB', 'BB', 1, 0, 0),
(20, 'Belarus', 'BLR', 'BY', 1, 0, 0),
(21, 'Belgium', 'BEL', 'BE', 1, 1, 0),
(22, 'Belize', 'BLZ', 'BZ', 1, 0, 0),
(23, 'Benin', 'BEN', 'BJ', 1, 0, 0),
(24, 'Bermuda', 'BMU', 'BM', 1, 0, 0),
(25, 'Bhutan', 'BTN', 'BT', 1, 0, 0),
(26, 'Bolivia', 'BOL', 'BO', 1, 0, 0),
(27, 'Bosnia and Herzegowina', 'BIH', 'BA', 1, 0, 0),
(28, 'Botswana', 'BWA', 'BW', 1, 0, 0),
(29, 'Bouvet Island', 'BVT', 'BV', 1, 0, 0),
(30, 'Brazil', 'BRA', 'BR', 1, 0, 0),
(31, 'British Indian Ocean Territory', 'IOT', 'IO', 1, 0, 0),
(32, 'Brunei Darussalam', 'BRN', 'BN', 1, 0, 0),
(33, 'Bulgaria', 'BGR', 'BG', 1, 1, 0),
(34, 'Burkina Faso', 'BFA', 'BF', 1, 0, 0),
(35, 'Burundi', 'BDI', 'BI', 1, 0, 0),
(36, 'Cambodia', 'KHM', 'KH', 1, 0, 0),
(37, 'Cameroon', 'CMR', 'CM', 1, 0, 0),
(38, 'Canada', 'CAN', 'CA', 1, 0, 0),
(39, 'Cape Verde', 'CPV', 'CV', 1, 0, 0),
(40, 'Cayman Islands', 'CYM', 'KY', 1, 0, 0),
(41, 'Central African Republic', 'CAF', 'CF', 1, 0, 0),
(42, 'Chad', 'TCD', 'TD', 1, 0, 0),
(43, 'Chile', 'CHL', 'CL', 1, 0, 0),
(44, 'China', 'CHN', 'CN', 1, 0, 0),
(45, 'Christmas Island', 'CXR', 'CX', 1, 0, 0),
(46, 'Cocos (Keeling) Islands', 'CCK', 'CC', 1, 0, 0),
(47, 'Colombia', 'COL', 'CO', 1, 0, 0),
(48, 'Comoros', 'COM', 'KM', 1, 0, 0),
(49, 'Congo', 'COG', 'CG', 1, 0, 0),
(50, 'Cook Islands', 'COK', 'CK', 1, 0, 0),
(51, 'Costa Rica', 'CRI', 'CR', 1, 0, 0),
(52, 'Cote D''Ivoire', 'CIV', 'CI', 1, 0, 0),
(53, 'Croatia', 'HRV', 'HR', 1, 0, 0),
(54, 'Cuba', 'CUB', 'CU', 1, 0, 0),
(55, 'Cyprus', 'CYP', 'CY', 1, 1, 0),
(56, 'Czech Republic', 'CZE', 'CZ', 1, 1, 0),
(57, 'Denmark', 'DNK', 'DK', 1, 1, 0),
(58, 'Djibouti', 'DJI', 'DJ', 1, 0, 0),
(59, 'Dominica', 'DMA', 'DM', 1, 0, 0),
(60, 'Dominican Republic', 'DOM', 'DO', 1, 0, 0),
(61, 'East Timor', 'TMP', 'TP', 1, 0, 0),
(62, 'Ecuador', 'ECU', 'EC', 1, 0, 0),
(63, 'Egypt', 'EGY', 'EG', 1, 0, 0),
(64, 'El Salvador', 'SLV', 'SV', 1, 0, 0),
(65, 'Equatorial Guinea', 'GNQ', 'GQ', 1, 0, 0),
(66, 'Eritrea', 'ERI', 'ER', 1, 0, 0),
(67, 'Estonia', 'EST', 'EE', 1, 1, 0),
(68, 'Ethiopia', 'ETH', 'ET', 1, 0, 0),
(69, 'Falkland Islands (Malvinas)', 'FLK', 'FK', 1, 0, 0),
(70, 'Faroe Islands', 'FRO', 'FO', 1, 0, 0),
(71, 'Fiji', 'FJI', 'FJ', 1, 0, 0),
(72, 'Finland', 'FIN', 'FI', 1, 1, 0),
(73, 'France', 'FRA', 'FR', 1, 1, 0),
(75, 'French Guiana', 'GUF', 'GF', 1, 0, 0),
(76, 'French Polynesia', 'PYF', 'PF', 1, 0, 0),
(77, 'French Southern Territories', 'ATF', 'TF', 1, 0, 0),
(78, 'Gabon', 'GAB', 'GA', 1, 0, 0),
(79, 'Gambia', 'GMB', 'GM', 1, 0, 0),
(80, 'Georgia', 'GEO', 'GE', 1, 0, 0),
(81, 'Germany', 'DEU', 'DE', 1, 1, 0),
(82, 'Ghana', 'GHA', 'GH', 1, 0, 0),
(83, 'Gibraltar', 'GIB', 'GI', 1, 0, 0),
(84, 'Greece', 'GRC', 'GR', 1, 1, 0),
(85, 'Greenland', 'GRL', 'GL', 1, 0, 0),
(86, 'Grenada', 'GRD', 'GD', 1, 0, 0),
(87, 'Guadeloupe', 'GLP', 'GP', 1, 0, 0),
(88, 'Guam', 'GUM', 'GU', 1, 0, 0),
(89, 'Guatemala', 'GTM', 'GT', 1, 0, 0),
(90, 'Guinea', 'GIN', 'GN', 1, 0, 0),
(91, 'Guinea-bissau', 'GNB', 'GW', 1, 0, 0),
(92, 'Guyana', 'GUY', 'GY', 1, 0, 0),
(93, 'Haiti', 'HTI', 'HT', 1, 0, 0),
(94, 'Heard and Mc Donald Islands', 'HMD', 'HM', 1, 0, 0),
(95, 'Honduras', 'HND', 'HN', 1, 0, 0),
(96, 'Hong Kong', 'HKG', 'HK', 1, 0, 0),
(97, 'Hungary', 'HUN', 'HU', 1, 1, 0),
(98, 'Iceland', 'ISL', 'IS', 1, 0, 0),
(99, 'India', 'IND', 'IN', 1, 0, 0),
(100, 'Indonesia', 'IDN', 'ID', 1, 0, 0),
(101, 'Iran (Islamic Republic of)', 'IRN', 'IR', 1, 0, 0),
(102, 'Iraq', 'IRQ', 'IQ', 1, 0, 0),
(103, 'Ireland', 'IRL', 'IE', 1, 1, 0),
(104, 'Israel', 'ISR', 'IL', 1, 0, 0),
(105, 'Italy', 'ITA', 'IT', 1, 1, 0),
(106, 'Jamaica', 'JAM', 'JM', 1, 0, 0),
(107, 'Japan', 'JPN', 'JP', 1, 0, 0),
(108, 'Jordan', 'JOR', 'JO', 1, 0, 0),
(109, 'Kazakhstan', 'KAZ', 'KZ', 1, 0, 0),
(110, 'Kenya', 'KEN', 'KE', 1, 0, 0),
(111, 'Kiribati', 'KIR', 'KI', 1, 0, 0),
(112, 'Korea, Democratic People''s Republic of', 'PRK', 'KP', 1, 0, 0),
(113, 'Korea, Republic of', 'KOR', 'KR', 1, 0, 0),
(114, 'Kuwait', 'KWT', 'KW', 1, 0, 0),
(115, 'Kyrgyzstan', 'KGZ', 'KG', 1, 0, 0),
(116, 'Lao People''s Democratic Republic', 'LAO', 'LA', 1, 0, 0),
(117, 'Latvia', 'LVA', 'LV', 1, 1, 0),
(118, 'Lebanon', 'LBN', 'LB', 1, 0, 0),
(119, 'Lesotho', 'LSO', 'LS', 1, 0, 0),
(120, 'Liberia', 'LBR', 'LR', 1, 0, 0),
(121, 'Libya', 'LBY', 'LY', 1, 0, 0),
(122, 'Liechtenstein', 'LIE', 'LI', 1, 0, 0),
(123, 'Lithuania', 'LTU', 'LT', 1, 1, 0),
(124, 'Luxembourg', 'LUX', 'LU', 1, 1, 0),
(125, 'Macau', 'MAC', 'MO', 1, 0, 0),
(126, 'Macedonia, The Former Yugoslav Republic of', 'MKD', 'MK', 1, 0, 0),
(127, 'Madagascar', 'MDG', 'MG', 1, 0, 0),
(128, 'Malawi', 'MWI', 'MW', 1, 0, 0),
(129, 'Malaysia', 'MYS', 'MY', 1, 0, 0),
(130, 'Maldives', 'MDV', 'MV', 1, 0, 0),
(131, 'Mali', 'MLI', 'ML', 1, 0, 0),
(132, 'Malta', 'MLT', 'MT', 1, 1, 0),
(133, 'Marshall Islands', 'MHL', 'MH', 1, 0, 0),
(134, 'Martinique', 'MTQ', 'MQ', 1, 0, 0),
(135, 'Mauritania', 'MRT', 'MR', 1, 0, 0),
(136, 'Mauritius', 'MUS', 'MU', 1, 0, 0),
(137, 'Mayotte', 'MYT', 'YT', 1, 0, 0),
(138, 'Mexico', 'MEX', 'MX', 1, 0, 0),
(139, 'Micronesia, Federated States of', 'FSM', 'FM', 1, 0, 0),
(140, 'Moldova, Republic of', 'MDA', 'MD', 1, 0, 0),
(141, 'Monaco', 'MCO', 'MC', 1, 0, 0),
(142, 'Mongolia', 'MNG', 'MN', 1, 0, 0),
(143, 'Montserrat', 'MSR', 'MS', 1, 0, 0),
(144, 'Morocco', 'MAR', 'MA', 1, 0, 0),
(145, 'Mozambique', 'MOZ', 'MZ', 1, 0, 0),
(146, 'Myanmar', 'MMR', 'MM', 1, 0, 0),
(147, 'Namibia', 'NAM', 'NA', 1, 0, 0),
(148, 'Nauru', 'NRU', 'NR', 1, 0, 0),
(149, 'Nepal', 'NPL', 'NP', 1, 0, 0),
(150, 'Netherlands', 'NLD', 'NL', 1, 1, 0),
(151, 'Netherlands Antilles', 'ANT', 'AN', 1, 0, 0),
(152, 'New Caledonia', 'NCL', 'NC', 1, 0, 0),
(153, 'New Zealand', 'NZL', 'NZ', 1, 0, 0),
(154, 'Nicaragua', 'NIC', 'NI', 1, 0, 0),
(155, 'Niger', 'NER', 'NE', 1, 0, 0),
(156, 'Nigeria', 'NGA', 'NG', 1, 0, 0),
(157, 'Niue', 'NIU', 'NU', 1, 0, 0),
(158, 'Norfolk Island', 'NFK', 'NF', 1, 0, 0),
(159, 'Northern Mariana Islands', 'MNP', 'MP', 1, 0, 0),
(160, 'Norway', 'NOR', 'NO', 1, 0, 0),
(161, 'Oman', 'OMN', 'OM', 1, 0, 0),
(162, 'Pakistan', 'PAK', 'PK', 1, 0, 0),
(163, 'Palau', 'PLW', 'PW', 1, 0, 0),
(164, 'Panama', 'PAN', 'PA', 1, 0, 0),
(165, 'Papua New Guinea', 'PNG', 'PG', 1, 0, 0),
(166, 'Paraguay', 'PRY', 'PY', 1, 0, 0),
(167, 'Peru', 'PER', 'PE', 1, 0, 0),
(168, 'Philippines', 'PHL', 'PH', 1, 0, 0),
(169, 'Pitcairn', 'PCN', 'PN', 1, 0, 0),
(170, 'Poland', 'POL', 'PL', 1, 1, 0),
(171, 'Portugal', 'PRT', 'PT', 1, 1, 0),
(172, 'Puerto Rico', 'PRI', 'PR', 1, 0, 0),
(173, 'Qatar', 'QAT', 'QA', 1, 0, 0),
(174, 'Reunion', 'REU', 'RE', 1, 0, 0),
(175, 'Romania', 'ROM', 'RO', 1, 1, 0),
(176, 'Russian Federation', 'RUS', 'RU', 1, 0, 0),
(177, 'Rwanda', 'RWA', 'RW', 1, 0, 0),
(178, 'Saint Kitts and Nevis', 'KNA', 'KN', 1, 0, 0),
(179, 'Saint Lucia', 'LCA', 'LC', 1, 0, 0),
(180, 'Saint Vincent and the Grenadines', 'VCT', 'VC', 1, 0, 0),
(181, 'Samoa', 'WSM', 'WS', 1, 0, 0),
(182, 'San Marino', 'SMR', 'SM', 1, 0, 0),
(183, 'Sao Tome and Principe', 'STP', 'ST', 1, 0, 0),
(184, 'Saudi Arabia', 'SAU', 'SA', 1, 0, 0),
(185, 'Senegal', 'SEN', 'SN', 1, 0, 0),
(186, 'Seychelles', 'SYC', 'SC', 1, 0, 0),
(187, 'Sierra Leone', 'SLE', 'SL', 1, 0, 0),
(188, 'Singapore', 'SGP', 'SG', 1, 0, 0),
(189, 'Slovakia', 'SVK', 'SK', 1, 1, 0),
(190, 'Slovenia', 'SVN', 'SI', 1, 1, 0),
(191, 'Solomon Islands', 'SLB', 'SB', 1, 0, 0),
(192, 'Somalia', 'SOM', 'SO', 1, 0, 0),
(193, 'South Africa', 'ZAF', 'ZA', 1, 0, 0),
(194, 'South Georgia and the South Sandwich Islands', 'SGS', 'GS', 1, 0, 0),
(195, 'Spain', 'ESP', 'ES', 1, 1, 0),
(196, 'Sri Lanka', 'LKA', 'LK', 1, 0, 0),
(197, 'St. Helena', 'SHN', 'SH', 1, 0, 0),
(198, 'St. Pierre and Miquelon', 'SPM', 'PM', 1, 0, 0),
(199, 'Sudan', 'SDN', 'SD', 1, 0, 0),
(200, 'Suriname', 'SUR', 'SR', 1, 0, 0),
(201, 'Svalbard and Jan Mayen Islands', 'SJM', 'SJ', 1, 0, 0),
(202, 'Swaziland', 'SWZ', 'SZ', 1, 0, 0),
(203, 'Sweden', 'SWE', 'SE', 1, 1, 0),
(204, 'Switzerland', 'CHE', 'CH', 1, 0, 0),
(205, 'Syrian Arab Republic', 'SYR', 'SY', 1, 0, 0),
(206, 'Taiwan', 'TWN', 'TW', 1, 0, 0),
(207, 'Tajikistan', 'TJK', 'TJ', 1, 0, 0),
(208, 'Tanzania, United Republic of', 'TZA', 'TZ', 1, 0, 0),
(209, 'Thailand', 'THA', 'TH', 1, 0, 0),
(210, 'Togo', 'TGO', 'TG', 1, 0, 0),
(211, 'Tokelau', 'TKL', 'TK', 1, 0, 0),
(212, 'Tonga', 'TON', 'TO', 1, 0, 0),
(213, 'Trinidad and Tobago', 'TTO', 'TT', 1, 0, 0),
(214, 'Tunisia', 'TUN', 'TN', 1, 0, 0),
(215, 'Turkey', 'TUR', 'TR', 1, 0, 0),
(216, 'Turkmenistan', 'TKM', 'TM', 1, 0, 0),
(217, 'Turks and Caicos Islands', 'TCA', 'TC', 1, 0, 0),
(218, 'Tuvalu', 'TUV', 'TV', 1, 0, 0),
(219, 'Uganda', 'UGA', 'UG', 1, 0, 0),
(220, 'Ukraine', 'UKR', 'UA', 1, 0, 0),
(221, 'United Arab Emirates', 'ARE', 'AE', 1, 0, 0),
(222, 'United Kingdom', 'GBR', 'GB', 1, 1, 0),
(223, 'United States', 'USA', 'US', 1, 0, 0),
(224, 'United States Minor Outlying Islands', 'UMI', 'UM', 1, 0, 0),
(225, 'Uruguay', 'URY', 'UY', 1, 0, 0),
(226, 'Uzbekistan', 'UZB', 'UZ', 1, 0, 0),
(227, 'Vanuatu', 'VUT', 'VU', 1, 0, 0),
(228, 'Vatican City State (Holy See)', 'VAT', 'VA', 1, 0, 0),
(229, 'Venezuela', 'VEN', 'VE', 1, 0, 0),
(230, 'Viet Nam', 'VNM', 'VN', 1, 0, 0),
(231, 'Virgin Islands (British)', 'VGB', 'VG', 1, 0, 0),
(232, 'Virgin Islands (U.S.)', 'VIR', 'VI', 1, 0, 0),
(233, 'Wallis and Futuna Islands', 'WLF', 'WF', 1, 0, 0),
(234, 'Western Sahara', 'ESH', 'EH', 1, 0, 0),
(235, 'Yemen', 'YEM', 'YE', 1, 0, 0),
(237, 'The Democratic Republic of Congo', 'DRC', 'DC', 1, 0, 0),
(238, 'Zambia', 'ZMB', 'ZM', 1, 0, 0),
(239, 'Zimbabwe', 'ZWE', 'ZW', 1, 0, 0),
(240, 'East Timor', 'XET', 'XE', 1, 0, 0),
(241, 'Jersey', 'JEY', 'JE', 1, 0, 0),
(242, 'St. Barthelemy', 'XSB', 'XB', 1, 0, 0),
(243, 'St. Eustatius', 'XSE', 'XU', 1, 0, 0),
(244, 'Canary Islands', 'XCA', 'XC', 1, 0, 0),
(245, 'Serbia', 'SRB', 'RS', 1, 0, 0),
(246, 'Sint Maarten (French Antilles)', 'MAF', 'MF', 1, 0, 0),
(247, 'Sint Maarten (Netherlands Antilles)', 'SXM', 'SX', 1, 0, 0),
(248, 'Palestinian Territory, occupied', 'PSE', 'PS', 1, 0, 0);


CREATE TABLE IF NOT EXISTS `#__djc2_customer_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__djc2_tax_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` decimal(10,4) NOT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__djc2_tax_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_rate_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country_id` int(11) NOT NULL,
  `client_type` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_tax_rate_id` (`tax_rate_id`,`country_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djc2_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `price` decimal(10,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_group` (`item_id`,`group_id`),
  UNIQUE KEY `group_item` (`group_id`,`item_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djc2_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` int(11) NOT NULL,
  `invoice_number` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` datetime DEFAULT '0000-00-00 00:00:00',
  `invoice_date` datetime DEFAULT '0000-00-00 00:00:00',
  `total` decimal(10,4) NOT NULL,
  `grand_total` decimal(10,4) NOT NULL,
  `tax` decimal(10,4) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `status`  CHAR( 1 ) NOT NULL DEFAULT 'N',
  `email` varchar(255) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `vat_id` varchar(20) DEFAULT NULL,
  `customer_note` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_country_id` (`country_id`)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__djc2_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT '0',
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cost` decimal(10,4) NOT NULL,
  `base_cost` decimal(10,4) NOT NULL,
  `tax` decimal(10,4) NOT NULL,
  `tax_rate` decimal(10,4) NOT NULL,
  `total` decimal(10,4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_item_id` (`item_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__djc2_quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT '0000-00-00 00:00:00',
  `email` varchar(255) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `vat_id` varchar(20) DEFAULT NULL,
  `customer_note` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_country_id` (`country_id`)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__djc2_quote_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT '0',
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_quote_id` (`quote_id`),
  KEY `idx_item_id` (`item_id`)
) DEFAULT CHARSET=utf8;

ALTER TABLE `#__djc2_categories` ADD INDEX `idx_parent_id` ( `parent_id` );
ALTER TABLE `#__djc2_categories` ADD INDEX `idx_published` ( `published` );

ALTER TABLE `#__djc2_items` ADD INDEX `idx_name` ( `name` );
ALTER TABLE `#__djc2_items` ADD INDEX `idx_cat_id` ( `cat_id` ); 
ALTER TABLE `#__djc2_items` ADD INDEX `idx_group_id` ( `group_id` ); 
ALTER TABLE `#__djc2_items` ADD INDEX `idx_producer_id` ( `producer_id` ); 
ALTER TABLE `#__djc2_items` ADD INDEX `idx_published` ( `published` );
ALTER TABLE `#__djc2_items` ADD INDEX `idx_featured` ( `featured` );
ALTER TABLE `#__djc2_items` ADD INDEX `idx_created_by` ( `created_by` );

ALTER TABLE `#__djc2_producers` ADD INDEX `idx_published` ( `published` );
ALTER TABLE `#__djc2_producers` ADD INDEX `idx_created_by` ( `created_by` );

ALTER TABLE `#__djc2_images` ADD INDEX `idx_item_type_ordering` ( `item_id` , `type` , `ordering` ); 
ALTER TABLE `#__djc2_images` ADD INDEX `idx_exclude` ( `exclude` );

ALTER TABLE `#__djc2_files` ADD INDEX `idx_item_type_ordering` ( `item_id` , `type` , `ordering` );

ALTER TABLE `#__djc2_items_extra_fields` ADD INDEX `idx_published` ( `published` );
ALTER TABLE `#__djc2_items_extra_fields` ADD INDEX `idx_filterable` ( `filterable` );
ALTER TABLE `#__djc2_items_extra_fields` ADD INDEX `idx_searchable` ( `searchable` );
ALTER TABLE `#__djc2_items_extra_fields` ADD INDEX `idx_separate_column` ( `separate_column` ); 
ALTER TABLE `#__djc2_items_extra_fields` ADD INDEX `idx_type` ( `type` );
ALTER TABLE `#__djc2_items_extra_fields` ADD INDEX `idx_group_id` ( `group_id` );

ALTER TABLE `#__djc2_items_extra_fields_values_int` ADD INDEX `idx_value` (`value`);

ALTER TABLE `#__djc2_items_extra_fields_options` ADD INDEX `idx_field_id` ( `field_id` );
ALTER TABLE `#__djc2_items_extra_fields_options` ADD INDEX `idx_value` ( `value` );
