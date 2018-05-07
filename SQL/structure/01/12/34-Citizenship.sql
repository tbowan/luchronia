#------------------------------------------------------------------------------
# Suppression des anciennes citoyennetés
#------------------------------------------------------------------------------

ALTER TABLE game_character
    DROP FOREIGN KEY `game_character_ibfk_3`,
    DROP COLUMN      `citizenship` ;

#------------------------------------------------------------------------------
# Table des citoyennetés
#------------------------------------------------------------------------------

CREATE TABLE `game_citizenship` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `character` int(11)     NOT NULL,
    `city`      int(11)     NOT NULL,
    `created`   int(11)         NULL,
    `from`      int(11)         NULL,
    `to`        int(11)         NULL,
    `isInvite`  tinyint(1)  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)      ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`city`)        REFERENCES `game_city` (`id`)           ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des messages de suivi
#------------------------------------------------------------------------------

CREATE TABLE `game_citizenship_message` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `citizenship`   int(11)     NOT NULL,
    `character`     int(11)     NOT NULL,
    `date`          int(11)     NOT NULL,
    `message`       text        NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`citizenship`) REFERENCES `game_citizenship` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
