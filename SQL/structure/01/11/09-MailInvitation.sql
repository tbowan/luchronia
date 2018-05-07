

#------------------------------------------------------------------------------
# Table des invitations
#------------------------------------------------------------------------------

CREATE TABLE `identity_sponsor` (
    `id`        int(11)     NOT NULL AUTO_INCREMENT,
    `sponsor`   int(11)     NOT NULL,
    `date`      int(11)     NOT NULL,
    `mail`      varchar(64) NOT NULL,
    `message`   text        NOT NULL,
    `protege`    int(11)         NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`sponsor`)  REFERENCES `identity_user` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`protege`)  REFERENCES `identity_user` (`id`)   ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
