SET @fr = (SELECT id FROM `i18n_lang` WHERE `code` = "fr") ;

#------------------------------------------------------------------------------
# Table des Livres
#------------------------------------------------------------------------------

CREATE TABLE `game_ressource_book` (
    `id`         int(11)      NOT NULL AUTO_INCREMENT,
    `item`       int(11)      NOT NULL,
    `skill`      int(11)      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`skill`) REFERENCES `game_skill_skill`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Insertion des items "livres"
#------------------------------------------------------------------------------

INSERT INTO game_ressource_item
    (`name`, `groupable`)
SELECT
    concat("Book", game_skill_skill.`name`) as `name`,
    0 as `groupable`
FROM
    game_skill_skill
where
    not game_skill_skill.`start` ;

#------------------------------------------------------------------------------
# Lien entre livre et compétence
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
    i.`name` = concat("Book", s.`name`) ;

#------------------------------------------------------------------------------
# Traduction des items "livre"
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
    i.`key` = concat("SKILL_", s.`name`) ;
