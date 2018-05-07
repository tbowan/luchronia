SET @Academy  = (SELECT id FROM `game_building_job`   WHERE `name` = "Academy") ;
SET @Charisma = (SELECT id FROM `game_characteristic` WHERE `name` = "Charisma") ;
SET @fr = (SELECT id FROM `i18n_lang` WHERE `code` = "fr") ;

#------------------------------------------------------------------------------
# I. Table des enseignements
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_teach` (
    `id`             int(11)      NOT NULL AUTO_INCREMENT,
    `characteristic` int(11)      NOT NULL,
    `skill`          int(11)      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`characteristic`)  REFERENCES `game_characteristic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`skill`)           REFERENCES `game_skill_skill`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# II. Les compétences
#------------------------------------------------------------------------------

INSERT INTO game_skill_skill
    (`name`, `classname`,  `building_job`,  `building_type`,  `by_hand`,  `characteristic`,  `start`,  `buyable`)
SELECT
    concat("Teach", `name`) as `name`,
    "Teach"                 as `classname`,
    @Academy                as `building_job`,
    null                    as `building_type`,
    1.0                     as `by_hand`,
    @Charisma               as `characteristic`,
    0                       as `start`,
    0                       as buyable
FROM game_characteristic ;

#------------------------------------------------------------------------------
# III. Lien entre compétence et caractéristique
#------------------------------------------------------------------------------

INSERT INTO game_skill_teach
    (`skill`, `characteristic`)
SELECT
    s.id as `skill`,
    c.id as `characteristic`
FROM
    game_characteristic as c,
    game_skill_skill as s
WHERE
    s.`name` = concat("Teach", c.`name`) ;

#------------------------------------------------------------------------------
# IV. Traduction du nom
#------------------------------------------------------------------------------

INSERT INTO i18n_translation
    (`key`, `lang`, `translation`)
SELECT
    concat("SKILL_Teach", c.`name`) as `key`,
    @fr as `lang`,
    concat("Enseigner - ", i.`translation`) as `translation`
FROM
    game_characteristic as c,
    i18n_translation as i
WHERE
    i.`key` = concat("CHARACTERISTIC_NAME_", c.`name`) ;

#------------------------------------------------------------------------------
# V. Traduction de la description
#------------------------------------------------------------------------------

INSERT INTO i18n_translation
    (`key`, `lang`, `translation`)
SELECT
    concat("SKILL_DESCRIPTION_Teach", c.`name`) as `key`,
    @fr as `lang`,
    "<p>Les compétences d'enseignement permettent au personnage d'apprendre ses compétences à un autre personnage et ainsi de transmettre son savoir.</p>" as `translation`
FROM
    game_characteristic as c ;

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
    s.`classname` = "Teach" and
    not s.`start` ;

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
    s.`classname` = "Teach" and
    i.`name` = concat("Parchment", s.`name`) ;

#------------------------------------------------------------------------------
# V.3. Parchemins : traduction
#------------------------------------------------------------------------------

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
    s.`classname` = "Teach" and
    i.`key` = concat("SKILL_", s.`name`) ;


#------------------------------------------------------------------------------
# VI.1. Livres : les items
#------------------------------------------------------------------------------

INSERT INTO game_ressource_item
    (`name`, `groupable`)
SELECT
    concat("Book", s.`name`) as `name`,
    0 as `groupable`
FROM
    game_skill_skill as s
WHERE
    s.`classname` = "Teach" and
    not s.`start` ;

#------------------------------------------------------------------------------
# VI.2. Livres : lien entre compétence et item
#------------------------------------------------------------------------------

INSERT INTO game_ressource_book
    (`item`, `skill`)
SELECT
    i.id as item,
    s.id as skill
FROM
    game_skill_skill as s,
    game_ressource_item as i
WHERE
    s.`classname` = "Teach" and
    i.`name` = concat("Book", s.`name`) ;

#------------------------------------------------------------------------------
# VI.3. Livres : traduction
#------------------------------------------------------------------------------

INSERT INTO i18n_translation
    (`key`, `lang`, `translation`)
SELECT
    concat("ITEM_Book", s.`name`) as `key`,
    @fr as `lang`,
    concat("Livre - ", i.`translation`) as `translation`
FROM
    game_skill_skill as s,
    i18n_translation as i
WHERE
    s.`classname` = "Teach" and
    i.`key` = concat("SKILL_", s.`name`) ;

