-- phpMyAdmin SQL Dump

-- version 4.5.4.1deb2ubuntu2

-- http://www.phpmyadmin.net

--

-- Client :  localhost

-- Généré le :  Jeu 26 Octobre 2017 à 13:53

-- Version du serveur :  5.7.19-0ubuntu0.16.04.1

-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */

;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */

;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */

;

/*!40101 SET NAMES utf8mb4 */

;

--

-- Base de données :  `simple-mvc`

--

-- --------------------------------------------------------

--

-- Structure de la table `item`

--

CREATE TABLE
    `item` (
        `id` int(11) UNSIGNED NOT NULL,
        `title` varchar(255) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--

-- Contenu de la table `item`

--

INSERT INTO
    `item` (`id`, `title`)
VALUES (1, 'Stuff'), (2, 'Doodads');

--

-- Index pour les tables exportées

--

--

-- Index pour la table `item`

--

ALTER TABLE `item` ADD PRIMARY KEY (`id`);

--

-- AUTO_INCREMENT pour les tables exportées

--

--

-- AUTO_INCREMENT pour la table `item`

--

ALTER TABLE
    `item` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 3;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */

;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */

;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */

;

/***** Table Category ******/

CREATE TABLE
    category (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        name VARCHAR(255) NOT NULL
    );

INSERT INTO category (name)
VALUES ('Pneus toute saison'), ('Pneus pluie'), ('Pneus neige');

/***** Table Tire ******/

CREATE TABLE
    tire (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(6, 2) NOT NULL,
        image VARCHAR(255),
        description TEXT,
        category_id INT,
        FOREIGN KEY (category_id) REFERENCES category(id)
    );

INSERT INTO tire (name, price, description, category_id, image) 
    VALUES 
    ('pneu1', 100, 'mon pneu 1', 1, 'tire1.webp'),
    ('pneu2', 100, 'mon pneu 2', 2, NULL),
    ('pneu3', 99.99, 'mon pneu 3', 3, 'tire2.webp'),
    ('pneu4', 100, 'mon pneu 4', 1, NULL),
    ('pneu5', 100, 'lorem ipsum dolor', 2, NULL), 
    ('mon pneu neige', 100, 'mon pneu', 3, NULL);