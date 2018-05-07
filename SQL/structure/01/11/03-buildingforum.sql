
#------------------------------------------------------------------------------
# Les catégories du forum
#------------------------------------------------------------------------------

CREATE TABLE `game_forum_category` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `instance`      int(11) NOT NULL,
    `title`         varchar(64) NOT NULL,
    `description`   varchar(255) NOT NULL,
    `order`         int(11) NOT NULL,
    `rw`            int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)    REFERENCES `game_building_instance` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Les modérateurs des catégories
#------------------------------------------------------------------------------

CREATE TABLE `game_forum_moderator` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `category`  int(11) NOT NULL,
    `moderator` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`category`)    REFERENCES `game_forum_category` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`moderator`)   REFERENCES `game_character`      (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Les fils de discussion
#------------------------------------------------------------------------------

CREATE TABLE `game_forum_thread` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `category`      int(11) NOT NULL,
    `author`        int(11) NOT NULL,
    `title`         varchar(64) NOT NULL,
    `pinned`        tinyint(1) NOT NULL,
    `closed`        tinyint(1) NOT NULL,
    `viewed`        int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`category`)    REFERENCES `game_forum_category` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`author`)      REFERENCES `game_character`      (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Les messages
#------------------------------------------------------------------------------

CREATE TABLE `game_forum_post` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `thread`        int(11) NOT NULL,
    `pub_author`    int(11) NOT NULL,
    `pub_date`      int(11) NOT NULL,
    `mod_author`    int(11)     NULL,
    `mod_date`      int(11)     NULL,
    `content`       text NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`thread`)      REFERENCES `game_forum_thread`  (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`pub_author`)  REFERENCES `game_character`     (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`mod_author`)  REFERENCES `game_character`     (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
