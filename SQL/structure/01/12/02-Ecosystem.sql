
#------------------------------------------------------------------------------
# Validation de l'adresse mail des utilisateurs
#------------------------------------------------------------------------------

ALTER TABLE game_ressource_ecosystem 
    CHANGE `biome` `biome` int(11) NULL ;

INSERT INTO game_ressource_ecosystem
    (`item`, `biome`,  `min`, `max`)
SELECT
    id, null, 0, 1
FROM
    game_ressource_item
WHERE
    name in (
            "Sand",
            "Clay",
            "Limestone",
            "Coal",
            "IronOre",
            "Water"
            )
;