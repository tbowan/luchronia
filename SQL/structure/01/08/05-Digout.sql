#------------------------------------------------------------------------------
# Compétences pour exhumer les ruines
#------------------------------------------------------------------------------

SET @Perception = (SELECT id FROM `game_characteristic` WHERE `name` = "Perception") ;
SET @Excavation = (SELECT id FROM `game_building_job` WHERE `name` = "Excavation") ;

INSERT INTO game_skill_skill (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `start`)
        VALUES ("Digout", "Digout", @Excavation, null, 1.0, @Perception, 0) ;

INSERT INTO game_ressource_item (`name`, `groupable`)
        VALUES ("ParchmentDigout", 0) ;

INSERT INTO game_ressource_parchment (`item`, `skill`)
SELECT
    i.id as item,
    s.id as skill
FROM
    game_ressource_item as i,
    game_skill_skill as s
WHERE
    s.classname = "Digout" and
    i.name like concat("Parchment", s.name) ;
