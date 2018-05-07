
#------------------------------------------------------------------------------
# Table des jetons de reinitialisation
#------------------------------------------------------------------------------

CREATE TABLE `identity_authentication_reinit` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `user`      int(11) NOT NULL,
    `token`     varchar(16) NOT NULL,
    `until`     int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`)        REFERENCES `identity_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Durée de validité d'un token
#------------------------------------------------------------------------------

INSERT INTO quantyl_config (`key`, `type`, `value`) values
    ("AUTH_REINIT_UNTIL", "Integer", 300) ;
