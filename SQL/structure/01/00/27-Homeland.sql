
#------------------------------------------------------------------------------
# Ajout du mur de ville
#------------------------------------------------------------------------------

CREATE TABLE `game_building_wall` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `instance`  int(11)     NOT NULL,
    `door`      int(11)     NOT NULL,
    `message`   text        NOT NULL,
    `defense`   double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`) REFERENCES `game_building_instance` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Ajout de la table des demandes de citoyenneté et invitations
#------------------------------------------------------------------------------

CREATE TABLE `game_citizenship_request` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `city`      int(11)     NOT NULL,
    `character` int(11)     NOT NULL,
    `message`   text        NOT NULL,
    `creation`  int(11)     NOT NULL,
    `update`    int(11)     NOT NULL,
    `status`    int(11)     NOT NULL,
    `minister`  int(11)         NULL,
    `answer`    text            NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)      REFERENCES `game_city`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`minister`)  REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_citizenship_proposal` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `city`      int(11)     NOT NULL,
    `minister`  int(11)     NOT NULL,
    `message`   text        NOT NULL,
    `creation`  int(11)     NOT NULL,
    `update`    int(11)     NOT NULL,
    `status`    int(11)     NOT NULL,
    `character` int(11)     NOT NULL,
    `answer`    text            NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)      REFERENCES `game_city`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`minister`)  REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;