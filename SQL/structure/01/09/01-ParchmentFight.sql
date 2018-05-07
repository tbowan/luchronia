
#------------------------------------------------------------------------------
# V.1. Parchemins : les items
#------------------------------------------------------------------------------

INSERT INTO game_ressource_item
    (`name`, `groupable`)
SELECT
    concat("Parchment", s.`name`) as `name`,
    0 as `groupable`
FROM
    game_skill_skill as s
WHERE
    s.`classname` = "Fight" and
    s.`cost`      > 0 ;

#------------------------------------------------------------------------------
# V.2. Parchemins : lien entre compétence et item
#------------------------------------------------------------------------------

INSERT INTO game_ressource_parchment
    (`item`, `skill`)
SELECT
    i.id as item,
    s.id as skill
FROM
    game_skill_skill as s,
    game_ressource_item as i
WHERE
    s.`classname` = "Fight" and
    i.`name` = concat("Parchment", s.`name`) ;

#------------------------------------------------------------------------------
# V.3. Parchemins : traduction
#------------------------------------------------------------------------------
SET @fr = (SELECT id FROM `i18n_lang` WHERE `code` = "fr") ;

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
    s.`classname` = "Fight" and
    s.`cost`      > 0       and
    i.`key`       = concat("SKILL_", s.`name`) ;

