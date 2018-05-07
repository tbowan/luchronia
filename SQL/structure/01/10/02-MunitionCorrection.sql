
#------------------------------------------------------------------------------
# On garde les anciennes données dans une "nouvelle table"
#------------------------------------------------------------------------------

RENAME TABLE game_ressource_munition TO tempo ;

#------------------------------------------------------------------------------
# Création de la nouvelle table
#------------------------------------------------------------------------------


CREATE TABLE `game_ressource_munition` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `tool`      int(11)     NOT NULL,
    `item`      int(11)     NOT NULL,
    `amount`    int(11)     NOT NULL,
    `coef`      int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tool`)    REFERENCES `game_skill_tool`        (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)    REFERENCES `game_ressource_item`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Création des nouvelles données
#------------------------------------------------------------------------------

INSERT INTO game_ressource_munition
    (`tool`, `item`, `amount`, `coef`)
SELECT
    `tool`.`id`         as `tool`,
    `tempo`.`munition`  as `item`,
    `tempo`.`amount`    as `amount`,
    `tempo`.`coef`      as `coef`
FROM
    tempo,
    game_skill_tool as tool,
    game_skill_skill as skill
where
    tempo.weapon = tool.item and
    tool.skill = skill.id and
    skill.name != "Bayonet" ;

#------------------------------------------------------------------------------
# Suppression des anciennes données
#------------------------------------------------------------------------------

drop table tempo ;
