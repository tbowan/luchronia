#------------------------------------------------------------------------------
# Reprise de l'apprentissage
#------------------------------------------------------------------------------

#------------------------------------------------------------------------------
# Lesqulles sont achetables ?
#------------------------------------------------------------------------------

ALTER TABLE game_skill_skill
    ADD buyable tinyint(1) NOT NULL ;

update game_skill_skill set buyable = true where classname = "primary" ;

#------------------------------------------------------------------------------
# diminue de 100 points l'apprentissage
#------------------------------------------------------------------------------

update game_skill_character set learning = learning - 100, `level` = greatest(`level`, 0) ;

#------------------------------------------------------------------------------
# rend le point de niveau pour les compétences achetées
#------------------------------------------------------------------------------

UPDATE
    game_character
INNER JOIN (
    SELECT
        gsc.`character` as `character`,
        count(gsc.id)   as point
    from
        game_skill_character as gsc,
        game_skill_skill as gss
    where
        gsc.skill = gss.id and
        not gss.`start` and
        not gss.`buyable`
    group by `character`
    ) as bonus
ON
    bonus.`character` = game_character.id
SET
    game_character.point = game_character.point + bonus.point
;
