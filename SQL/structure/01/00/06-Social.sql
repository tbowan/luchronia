
#------------------------------------------------------------------------------
# Social : posts & comment
#------------------------------------------------------------------------------


CREATE TABLE `game_social_post` (
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `author`  int(11) NOT NULL,
  `date`    int(11) NOT NULL,
  `content` text    NOT NULL,
  `access`  int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`author`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_social_comment` (
  `id`      int(11) NOT NULL AUTO_INCREMENT,
  `author`  int(11) NOT NULL,
  `date`    int(11) NOT NULL,
  `content` text    NOT NULL,
  `post`    int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`author`) REFERENCES `game_character`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`post`)   REFERENCES `game_social_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Social : friendship
#------------------------------------------------------------------------------

CREATE TABLE `game_social_friend` (
  `id`      int(11)    NOT NULL AUTO_INCREMENT,
  `a`       int(11)    NOT NULL,
  `b`       int(11)    NOT NULL,
  `date`    int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`a`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`b`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_social_request` (
  `id`      int(11)    NOT NULL AUTO_INCREMENT,
  `a`       int(11)    NOT NULL,
  `b`       int(11)    NOT NULL,
  `msg`     text       NOT NULL,
  `date`    int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`a`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`b`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Social : friendship
#------------------------------------------------------------------------------

CREATE TABLE `game_social_follower` (
  `id`   int(11)    NOT NULL AUTO_INCREMENT,
  `a`    int(11)    NOT NULL,
  `b`    int(11)    NOT NULL,
  `date` int(11)    NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`a`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`b`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;