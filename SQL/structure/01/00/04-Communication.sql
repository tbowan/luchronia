#------------------------------------------------------------------------------
# I18N
#------------------------------------------------------------------------------

CREATE TABLE `i18n_lang` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `code`     varchar(2) NOT NULL,
  `wikipage` int(11)    DEFAULT NULL,
  `mainpage` int(11)    DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB ;

CREATE TABLE `i18n_translation` (
  `id`          int(11)     NOT NULL AUTO_INCREMENT,
  `key`         varchar(64) NOT NULL,
  `lang`        int(11)     NOT NULL,
  `translation` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_2` (`key`,`lang`),
  KEY `key` (`key`),
  FOREIGN KEY (`lang`) REFERENCES `i18n_lang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Wiki
#------------------------------------------------------------------------------

CREATE TABLE `wiki_page` (
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `title`   varchar(64) NOT NULL,
  `content` text NOT NULL,
  `lang`    int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_lang` (`title`, `lang`),
  FOREIGN KEY (`lang`) REFERENCES `i18n_lang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

# Ajout des clés étrangères à la table des langues

ALTER TABLE `i18n_lang`
  ADD CONSTRAINT `i18n_lang_ibfk_1` FOREIGN KEY (`wikipage`) REFERENCES `wiki_page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `i18n_lang_ibfk_2` FOREIGN KEY (`mainpage`) REFERENCES `wiki_page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

#------------------------------------------------------------------------------
# Blog
#------------------------------------------------------------------------------

CREATE TABLE `blog_category` (
  `id`    int(11)     NOT NULL AUTO_INCREMENT,
  `name`  varchar(64) NOT NULL,
  `lang`  int(11)     NOT NULL,
  `group` int(11)     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_lang` (`name`, `lang`),
  FOREIGN KEY (`lang`)  REFERENCES `i18n_lang`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`group`) REFERENCES `identity_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `blog_post` (
  `id`       int(11)     NOT NULL AUTO_INCREMENT,
  `title`    varchar(64) NOT NULL,
  `date`     int(11)     NOT NULL,
  `head`     text        NOT NULL,
  `content`  text        NOT NULL,
  `category` int(11)     NOT NULL,
  `author`   int(11)     NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category`) REFERENCES `blog_category` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`author`)   REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Forum
#------------------------------------------------------------------------------

CREATE TABLE `forum_category` (
  `id`             int(11)     NOT NULL AUTO_INCREMENT,
  `lang`           int(11)     NOT NULL,
  `title`          varchar(64) NOT NULL,
  `description`    text        NOT NULL,
  `order`          int(11)     NOT NULL,
  `parent`         int(11)     DEFAULT NULL,
  `view_group`     int(11)     DEFAULT NULL,
  `moderate_group` int(11)     DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`lang`)           REFERENCES `i18n_lang` (`id`)      ON DELETE CASCADE  ON UPDATE CASCADE,
  FOREIGN KEY (`parent`)         REFERENCES `forum_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`view_group`)     REFERENCES `identity_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`moderate_group`) REFERENCES `identity_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `forum_thread` (
  `id`       int(11)    NOT NULL AUTO_INCREMENT,
  `title`    text       NOT NULL,
  `category` int(11)    NOT NULL,
  `pinned`   tinyint(1) NOT NULL,
  `closed`   tinyint(1) NOT NULL,
  `viewed`   int(11)    NOT NULL,
  `author`   int(11)    NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category`) REFERENCES `forum_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`author`)   REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `forum_post` (
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `thread`  int(11) NOT NULL,
  `date`    int(11) NOT NULL,
  `content` text    NOT NULL,
  `author`  int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`thread`) REFERENCES `forum_thread` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`author`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `forum_choice` (
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `thread`  int(11) NOT NULL,
  `message` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`thread`) REFERENCES `forum_thread` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;


CREATE TABLE `forum_vote` (
  `id`     int(11) NOT NULL AUTO_INCREMENT,
  `choice` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`choice`) REFERENCES `forum_choice` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`author`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
