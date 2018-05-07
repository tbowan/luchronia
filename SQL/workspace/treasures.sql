#------------------------------------------------------------------------------
# Ajout dans la table des trésors de outils utilisés dans chaque batiment
#------------------------------------------------------------------------------

INSERT INTO game_ressource_treasure
    (`job`, `item`,  `amount`, `infinite`, `gained`)
SELECT
    j.id as job,
    i.id as itm,
    100 / i.energy as amount,
    true as infinite,
    1 as gained
FROM
    game_building_job as j,
    game_skill_skill as s,
    game_skill_tool as t,
    game_ressource_item as i
WHERE
    s.building_job = j.id and
    t.skill = s.id and
    t.item = i.id and

    j.technology > 0
GROUP BY job, itm 
;

#------------------------------------------------------------------------------
# Ajout dans la table des trésors des ressources obtenues dans les batiments du secteur primaire
#------------------------------------------------------------------------------

INSERT INTO game_ressource_treasure
    (`job`, `item`,  `amount`, `infinite`, `gained`)
SELECT
    j.id as job,
    i.id as itm,
    100 / i.energy as amount,
    true as infinity,
    1 as gained
FROM
    game_building_job as j,
    game_skill_skill as s,
    game_skill_primary as p,
    game_ressource_item as i
WHERE
    s.building_job = j.id and
    p.skill = s.id and
    p.out = i.id and

    j.technology > 0
GROUP BY job, itm ;

#------------------------------------------------------------------------------
# Ajout dans la table des trésors des ressources obtenues dans les batiments du secteur secondaire
#------------------------------------------------------------------------------

INSERT INTO game_ressource_treasure
    (`job`, `item`,  `amount`, `infinite`, `gained`)
SELECT
    j.id as job,
    i.id as itm,
    100 / i.energy as amount,
    true as infinity,
    1 as gained
FROM
    game_building_job as j,
    game_skill_skill as s,
    game_skill_out as o,
    game_ressource_item as i
WHERE
    s.building_job = j.id and
    o.skill = s.id and
    o.item = i.id and

    j.technology > 0
GROUP BY job, itm ;

#------------------------------------------------------------------------------
# Ajout dans la table des trésors des différents plans chez l'architecte
#------------------------------------------------------------------------------
INSERT INTO game_ressource_treasure
    (`job`, `item`,  `amount`, `infinite`, `gained`)
SELECT
    j.id as jb,
    i.id as itm,
    100 / i.energy as amount,
    true as infinity,
    1 as gained
FROM
    game_building_job as j,
    game_skill_skill as s,
    game_building_map as m,
    game_ressource_item as i
WHERE
    s.building_job = j.id and
    m.skill = s.id and
    m.item = i.id and

    j.technology > 0
GROUP BY jb, itm ;

#------------------------------------------------------------------------------
# Ajout dans la table des trésors des "gros" outils des batiments
#------------------------------------------------------------------------------
INSERT INTO game_ressource_treasure
    (`job`, `item`,  `amount`, `infinite`, `gained`)
SELECT
    j.id as jb,
    i.id as itm,
    100 / i.energy as amount,
    true as infinity,
    1 as gained
FROM
    game_building_job as j,
    game_building_complement as c,
    game_ressource_item as i
WHERE
    c.job = j.id and
    c.item = i.id and

    j.technology > 0
GROUP BY jb, itm ;

#------------------------------------------------------------------------------
# Ajout dans la table des trésors ressources utilisées pour la construction d'un type de batiment
#------------------------------------------------------------------------------

INSERT INTO game_ressource_treasure
    (`type`, `item`,  `amount`, `infinite`, `gained`)
SELECT
    t.id as typ,
    i.id as itm,
    100 / i.energy as amount,
    true as infinity,
    1 as gained
FROM
    game_building_type as t,
    game_building_construction as c,
    game_ressource_item as i
WHERE
    c.type = t.id and
    c.item = i.id 

GROUP BY typ, itm ;

#------------------------------------------------------------------------------
# Ajout dans la table des trésors ressources récupérables dans chaque biome
#------------------------------------------------------------------------------
INSERT INTO game_ressource_treasure
    (`biome`, `item`,  `amount`, `infinite`, `gained`)
SELECT 
    e.biome as biome,
    i.id as itm,
    100 / i.energy as amount,
    true as infinity,
    1 as gained
FROM
    game_ressource_ecosystem as e,
    game_ressource_item as i,
    game_skill_primary as p
WHERE
    e.item = p.in and
    p.out = i.id     

GROUP BY biome, itm;

#------------------------------------------------------------------------------
# Ajout dans la table des trésors des plans de construction dans les batiments correspondants
#------------------------------------------------------------------------------
INSERT INTO game_ressource_treasure
    (`job`, `type`, `item`,  `amount`, `infinite`, `gained`)
SELECT
    j.id as jb,
    t.id as typ,
    i.id as itm,
    100 / i.energy as amount,
    true as infinity,
    1 as gained
FROM
    game_building_job as j,
    game_building_type as t,
    game_building_map as m,
    game_ressource_item as i
WHERE 
    m.job = j.id and
    m.type = t.id and
    m.item = i.id and 

    j.technology > 0
GROUP BY jb, typ, itm ;