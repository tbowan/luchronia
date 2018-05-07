
#------------------------------------------------------------------------------
# Les codes promotionnels
#------------------------------------------------------------------------------

CREATE TABLE `ecom_code_bonus` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `code`          varchar(32) NOT NULL,
    `from`          int(11)     NOT NULL,
    `to`            int(11)     NOT NULL,
    `amount`        int(11)     NOT NULL,
    `rate`          double      NOT NULL,
    `max_u`         int(11)     NOT NULL,
    `max_t`         int(11)     NOT NULL,
    `active`        tinyint(1)  NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Les utilisations
#------------------------------------------------------------------------------

CREATE TABLE `ecom_code_apply` (
    `id`            int(11)     NOT NULL AUTO_INCREMENT,
    `quanta`        int(11)     NOT NULL,
    `bonus`         int(11)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`quanta`) REFERENCES `ecom_quanta` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`bonus`)   REFERENCES `ecom_code_bonus`   (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;