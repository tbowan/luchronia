
#------------------------------------------------------------------------------
# Compétences d'architecte
#------------------------------------------------------------------------------

SET @mental     = (SELECT id FROM `game_characteristic` WHERE `name` = "Mental") ;
SET @architect  = (SELECT id FROM `game_building_job` WHERE `name` = "Architect") ;

#------------------------------------------------------------------------------
# étude depuis les bâtiments eux-même
#------------------------------------------------------------------------------

INSERT INTO game_skill_skill
    (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `start`)
select
    concat("StudyBuilding", gbt.`name`) as `name`,
    "StudyBuilding"                     as `classname`,
    null                                as `building_job`,
    gbt.id                              as `building_type`,
    1.0                                 as `by_hand`,
    @mental                             as `characteristic`,
    0                                   as `start`
from game_building_type as gbt ;

#------------------------------------------------------------------------------
# Points de recherche en bâtiment (en fonction du type)
#------------------------------------------------------------------------------

# Les points acquis par les personnages
CREATE TABLE `game_skill_research` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `type`      int(11)     NOT NULL,
    `character` int(11)     NOT NULL,
    `amount`    double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`type`)  REFERENCES `game_building_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

# quelle compétence fait gagner quel point
CREATE TABLE `game_skill_architect` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `type`      int(11)     NOT NULL,
    `skill`     int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`type`)  REFERENCES `game_building_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`skill`) REFERENCES `game_skill_skill`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Etude depuis les plans (dans un bureau)
#------------------------------------------------------------------------------

INSERT INTO game_skill_skill
    (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `start`)
select
    concat("StudyMap", gbt.`name`) as `name`,
    "StudyMap"                as `classname`,
    @architect                     as `building_job`,
    null                           as `building_type`,
    1.0                            as `by_hand`,
    @mental                        as `characteristic`,
    0                              as `start`
from game_building_type as gbt ;

INSERT INTO game_skill_architect
    (`type`, `skill`)
select
    gbt.id as `type`,
    gss.id as `skill`
from
    game_building_type as gbt,
    game_skill_skill as gss
where
    gss.`name` = concat("StudyMap", gbt.`name`) ;


#------------------------------------------------------------------------------
# Faire des recherches
#------------------------------------------------------------------------------

# Les compétences
INSERT INTO game_skill_skill
    (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `start`)
select
    concat("Research", gbt.`name`) as `name`,
    "Research"                     as `classname`,
    @architect                     as `building_job`,
    null                           as `building_type`,
    1.0                            as `by_hand`,
    @mental                        as `characteristic`,
    0                              as `start`
from game_building_type as gbt ;

INSERT INTO game_skill_architect
    (`type`, `skill`)
select
    gbt.id as `type`,
    gss.id as `skill`
from
    game_building_type as gbt,
    game_skill_skill as gss
where
    gss.`name` = concat("Research", gbt.`name`) ;
