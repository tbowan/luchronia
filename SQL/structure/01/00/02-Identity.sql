
CREATE TABLE `identity_user` (
  `id`            int(11)     NOT NULL AUTO_INCREMENT,
  `email`         varchar(64) NOT NULL,
  `first_name`    varchar(32) NOT NULL,
  `last_name`     varchar(32) NOT NULL,
  `sex`           char(1)     NOT NULL,
  `birth`         int(11)     NOT NULL,
  `address`       varchar(38) NOT NULL,
  `address_compl` varchar(38) NOT NULL,
  `code`          varchar(10) NOT NULL,
  `city`          varchar(64) NOT NULL,
  `state`         varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

#------------------------------------------------------------------------------
# CGVU
#------------------------------------------------------------------------------

CREATE TABLE `identity_cgvu` (
    `id`       int(11)     NOT NULL AUTO_INCREMENT,
    `file`     varchar(32) NOT NULL,
    `inserted` int(11)     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE `identity_accepted` (
  `id`   int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `cgvu` int(11) NOT NULL,
  `ip`   text    NOT NULL,
  `when` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user`) REFERENCES `identity_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`cgvu`) REFERENCES `identity_cgvu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Authentication
#------------------------------------------------------------------------------

CREATE TABLE `identity_authentication_luchronia` (
    `id`       int(11)     NOT NULL AUTO_INCREMENT,
    `user`     int(11)     NOT NULL,
    `nickname` varchar(16) NOT NULL,
    `password` char(128)   NOT NULL,
    `salt`     varchar(16) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `nickname` (`nickname`),
    FOREIGN KEY (`user`) REFERENCES `identity_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Groups and roles
#------------------------------------------------------------------------------


CREATE TABLE `identity_group` (
  `id`          int(11)     NOT NULL AUTO_INCREMENT,
  `name`        varchar(32) NOT NULL,
  `description` text        NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;


CREATE TABLE `identity_role` (
  `id`    int(11) NOT NULL AUTO_INCREMENT,
  `user`  int(11) NOT NULL,
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user`)  REFERENCES `identity_user` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`group`) REFERENCES `identity_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
