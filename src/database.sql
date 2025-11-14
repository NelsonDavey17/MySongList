SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `gender` ENUM('male', 'female') NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_unique` (`username`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `artist` (
  `artist_id` INT NOT NULL AUTO_INCREMENT,
  `nama_artist` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`artist_id`),
  UNIQUE KEY `nama_artist_unique` (`nama_artist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `genre` (
  `genre_id` INT NOT NULL AUTO_INCREMENT,
  `nama_genre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`genre_id`),
  UNIQUE KEY `nama_genre_unique` (`nama_genre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `lagu` (
  `lagu_id` INT NOT NULL AUTO_INCREMENT,
  `judul` VARCHAR(255) NOT NULL,
  `artist_id` INT NOT NULL,
  `tahun` YEAR NULL DEFAULT NULL,
  PRIMARY KEY (`lagu_id`),
  KEY `artist_id` (`artist_id`),
  CONSTRAINT `lagu_fk_artist` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`artist_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `lagu_genre` (
  `lagu_id` INT NOT NULL,
  `genre_id` INT NOT NULL,
  PRIMARY KEY (`lagu_id`, `genre_id`),
  KEY `genre_id` (`genre_id`),
  CONSTRAINT `lg_fk_lagu` FOREIGN KEY (`lagu_id`) REFERENCES `lagu` (`lagu_id`) ON DELETE CASCADE,
  CONSTRAINT `lg_fk_genre` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`genre_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `lagu_user` (
  `user_id` INT NOT NULL,
  `lagu_id` INT NOT NULL,
  `tanggal_ditambahkan` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `lagu_id`),
  KEY `lagu_id` (`lagu_id`),
  CONSTRAINT `lu_fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `lu_fk_lagu` FOREIGN KEY (`lagu_id`) REFERENCES `lagu` (`lagu_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `friendlist` (
  `user_satu_id` INT NOT NULL,
  `user_dua_id` INT NOT NULL,
  `status` ENUM('pending', 'accepted', 'declined', 'blocked') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`user_satu_id`, `user_dua_id`),
  KEY `user_dua_id` (`user_dua_id`),
  CONSTRAINT `fl_fk_user_satu` FOREIGN KEY (`user_satu_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fl_fk_user_dua` FOREIGN KEY (`user_dua_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `genre` (`nama_genre`) VALUES
('Pop'),
('Rock'),
('Hip Hop'),
('R&B'),
('Electronic'),
('Jazz'),
('Blues'),
('Country'),
('Folk'),
('Classical'),
('Metal'),
('Punk'),
('Alternative Rock'),
('Indie Pop'),
('Reggae'),
('Latin'),
('Funk'),
('Soul'),
('Disco'),
('Techno'),
('House'),
('EDM'),
('Dubstep'),
('K-Pop'),
('J-Pop'),
('Dangdut'),
('Acoustic'),
('Ambient'),
('Soundtrack'),
('Experimental'),
('Others');