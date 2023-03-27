-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : Dim 17 avr. 2022 à 23:59
-- Version du serveur :  5.7.34-log
-- Version de PHP : 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `shadowsdash`
--

-- --------------------------------------------------------

--
-- Structure de la table `eggs`
--

CREATE TABLE `eggs` (
  `id` int(11) NOT NULL,
  `egg` int(255) NOT NULL,
  `nest` int(255) NOT NULL DEFAULT '1',
  `icon` text NOT NULL,
  `category` text NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `j4r`
--

CREATE TABLE `j4r` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `qc` text NOT NULL,
  `invite` text NOT NULL,
  `joins` text NOT NULL,
  `status` text NOT NULL,
  `serverid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `j4r_claimed`
--

CREATE TABLE `j4r_claimed` (
  `id` int(11) NOT NULL,
  `serverid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `icon` text NOT NULL,
  `locationid` int(255) NOT NULL,
  `status` text NOT NULL,
  `slots` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `ipaddr` text NOT NULL,
  `userid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `referral_claims`
--

CREATE TABLE `referral_claims` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `uid` text NOT NULL,
  `timestamp` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `referral_codes`
--

CREATE TABLE `referral_codes` (
  `id` int(11) NOT NULL,
  `uid` text NOT NULL,
  `referral` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `pid` int(255) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `location` int(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `created` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `servers_queue`
--

CREATE TABLE `servers_queue` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `ram` int(255) NOT NULL,
  `disk` int(255) NOT NULL,
  `cpu` decimal(65,0) NOT NULL,
  `xtra_ports` int(255) NOT NULL,
  `databases` int(255) NOT NULL,
  `location` int(255) NOT NULL,
  `ownerid` varchar(255) NOT NULL,
  `type` int(255) NOT NULL,
  `egg` int(255) NOT NULL,
  `puid` varchar(255) NOT NULL,
  `created` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `panel_id` int(11) NOT NULL,
  `discord_id` varchar(25) NOT NULL,
  `discord_name` varchar(255) DEFAULT NULL,
  `discord_email` varchar(255) DEFAULT NULL,
  `avatar` text NOT NULL,
  `coins` decimal(65,2) NOT NULL DEFAULT '0.00' COMMENT 'Change this for default plan',
  `balance` decimal(65,2) NOT NULL DEFAULT '0.00' COMMENT 'Change this for default plan',
  `memory` int(255) NOT NULL DEFAULT '2048' COMMENT 'Change this for default plan',
  `disk_space` int(255) NOT NULL DEFAULT '10000' COMMENT 'Change this for default plan',
  `ports` int(255) DEFAULT '1' COMMENT 'Change this for default plan',
  `databases` int(255) DEFAULT '1' COMMENT 'Change this for default plan',
  `cpu` varchar(255) NOT NULL DEFAULT '60' COMMENT 'Change this for default plan',
  `server_limit` int(255) NOT NULL DEFAULT '2' COMMENT 'Change this for default plan',
  `panel_username` varchar(255) NOT NULL,
  `panel_password` varchar(255) NOT NULL,
  `register_ip` text NOT NULL,
  `lastlogin_ip` text NOT NULL,
  `created_at` int(255) NOT NULL,
  `last_login` text NOT NULL,
  `banned` tinyint(4) NOT NULL DEFAULT '0',
  `banned_reason` longtext,
  `staff` tinyint(4) NOT NULL DEFAULT '0',
  `registered` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `eggs`
--
ALTER TABLE `eggs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `j4r`
--
ALTER TABLE `j4r`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `j4r_claimed`
--
ALTER TABLE `j4r_claimed`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `referral_claims`
--
ALTER TABLE `referral_claims`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `referral_codes`
--
ALTER TABLE `referral_codes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `servers_queue`
--
ALTER TABLE `servers_queue`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--
--
-- AUTO_INCREMENT pour la table `eggs`
--
ALTER TABLE `eggs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `j4r`
--
ALTER TABLE `j4r`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `j4r_claimed`
--
ALTER TABLE `j4r_claimed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `referral_claims`
--
ALTER TABLE `referral_claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `referral_codes`
--
ALTER TABLE `referral_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `servers_queue`
--
ALTER TABLE `servers_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
