-- --------------------------------------------------------
-- Hôte :                        allodssaobsite.mysql.db
-- Version du serveur:           5.5.43-0+deb7u1-log - (Debian)
-- SE du serveur:                debian-linux-gnu
-- HeidiSQL Version:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Export de la structure de table allodssaobsite. ban
DROP TABLE IF EXISTS `ban`;
CREATE TABLE IF NOT EXISTS `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `motif` varchar(50) DEFAULT NULL,
  `musique` varchar(150) DEFAULT NULL,
  `date` bigint(20) DEFAULT NULL,
  `fin` bigint(20) DEFAULT NULL,
  `fini` enum('Y','N') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- L'exportation de données n'était pas sélectionnée.


-- Export de la structure de table allodssaobsite. news
DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` varchar(50) DEFAULT NULL,
  `auteur` varchar(50) DEFAULT NULL,
  `contenu` text,
  `date` bigint(20) DEFAULT NULL,
  `administratif` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- L'exportation de données n'était pas sélectionnée.


-- Export de la structure de table allodssaobsite. users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL DEFAULT '0',
  `pseudo` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `avatar` varchar(50) DEFAULT '/app/user_folder/default/avatar/default.png',
  `grade` enum('Ban','Mem','VIP','Mod','Adm') DEFAULT 'Mem',
  `register_ip` varchar(25) DEFAULT NULL,
  `date_inscription` bigint(21) DEFAULT NULL,
  `valider` enum('Y','N') DEFAULT 'N',
  `deleted` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- L'exportation de données n'était pas sélectionnée.


-- Export de la structure de table allodssaobsite. youtube
DROP TABLE IF EXISTS `youtube`;
CREATE TABLE IF NOT EXISTS `youtube` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `videoID` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `thumbnails` varchar(250) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `title` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `author` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `duration` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `alreadyEar` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `inListening` enum('Y','N') CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `applicant` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `applicant_ip` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `VotePour` int(11) NOT NULL,
  `VoteContre` int(11) NOT NULL,
  `lastVote` enum('POUR','CONTRE') DEFAULT NULL,
  `forNight` enum('Y','N') DEFAULT 'N',
  `reported` enum('Y','N') CHARACTER SET latin1 DEFAULT 'N',
  `accepted` enum('Y','N') DEFAULT 'N',
  `deleted` enum('Y','N') CHARACTER SET latin1 DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- L'exportation de données n'était pas sélectionnée.


-- Export de la structure de table allodssaobsite. youtubeVotes
DROP TABLE IF EXISTS `youtubeVotes`;
CREATE TABLE IF NOT EXISTS `youtubeVotes` (
  `user_id` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `videoID` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- L'exportation de données n'était pas sélectionnée.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
