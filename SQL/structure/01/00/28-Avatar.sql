
#------------------------------------------------------------------------------
# Ajout des avatars
#------------------------------------------------------------------------------

CREATE TABLE `game_avatar_item` (
    `id`        int(11)      NOT NULL AUTO_INCREMENT,
    `race`      int(11)          NULL,
    `sex`       int(11)          NULL,
    `layer`     int(11)      NOT NULL,
    `filename`  varchar(128) NOT NULL,
    `price`     double NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE `game_avatar_used` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `item`      int(11)     NOT NULL,
    `character` int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`item`)      REFERENCES `game_avatar_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_avatar_owned` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `item`      int(11)     NOT NULL,
    `character` int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`item`)      REFERENCES `game_avatar_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Images
#------------------------------------------------------------------------------


