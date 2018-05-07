
#------------------------------------------------------------------------------
# Suppression des anciennes tables
#------------------------------------------------------------------------------

drop table game_quest_listener ;
drop table game_quest_event ;

#------------------------------------------------------------------------------
# Nouvelle table de gestion des events
#------------------------------------------------------------------------------

CREATE TABLE `event_listening` (
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `event`      varchar(32) NOT NULL,
    `listener`   varchar(32) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;
