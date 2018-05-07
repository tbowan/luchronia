
SET @fr = (SELECT id FROM `i18n_lang` WHERE `code` = "fr") ;

#------------------------------------------------------------------------------
# Parchemins du secteur primaire (ils n'était pas encore disponibles)
#------------------------------------------------------------------------------

INSERT INTO game_ressource_item
    (`name`, `groupable`)
SELECT
    concat("Parchment", s.`name`) as `name`,
    0 as `groupable`
FROM
    game_skill_skill as s
WHERE
    s.buyable and
    not s.`start` ;

INSERT INTO i18n_translation
    (`key`, `lang`, `translation`)
SELECT
    concat("ITEM_Parchment", s.`name`) as `key`,
    @fr as `lang`,
    concat("Parchemin - ", i.`translation`) as `translation`
FROM
    game_skill_skill as s,
    i18n_translation as i
WHERE
    s.buyable and
    i.`key` = concat("SKILL_", s.`name`) ;

INSERT INTO game_ressource_parchment
    (`item`, `skill`)
SELECT
    i.id as item,
    s.id as skill
FROM
    game_skill_skill as s,
    game_ressource_item as i
WHERE
    s.buyable and
    i.`name` = concat("Parchment", s.`name`) ;