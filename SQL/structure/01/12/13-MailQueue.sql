#------------------------------------------------------------------------------
# Mails à envoyer
#------------------------------------------------------------------------------

CREATE TABLE `mail_queue` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `from`      varchar(64)     NOT NULL,
    `to`        varchar(64)     NOT NULL,
    `subject`   varchar(255)    not null,
    `content`   text            NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;
