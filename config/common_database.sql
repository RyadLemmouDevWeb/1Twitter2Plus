DROP DATABASE IF EXISTS twitter;
CREATE DATABASE twitter;
USE twitter;

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS follow;
DROP TABLE IF EXISTS tweet;
DROP TABLE IF EXISTS retweet;
DROP TABLE IF EXISTS hashtag;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS block_user;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS bookmark;
DROP TABLE IF EXISTS impression;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS community;
DROP TABLE IF EXISTS user_community;

CREATE TABLE `user` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `role` varchar(255) DEFAULT 'user',
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) UNIQUE NOT NULL,
  `display_name` varchar(255),
  `email` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `phone` varchar(255) NULL,
  `url` varchar(255) NULL,
  `biography` varchar(255) NULL,
  `city` varchar(255) NULL,
  `country` varchar(255) NULL,
  `genre` varchar(255) NULL,
  `picture` varchar(255) NULL,
  `header` varchar(255) NULL,
  `NSFW` boolean DEFAULT false,
  `is_active` boolean DEFAULT true NOT NULL,
  `is_verified` boolean DEFAULT false NOT NULL,
  `ban` varchar(255) DEFAULT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `verified_date` date DEFAULT NULL,
  `inactive_date` date DEFAULT NULL,
  `verification_code` varchar(6) NULL
);

CREATE TABLE `follow` (
  `id_user_follow` integer NOT NULL,
  `id_user_followed` integer NOT NULL,
  PRIMARY KEY (id_user_follow, id_user_followed), -- clé primaire composite
  FOREIGN KEY (id_user_follow) REFERENCES user(id),
  FOREIGN KEY (id_user_followed) REFERENCES user(id)
);

CREATE TABLE `block_user` (
  `id_user` integer NOT NULL,
  `id_blocked_user` integer NOT NULL,
  PRIMARY KEY (id_user, id_blocked_user), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_blocked_user) REFERENCES user(id)
);

CREATE TABLE `tweet` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `id_user` integer NOT NULL,
  `reply_to` integer NULL,
  `quote_to` integer NULL,
  `NSFW` boolean DEFAULT false NOT NULL,
  `content` varchar(140) NOT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_pinned` boolean NOT NULL DEFAULT false,
  `is_community` boolean NOT NULL DEFAULT false,
  `media1` varchar(255) NULL,
  `media2` varchar(255) NULL,
  `media3` varchar(255) NULL,
  `media4` varchar(255) NULL,
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (reply_to) REFERENCES tweet(id),
  FOREIGN KEY (quote_to) REFERENCES tweet(id)
);

CREATE TABLE `bookmark` (
  `id_tweet` integer NOT NULL,
  `id_user` integer NOT NULL,
  PRIMARY KEY (id_tweet, id_user), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `impression` (
  `id_user` integer NOT NULL,
  `id_tweet` integer NOT NULL,
  PRIMARY KEY (id_user, id_tweet), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `retweet` (
  `id_user` integer NOT NULL,
  `id_tweet` integer NOT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_user, id_tweet), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `hashtag` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
);

CREATE TABLE `report` (
  `id_tweet` integer NOT NULL,
  `id_user` integer NOT NULL,
  `description` varchar(255) DEFAULT null,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_tweet, id_user), -- clé primaire composite
  FOREIGN KEY (id_tweet) REFERENCES tweet(id),
  FOREIGN KEY (id_user) REFERENCES user(id)
);

CREATE TABLE `likes` (
  `id_user` integer NOT NULL,
  `id_tweet` integer NOT NULL,
  PRIMARY KEY (id_user, id_tweet), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_tweet) REFERENCES tweet(id)
);

CREATE TABLE `message` (
  `id` integer PRIMARY KEY,
  `content` varchar(255) NOT NULL,
  `media` varchar(255) NULL,
  `id_sender` integer NOT NULL,
  `id_receiver` integer NOT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_hidden` boolean NOT NULL DEFAULT false,
  `is_viewed` boolean NOT NULL DEFAULT false,
  FOREIGN KEY (id_sender) REFERENCES user(id),
  FOREIGN KEY (id_receiver) REFERENCES user(id)
);

CREATE TABLE `community` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `biography` varchar(255),
  `id_creator` integer NOT NULL,
  `cover` varchar(255),
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_creator) REFERENCES user(id)
);

CREATE TABLE `user_community` (
  `id_community` integer NOT NULL,
  `id_user` integer NOT NULL,
  `role` varchar(255) DEFAULT 'user',
  PRIMARY KEY (id_community, id_user), -- clé primaire composite
  FOREIGN KEY (id_user) REFERENCES user(id),
  FOREIGN KEY (id_community) REFERENCES community(id)
);
