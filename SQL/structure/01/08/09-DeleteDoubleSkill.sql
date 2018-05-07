#------------------------------------------------------------------------------
# Suppression compétences en double
#------------------------------------------------------------------------------

DELETE game_skill_skill
FROM game_skill_skill
LEFT JOIN (
    SELECT
        MIN(id) as id,
        `name` as `name`
    FROM
        game_skill_skill
    GROUP BY `name`
    ) as t1
    ON game_skill_skill.id = t1.id
WHERE isnull(t1.id) ;

#------------------------------------------------------------------------------
# Suppression parchemins sans compétence
#------------------------------------------------------------------------------

DELETE game_ressource_item
FROM game_ressource_item
    LEFT JOIN game_ressource_parchment
    ON game_ressource_parchment.item = game_ressource_item.id
WHERE
    isnull(game_ressource_parchment.id) and
    game_ressource_item.`name` like "parchment%" ;

#------------------------------------------------------------------------------
# Suppression items doublons (donc les livres)
#------------------------------------------------------------------------------

DELETE game_ressource_item
FROM game_ressource_item
LEFT JOIN (
    SELECT
        MIN(id) as id,
        `name` as `name`
    FROM
        game_ressource_item
    GROUP BY `name`
    ) as t1
    ON game_ressource_item.id = t1.id
WHERE isnull(t1.id) ;