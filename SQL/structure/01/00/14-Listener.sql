#------------------------------------------------------------------------------
# Listeners
#------------------------------------------------------------------------------

CREATE TABLE `game_quest_event` (
    `id`    int(11)     NOT NULL AUTO_INCREMENT,
    `name`  varchar(32) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE `game_quest_listener` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `event`     int(11)     NOT NULL,
    `character` int(11)     NOT NULL,
    `type`      varchar(32) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`event`)     REFERENCES `game_quest_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
