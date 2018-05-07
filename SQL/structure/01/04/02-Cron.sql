
#------------------------------------------------------------------------------
# Table pour les infos des scripts en cron
#------------------------------------------------------------------------------

CREATE TABLE `stats_script` (
    `id`         int(11)      NOT NULL AUTO_INCREMENT,
    `hostname`   varchar(32)  NOT NULL,
    `script`     varchar(80)  NOT NULL,
    `start`      int(11)      NOT NULL,
    `percent`    double       NOT NULL,
    `end`        int(11)          NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;