
#------------------------------------------------------------------------------
# 1. Les compétences
#------------------------------------------------------------------------------

SET @Perception     = (SELECT id from game_characteristic where name = "Perception"     ) ;
SET @Explorer       = (SELECT id from game_skill_metier   where name = "Explorer"       ) ;
SET @Archaeologist  = (SELECT id from game_skill_metier   where name = "Archaeologist"  ) ;

INSERT INTO game_skill_skill
    (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `cost`, `metier`)
VALUES
    ("ProspectGround",      "ProspectGround",       null, null, 1.0, @Perception, 1, @Explorer),
    ("ProspectUnderground", "ProspectUnderground",  null, null, 1.0, @Perception, 1, @Explorer),
    ("ProspectArcheo",      "ProspectArcheo",       null, null, 1.0, @Perception, 1, @Archaeologist)
 ;


#------------------------------------------------------------------------------
# 2. Parchemins
#------------------------------------------------------------------------------

INSERT INTO game_ressource_item
    (`name`)
VALUES
    ("ParchmentProspectGround"),
    ("ParchmentProspectUnderground"),
    ("ParchmentProspectArcheo") ;

INSERT INTO game_ressource_parchment
    (`item`, `skill`)
SELECT
    i.id as item,
    s.id as skill
FROM
    game_skill_skill as s,
    game_ressource_item as i
WHERE
    s.`name` in ("ProspectGround", "ProspectUnderground", "ProspectArcheo") and
    i.`name` = concat("Parchment", s.`name`) ;

#------------------------------------------------------------------------------
# 3. Livres
#------------------------------------------------------------------------------

INSERT INTO game_ressource_item
    (`name`)
VALUES
    ("BookProspectGround"),
    ("BookProspectUnderground"),
    ("BookProspectArcheo") ;

INSERT INTO game_ressource_book
    (`item`, `skill`)
SELECT
    i.id as item,
    s.id as skill
FROM
    game_skill_skill as s,
    game_ressource_item as i
WHERE
    s.`name` in ("ProspectGround", "ProspectUnderground", "ProspectArcheo") and
    i.`name` = concat("Book", s.`name`) ;
