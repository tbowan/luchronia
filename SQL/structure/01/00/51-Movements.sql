
#------------------------------------------------------------------------------
# Villes visitées
#------------------------------------------------------------------------------

CREATE TABLE `stats_game_moves` (
    `id`         int(11)      NOT NULL AUTO_INCREMENT,
    `city`       int(11)      NOT NULL,
    `character`  int(11)      NOT NULL,
    `last_visit` int(11)          NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)      REFERENCES `game_city`      (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Compétence pour se déplacer
#------------------------------------------------------------------------------

SET @physics       = (SELECT id FROM `game_characteristic` WHERE `name` = "Strength") ;

INSERT INTO `game_skill_skill` (`name`, `classname`, `by_hand`, `characteristic`) values
    ("Walk", "Move", 1.0, @physics) ; 
