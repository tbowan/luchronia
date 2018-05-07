
#------------------------------------------------------------------------------
# I. Suppression des anciens coûts
#------------------------------------------------------------------------------

drop table game_building_cost ;

#------------------------------------------------------------------------------
# II. Création des nouvelles tables
#------------------------------------------------------------------------------


#------------------------------------------------------------------------------
# II.1. Coût d'accès à la ville
#------------------------------------------------------------------------------

CREATE TABLE `game_tax_city` (
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `city`  int(11) NOT NULL,
    `fix`   double  NOT NULL,
    `var`   double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)  REFERENCES `game_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# II.2. Coût d'accès au bâtiment
#------------------------------------------------------------------------------

CREATE TABLE `game_tax_building` (
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `city`  int(11) NOT NULL,
    `job`   int(11) NOT NULL,
    `fix`   double  NOT NULL,
    `var`   double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)  REFERENCES `game_city`            (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`job`)   REFERENCES `game_building_job`    (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# II.3. Coût d'accès au corporations / métiers
#------------------------------------------------------------------------------

CREATE TABLE `game_tax_metier` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `city`      int(11) NOT NULL,
    `metier`    int(11) NOT NULL,
    `fix`       double  NOT NULL,
    `var`       double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)    REFERENCES `game_city`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`metier`)  REFERENCES `game_skill_metier`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# II.4. Coût d'accès au compétences
#------------------------------------------------------------------------------

CREATE TABLE `game_tax_skill` (
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `city`  int(11) NOT NULL,
    `skill` int(11) NOT NULL,
    `fix`   double  NOT NULL,
    `var`   double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)  REFERENCES `game_city`        (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`skill`) REFERENCES `game_skill_skill` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# II.5. Coût d'accès au étrangers
#------------------------------------------------------------------------------

CREATE TABLE `game_tax_stranger` (
    `id`    int(11) NOT NULL AUTO_INCREMENT,
    `city`  int(11) NOT NULL,
    `fix`   double  NOT NULL,
    `var`   double  NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city`)  REFERENCES `game_city`            (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
