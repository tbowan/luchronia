
#------------------------------------------------------------------------------
# Table des messages
#------------------------------------------------------------------------------

CREATE TABLE `game_post_mail` (
    `id`                int(11)     NOT NULL AUTO_INCREMENT,
    # From
    `author`            int(11)     NOT NULL,
    `city`              int(11)     NOT NULL,
    `personnal`         tinyint(1)  NOT NULL,
    # To - special
    `to_friend`         tinyint(1)  NOT NULL,
    `to_citizen`        tinyint(1)  NOT NULL,
    `to_present`        tinyint(1)  NOT NULL,
    # Content
    `title`             varchar(64) NOT NULL,
    `content`           text        NOT NULL,
    # Meta
    `created`           int(11)     NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`author`)  REFERENCES `game_character` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`city`)    REFERENCES `game_city` (`id`)       ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des destinataires
#------------------------------------------------------------------------------

CREATE TABLE `game_post_recipient` (
    `id`                int(11)     NOT NULL AUTO_INCREMENT,
    `character`         int(11)     NOT NULL,
    `mail`              int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`mail`)        REFERENCES `game_post_mail` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des dossiers de mails
#------------------------------------------------------------------------------

CREATE TABLE `game_post_mailbox` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `character`     int(11)     NOT NULL,
    `name`          int(11)     NOT NULL,
    `type`          int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`character`)   REFERENCES `game_character` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

INSERT INTO game_post_mailbox (`character`, `name`, `type`)
    select id as `character`, "" as `name`, 1 as `type` from `game_character` ;

INSERT INTO game_post_mailbox (`character`, `name`, `type`)
    select id as `character`, "" as `name`, 2 as `type` from `game_character` ;

#------------------------------------------------------------------------------
# Rangement des mails
#------------------------------------------------------------------------------

CREATE TABLE `game_post_inbox` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `mail`          int(11)     NOT NULL,
    `box`           int(11)     NOT NULL,
    `read`          int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`mail`)    REFERENCES `game_post_mail` (`id`)      ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`box`)     REFERENCES `game_post_mailbox` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Table des colis
#------------------------------------------------------------------------------

CREATE TABLE `game_post_parcel` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    # From
    `sender`        int(11)         NULL,
    `source`         int(11)     NOT NULL,
    # To
    `recipient`     int(11)     NOT NULL,
    `destination`   int(11)     NOT NULL,
    # Content
    `message`       int(11)     NOT NULL,
    `credits`       int(11)     NOT NULL,
    # journey
    `origin`        int(11)     NOT NULL,
    `t0`            int(11)     NOT NULL,
    `tf`            int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`sender`)      REFERENCES `game_character` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`source`)      REFERENCES `game_city` (`id`)       ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`recipient`)   REFERENCES `game_character` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`destination`) REFERENCES `game_city` (`id`)       ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`origin`)      REFERENCES `game_city` (`id`)       ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Contenu d'un coli
#------------------------------------------------------------------------------

CREATE TABLE `game_post_good` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `parcel`        int(11)     NOT NULL,
    `item`          int(11)     NOT NULL,
    `amount`        int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`parcel`)  REFERENCES `game_post_parcel` (`id`)      ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`item`)    REFERENCES `game_ressource_item` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Les bureaux de poste
#------------------------------------------------------------------------------

CREATE TABLE `game_building_post` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `instance`      int(11)     NOT NULL,
    `cost_mail`     double      NOT NULL,
    `cost_parcel`   double      NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`instance`)  REFERENCES `game_building_instance` (`id`)      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;