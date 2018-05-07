SET @Library = (SELECT id FROM `game_building_job`   WHERE `name` = "Library") ;
SET @Mental  = (SELECT id FROM `game_characteristic` WHERE `name` = "Mental") ;
SET @fr = (SELECT id FROM `i18n_lang` WHERE `code` = "fr") ;

#------------------------------------------------------------------------------
# I. Table des apprentissages
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_learn` (
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
    concat("Learn", `name`) as `name`,
    "Learn"                 as `classname`,
    @Library                as `building_job`,
    null                    as `building_type`,
    1.0                     as `by_hand`,
    @Mental                 as `characteristic`,
    1                       as `start`,
    0                       as buyable
FROM game_characteristic ;

#------------------------------------------------------------------------------
# III. Lien entre compétence et caractéristique
#------------------------------------------------------------------------------

INSERT INTO game_skill_learn
    (`skill`, `characteristic`)
SELECT
    s.id as `skill`,
    c.id as `characteristic`
FROM
    game_characteristic as c,
    game_skill_skill    as s
WHERE
    s.`name` = concat("Learn", c.`name`) ;

#------------------------------------------------------------------------------
# I.V. Ajout des compétences aux personnages déjà existants
#------------------------------------------------------------------------------

INSERT INTO game_skill_character
    (`skill`, `character`, `uses`, `mastery`, `level`, `learning`)
SELECT
    s.id    as `skill`,
    c.id    as `character`,
    0       as `uses`,
    0       as `mastery`,
    1       as `level`,
    100     as `learning`
FROM
    game_character   as c,
    game_skill_skill as s
WHERE
    s.`classname` = "Learn" and
    s.`start` ;

#------------------------------------------------------------------------------
# V. Traduction du nom
#------------------------------------------------------------------------------

INSERT INTO i18n_translation
    (`key`, `lang`, `translation`)
SELECT
    concat("SKILL_Learn", c.`name`)              as `key`,
    @fr                                     as `lang`,
    concat("Apprendre - ", i.`translation`) as `translation`
FROM
    game_characteristic as c,
    i18n_translation    as i
WHERE
    i.`key` = concat("CHARACTERISTIC_NAME_", c.`name`) ;

#------------------------------------------------------------------------------
# VI. Traduction de la description
#------------------------------------------------------------------------------

INSERT INTO i18n_translation
    (`key`, `lang`, `translation`)
SELECT
    concat("SKILL_DESCRIPTION_Learn", c.`name`)  as `key`,
    @fr                                     as `lang`,
    "<p>Les compétences d'apprentissage permettent d'approfondir les connaissances des compétences. Elles sont utilisées lors de la lecture de livres et lorsqu'un maître enseigne.</p>" as `translation`
FROM
    game_characteristic as c ;
