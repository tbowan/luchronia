#------------------------------------------------------------------------------
# Groups and roles
#------------------------------------------------------------------------------

create table `quantyl_config` (
    `id`      int(11)     not null auto_increment,
    `key`     varchar(32) not null,
    `type`    varchar(32) not null,
    `value`   text        not null,
    primary key (`id`),
    unique(`key`)
) ENGINE=InnoDB ;

INSERT INTO `quantyl_config` (`key`, `type`, `value`) VALUES ("schema_version", "Integer", "") ;