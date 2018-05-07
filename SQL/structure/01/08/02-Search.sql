
#------------------------------------------------------------------------------
# Compétences de fouille
#------------------------------------------------------------------------------

SET @Perception = (SELECT id FROM `game_characteristic` WHERE `name` = "Perception") ;
SET @Outside    = (SELECT id FROM `game_building_job` WHERE `name` = "Outside") ;
SET @Ruin       = (SELECT id FROM `game_building_job` WHERE `name` = "Ruin") ;
SET @Excavation = (SELECT id FROM `game_building_job` WHERE `name` = "Excavation") ;

#------------------------------------------------------------------------------
# Table des trésors
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_treasure` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
# Where to search
    `job`       int(11)         NULL,
    `type`      int(11)         NULL,
    `biome`     int(11)         NULL,
    `city`      int(11)         NULL,
# What to find
    `item`      int(11)     NOT NULL,
    `amount`    double      NOT NULL,
    `infinite`  tinyint(1)  NOT NULL,
    `gained`    double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`job`)   REFERENCES `game_building_job`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`type`)  REFERENCES `game_building_type`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`biome`) REFERENCES `game_biome`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`city`)  REFERENCES `game_city`           (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# The Skills
#------------------------------------------------------------------------------

INSERT INTO game_skill_skill
    (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `start`)
VALUES
    ("SearchOutside",       "Search", @Outside,     null, 1.0, @Perception, 0),
    ("SearchRuin",          "Search", @Ruin,        null, 1.0, @Perception, 0),
    ("SearchExcavation",    "Search", @Excavation,  null, 1.0, @Perception, 0)
 ;
