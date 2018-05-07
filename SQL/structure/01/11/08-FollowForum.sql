
#------------------------------------------------------------------------------
# Table des suivis
#------------------------------------------------------------------------------

CREATE TABLE `game_forum_follow` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `thread`    int(11) NOT NULL,
    `character` int(11) NOT NULL,
    `last_post` int(11)     NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`thread`)      REFERENCES `game_forum_thread` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)      ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`last_post`)   REFERENCES `game_forum_post` (`id`)     ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;