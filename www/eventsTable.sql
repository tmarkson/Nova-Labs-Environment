-- DROP TABLE IF EXISTS 'novalabsDoorEvents';

CREATE TABLE IF NOT EXISTS `novalabsDoorEvents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `doorValue` boolean NOT NULL,
  `tempValue` DECIMAL(10,2) NOT NULL,
  `author` varchar(512) CHARACTER SET latin1 NULL, 
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
