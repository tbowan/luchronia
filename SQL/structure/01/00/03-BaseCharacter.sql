#------------------------------------------------------------------------------
# Base Character
# - pour permettre aux autres tables de référencer un "personnage"
# -> sera complétée par d'autres patchs
#------------------------------------------------------------------------------

CREATE TABLE `game_character` (
    `id`      int(11)     not null auto_increment,
    `name`    varchar(32) not null,
    `user`    int(11)     not null,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`) REFERENCES `identity_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

ALTER TABLE identity_user
    ADD `character` int(11) NULL,
    ADD FOREIGN KEY (`character`)
        REFERENCES game_character(`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE ;