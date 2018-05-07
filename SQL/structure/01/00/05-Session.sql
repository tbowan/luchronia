#------------------------------------------------------------------------------
# Session
#------------------------------------------------------------------------------

CREATE TABLE `quantyl_session` (
    `id`           int(11)    NOT NULL AUTO_INCREMENT,
    `session_id`   char(128)  NOT NULL,
    `session_data` text       NOT NULL,
    `session_time` int(11)    NOT NULL,
    PRIMARY KEY (`id`),
    unique(`session_id`)
) ENGINE=InnoDB ;
