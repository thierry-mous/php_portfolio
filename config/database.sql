-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 14 fév. 2025 à 13:36
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetb2`
--

-- --------------------------------------------------------

--
-- Structure de la table `interests`
--

DROP TABLE IF EXISTS `interests`;
CREATE TABLE IF NOT EXISTS `interests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb3_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `interests`
--

INSERT INTO `interests` (`id`, `name`, `created_at`) VALUES
(1, 'Programming', '2025-02-13 16:51:54'),
(2, 'Web Development', '2025-02-13 16:51:54'),
(3, 'Design', '2025-02-13 16:51:54'),
(4, 'Photography', '2025-02-13 16:51:54'),
(5, 'Music', '2025-02-13 16:51:54'),
(6, 'PHP', '2025-02-13 18:49:22'),
(7, 'JavaScript', '2025-02-13 18:49:22'),
(8, 'HTML/CSS', '2025-02-13 18:49:22'),
(9, 'Python', '2025-02-13 18:49:22'),
(10, 'Java', '2025-02-13 18:49:22'),
(11, 'React', '2025-02-13 18:49:22'),
(12, 'Vue.js', '2025-02-13 18:49:22'),
(13, 'Node.js', '2025-02-13 18:49:22'),
(14, 'SQL', '2025-02-13 18:49:22'),
(15, 'DevOps', '2025-02-13 18:49:22'),
(16, 'Angular', '2025-02-13 18:49:22'),
(17, 'TypeScript', '2025-02-13 18:49:22'),
(18, 'C#', '2025-02-13 18:49:22'),
(19, 'Ruby', '2025-02-13 18:49:22'),
(20, 'Docker', '2025-02-13 18:49:22'),
(21, 'Git', '2025-02-13 18:49:22'),
(22, 'Laravel', '2025-02-13 18:49:22'),
(23, 'Symfony', '2025-02-13 18:49:22'),
(24, 'WordPress', '2025-02-13 18:49:22'),
(25, 'MongoDB', '2025-02-13 18:49:22');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `description` text COLLATE utf8mb3_bin NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `project_link` varchar(255) COLLATE utf8mb3_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `username_changed` tinyint(1) DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb3_bin DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `bio` text COLLATE utf8mb3_bin,
  `profile_picture` varchar(255) COLLATE utf8mb3_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `created_at`, `username_changed`, `username`, `is_admin`, `bio`, `profile_picture`) VALUES
(1, 'test1@test.com', '$2y$12$jLAbaV1RqplQ1sdL8Wrzd.GrYLBXkQxXt7ki7oh/UzAWXqeSbTFrm', '2025-02-13 17:25:01', 1, 'user1', 1, 'htjekshgtrkjeshrkjeshrze', 'uploads/profile_pictures/67af3c06394a8.png'),
(2, 'test2@test.com', '$2y$12$16zNGxAHwjqKrrq6d.YF5OoVtmiCOTEhjSkDDPiiMxIVAtGJBo1vm', '2025-02-13 19:35:07', 1, 'user2', 0, '', 'uploads/profile_pictures/67af39e5ba509.png');

-- --------------------------------------------------------

--
-- Structure de la table `user_interests`
--

DROP TABLE IF EXISTS `user_interests`;
CREATE TABLE IF NOT EXISTS `user_interests` (
  `user_id` int NOT NULL,
  `interest_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `level` int DEFAULT '1',
  PRIMARY KEY (`user_id`,`interest_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Déchargement des données de la table `user_interests`
--

INSERT INTO `user_interests` (`user_id`, `interest_id`, `created_at`, `level`) VALUES
(1, 16, '2025-02-13 19:10:36', 1),
(1, 18, '2025-02-13 19:10:36', 4),
(1, 15, '2025-02-13 19:10:36', 3),
(2, 24, '2025-02-13 20:02:59', 4);

-- --------------------------------------------------------

--
-- Structure de la table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `user_id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb3_bin DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb3_bin DEFAULT NULL,
  `bio` text COLLATE utf8mb3_bin,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
