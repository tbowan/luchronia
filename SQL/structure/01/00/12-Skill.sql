#------------------------------------------------------------------------------
# Skill : Base classes : a skill
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_skill` (
    `id`                int(11)     NOT NULL AUTO_INCREMENT,
    `name`              varchar(32) NOT NULL,
    `classname`         varchar(32) NOT NULL,
    `building_job`      int(11)         NULL,
    `building_type`     int(11)         NULL,
    `by_hand`           double          NULL,
    `characteristic`    int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`building_job`)
        REFERENCES `game_building_job` (`id`)
        ON DELETE CASCADE ON UPDATE SET NULL,
    FOREIGN KEY (`building_type`)
        REFERENCES `game_building_type` (`id`)
        ON DELETE CASCADE ON UPDATE SET NULL,
    FOREIGN KEY (`characteristic`)
        REFERENCES `game_characteristic` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB ;


#------------------------------------------------------------------------------
# Skill : character mastering a skill
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_character` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `skill`     int(11)     NOT NULL,
    `character` int(11)     NOT NULL,
    `uses`      int(11)     NULL,
    `mastery`   int(11)     NOT NULL,
    `level`     int(11)     NOT NULL,
    `learning`  double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`skill`)     REFERENCES `game_skill_skill` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`character`) REFERENCES `game_character`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Skill : items (in, out, tools)
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_in` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `skill`      int(11)     NOT NULL,
  `item`       int(11)     NOT NULL,
  `amount`     double      NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`skill`) REFERENCES `game_skill_skill`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_skill_out` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `skill`      int(11)     NOT NULL,
  `item`       int(11)     NOT NULL,
  `amount`     double      NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`skill`) REFERENCES `game_skill_skill`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `game_skill_tool` (
  `id`         int(11)     NOT NULL AUTO_INCREMENT,
  `skill`      int(11)     NOT NULL,
  `item`       int(11)     NOT NULL,
  `coef`       double      NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`skill`) REFERENCES `game_skill_skill`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`item`)  REFERENCES `game_ressource_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
