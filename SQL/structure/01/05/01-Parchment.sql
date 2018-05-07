
#------------------------------------------------------------------------------
# Table des livres pour apprendre
#------------------------------------------------------------------------------

ALTER TABLE `game_ressource_item`
    MODIFY COLUMN `name` varchar(64) NOT NULL ;

CREATE TABLE `game_ressource_parchment` (
    `id`         int(11)      NOT NULL AUTO_INCREMENT,
    `item`       int(11)      NOT NULL,
    `skill`      int(11)      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`skill`) REFERENCES `game_skill_skill`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
