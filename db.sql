CREATE TABLE `country` (
  `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tid` varchar(2) NOT NULL,
  `tid2` varchar(3) DEFAULT NULL,
  `iso` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Список стран';


CREATE TABLE `locality` (
  `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `country_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Список городов по странам';
ALTER TABLE `locality` ADD INDEX(`country_id`);
ALTER TABLE `locality` ADD FOREIGN KEY (`country_id`) REFERENCES `country`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `street` (
  `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `country_id` int(11) UNSIGNED NOT NULL,
  `locality_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `coordinates` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Список улиц по городам';
ALTER TABLE `street` ADD INDEX(`country_id`,`locality_id`);
ALTER TABLE `street` ADD FOREIGN KEY (`country_id`) REFERENCES `country`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `street` ADD FOREIGN KEY (`locality_id`) REFERENCES `locality`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `subscriber` (
    `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `surname` varchar(80) NOT NULL,
    `name` varchar(50) NOT NULL,
    `patronymic` varchar(80) DEFAULT NULL,
    `country_id` int(11) UNSIGNED NOT NULL,
    `locality_id` int(11) UNSIGNED NOT NULL,
    `street_id` int(11) UNSIGNED NOT NULL,
    `house_number` varchar(5) NOT NULL  COMMENT 'номер дома, возможно с дробями и литерами',
    `apartment_number` varchar(5) DEFAULT NULL COMMENT 'номер квартиры, если домовладение, то пустое'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'список подписчиков';
ALTER TABLE `subscriber` ADD INDEX(`country_id`,`locality_id`,`street_id`);
ALTER TABLE `subscriber` ADD FOREIGN KEY (`country_id`) REFERENCES `country`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `subscriber` ADD FOREIGN KEY (`locality_id`) REFERENCES `locality`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `subscriber` ADD FOREIGN KEY (`street_id`) REFERENCES `street`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `magazine` (
    `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50) NOT NULL COMMENT 'название журнала',
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Список журналов';


CREATE TABLE `magazine_release` (
    `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `magazine_id` int(11) UNSIGNED NOT NULL,
    `number` int(4) NOT NULL COMMENT 'номер журнала',
    `release` DATE NOT NULL COMMENT 'дата выхода журнала'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Список выпусков журнала';
ALTER TABLE `magazine_release` ADD INDEX(`magazine_id`);
ALTER TABLE `magazine_release` ADD FOREIGN KEY (`magazine_id`) REFERENCES `magazine`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `subscription` (
  `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `subscriber_id` int(11) UNSIGNED NOT NULL,
  `magazine_id` int(11) UNSIGNED NOT NULL,
  `begin` DATE NOT NULL COMMENT 'дата начала подписки',
  `period` int(2) UNSIGNED NOT NULL COMMENT 'срок подписки(мес.)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Подписки подписчиков на журналы';
ALTER TABLE `subscription` ADD INDEX(`subscriber_id`,`magazine_id`);
ALTER TABLE `subscription` ADD FOREIGN KEY (`subscriber_id`) REFERENCES `subscriber`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `subscription` ADD FOREIGN KEY (`magazine_id`) REFERENCES `magazine`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


CREATE TABLE `operator` (
  `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `number` varchar(4) UNIQUE NOT NULL COMMENT 'номер оператора',
  `name` varchar(50) NOT NULL,
  `status` ENUM('offline','ready','away') NOT NULL DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'список операторов';


CREATE TABLE `check` (
  `id` int(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `number` varchar(20) NOT NULL COMMENT 'номер чека',
  `departure_date` DATE NOT NULL COMMENT 'дата отправки',
  `tracking_number` varchar(20) NOT NULL COMMENT 'номер отслеживания',
  `status` ENUM('new','introduced','error') NOT NULL DEFAULT 'new',
  `file` varchar(255) NOT NULL,
  `subscription_id` int(11) UNSIGNED NOT NULL,
  `magazine_release_id` int(11) UNSIGNED NOT NULL,
  `operator_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'чеки к подпискам';
ALTER TABLE `check` ADD INDEX(`subscription_id`,`operator_id`);
ALTER TABLE `check` ADD FOREIGN KEY (`subscription_id`) REFERENCES `subscription`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `check` ADD FOREIGN KEY (`operator_id`) REFERENCES `operator`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `check` ADD FOREIGN KEY (`magazine_release_id`) REFERENCES `magazine_release`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;