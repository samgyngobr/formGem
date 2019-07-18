
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `scarlet_area` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `area_id` bigint(20) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `label` varchar(200) NOT NULL,
  `multiple` tinyint(4) NOT NULL DEFAULT '0',
  `url` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `gallery` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_scarlet_area_scarlet_area` (`area_id`),
  CONSTRAINT `FK_scarlet_area_scarlet_area` FOREIGN KEY (`area_id`) REFERENCES `scarlet_area` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area_id` bigint(20) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_scarlet_area1_idx` (`area_id`),
  CONSTRAINT `fk_scarlet_data_scarlet_area1` FOREIGN KEY (`area_id`) REFERENCES `scarlet_area` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_data_boolean` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `history_id` bigint(20) NOT NULL,
  `field_id` bigint(20) NOT NULL,
  `value` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_boolean_scarlet_data1_idx` (`history_id`),
  KEY `fk_scarlet_data_boolean_scarlet_field1_idx` (`field_id`),
  CONSTRAINT `fk_scarlet_data_boolean_scarlet_data1` FOREIGN KEY (`history_id`) REFERENCES `scarlet_history` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_scarlet_data_boolean_scarlet_field1` FOREIGN KEY (`field_id`) REFERENCES `scarlet_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_data_date` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `history_id` bigint(20) NOT NULL,
  `field_id` bigint(20) NOT NULL,
  `value` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_date_scarlet_data1_idx` (`history_id`),
  KEY `fk_scarlet_data_date_scarlet_field1_idx` (`field_id`),
  CONSTRAINT `fk_scarlet_data_date_scarlet_data1` FOREIGN KEY (`history_id`) REFERENCES `scarlet_history` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_scarlet_data_date_scarlet_field1` FOREIGN KEY (`field_id`) REFERENCES `scarlet_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_data_decimal` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `history_id` bigint(20) NOT NULL,
  `field_id` bigint(20) NOT NULL,
  `value` decimal(2,0) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_decimal_scarlet_data1_idx` (`history_id`),
  KEY `fk_scarlet_data_decimal_scarlet_field1_idx` (`field_id`),
  CONSTRAINT `fk_scarlet_data_decimal_scarlet_data1` FOREIGN KEY (`history_id`) REFERENCES `scarlet_history` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_scarlet_data_decimal_scarlet_field1` FOREIGN KEY (`field_id`) REFERENCES `scarlet_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_data_integer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `history_id` bigint(20) NOT NULL,
  `field_id` bigint(20) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_integer_scarlet_data1_idx` (`history_id`),
  KEY `fk_scarlet_data_integer_scarlet_field1_idx` (`field_id`),
  CONSTRAINT `fk_scarlet_data_integer_scarlet_data1` FOREIGN KEY (`history_id`) REFERENCES `scarlet_history` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_scarlet_data_integer_scarlet_field1` FOREIGN KEY (`field_id`) REFERENCES `scarlet_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_data_textarea` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `history_id` bigint(20) NOT NULL,
  `field_id` bigint(20) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_textarea_scarlet_data1_idx` (`history_id`),
  KEY `fk_scarlet_data_textarea_scarlet_field1_idx` (`field_id`),
  CONSTRAINT `fk_scarlet_data_textarea_scarlet_data1` FOREIGN KEY (`history_id`) REFERENCES `scarlet_history` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_scarlet_data_textarea_scarlet_field1` FOREIGN KEY (`field_id`) REFERENCES `scarlet_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_data_varchar` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `history_id` bigint(20) NOT NULL,
  `field_id` bigint(20) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_varchar_scarlet_data1_idx` (`history_id`),
  KEY `fk_scarlet_data_varchar_scarlet_field1_idx` (`field_id`),
  CONSTRAINT `fk_scarlet_data_varchar_scarlet_data1` FOREIGN KEY (`history_id`) REFERENCES `scarlet_history` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_scarlet_data_varchar_scarlet_field1` FOREIGN KEY (`field_id`) REFERENCES `scarlet_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_field` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `version_id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '1 text, 2 int, 3 double, 4 textarea, 5 select, 6 radio, 7 checkbox, 8 image, 9 upload, 10 date, 11 bool',
  `required` tinyint(4) NOT NULL DEFAULT '0',
  `tip` varchar(200) DEFAULT NULL,
  `additional` varchar(500) DEFAULT NULL COMMENT 'opções adicionais em json',
  `validation` int(11) DEFAULT NULL COMMENT '1=cpf\n2=cnpj\n3=email',
  `order` int(11) DEFAULT '0',
  `index` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_field_scarlet_version1_idx` (`version_id`),
  CONSTRAINT `fk_scarlet_field_scarlet_version1` FOREIGN KEY (`version_id`) REFERENCES `scarlet_version` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_field_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field_id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_field_options_scarlet_field1_idx` (`field_id`),
  CONSTRAINT `fk_scarlet_field_options_scarlet_field1` FOREIGN KEY (`field_id`) REFERENCES `scarlet_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_gallery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `data_id` bigint(20) NOT NULL,
  `img` varchar(200) NOT NULL,
  `legend` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_scarlet_gallery_scarlet_data` (`data_id`),
  CONSTRAINT `FK_scarlet_gallery_scarlet_data` FOREIGN KEY (`data_id`) REFERENCES `scarlet_data` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `version_id` bigint(20) NOT NULL,
  `data_id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `current` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_data_scarlet_version1_idx` (`version_id`),
  KEY `fk_scarlet_history_scarlet_data1_idx` (`data_id`),
  CONSTRAINT `FK_scarlet_history_scarlet_data` FOREIGN KEY (`data_id`) REFERENCES `scarlet_data` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_scarlet_data_scarlet_version1` FOREIGN KEY (`version_id`) REFERENCES `scarlet_version` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `scarlet_version` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `area_id` bigint(20) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_scarlet_version_scarlet_area1_idx` (`area_id`),
  CONSTRAINT `fk_scarlet_version_scarlet_area1` FOREIGN KEY (`area_id`) REFERENCES `scarlet_area` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_reset_key` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
