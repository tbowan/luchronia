
#------------------------------------------------------------------------------
# Compétences pour exhumer les ruines
#------------------------------------------------------------------------------


INSERT INTO game_ressource_item
    (`name`, `groupable`)
VALUES
    ("ParchmentSearchOutside", 0),
    ("ParchmentSearchRuin", 0),
    ("ParchmentSearchExcavation", 0) ;

INSERT INTO game_ressource_parchment
    (`item`, `skill`)
SELECT
    i.id as item,
    s.id as skill
FROM
    game_ressource_item as i,
    game_skill_skill as s
WHERE
    s.classname = "Search" and
    i.name like concat("Parchment", s.name) ;
