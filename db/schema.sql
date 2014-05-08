CREATE DATABASE IF NOT EXISTS `urls` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `urls`;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original` varchar(512) NOT NULL,
  `shortened` varchar(512) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `original_index` (`original`),
  UNIQUE KEY `shortened_index` (`shortened`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

