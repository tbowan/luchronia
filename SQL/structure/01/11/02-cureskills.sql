
SET @hospital   = (SELECT id from game_building_job     where name = "Hospital"     ) ;
SET @doctor     = (SELECT id from game_skill_metier     where name = "Doctor"       ) ;
SET @perception = (SELECT id from game_characteristic   where name = "Perception"   ) ;

#------------------------------------------------------------------------------
# Création de la table des soins
#------------------------------------------------------------------------------

CREATE TABLE `game_skill_heal` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `skill`     int(11) NOT NULL,
    `race`      int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`skill`)    REFERENCES `game_skill_skill` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

#------------------------------------------------------------------------------
# Création des compétences de soins
#------------------------------------------------------------------------------

insert into game_skill_skill
    (`name`, `classname`, `building_job`, `building_type`, `by_hand`, `characteristic`, `cost`, `metier`)
values
    ("HealHuman",       "Heal", @hospital, null, 1.0, @perception, 1, @doctor),
    ("HealSelenite",    "Heal", @hospital, null, 1.0, @perception, 1, @doctor),
    ("HealCyborg",      "Heal", @hospital, null, 1.0, @perception, 1, @doctor)
 ;

insert into game_skill_heal
    (`skill`, `race`)
select id, 1 from game_skill_skill where name = "HealHuman" union
select id, 2 from game_skill_skill where name = "HealCyborg" union
select id, 3 from game_skill_skill where name = "HealSelenite"
 ;

#------------------------------------------------------------------------------
# Livres pour apprendre
#------------------------------------------------------------------------------

insert into game_ressource_item
    (`name`, `groupable`, `energy`)
select
    concat("Book", s.name) as name,
    1 as groupable,
    0 as energy
from
    game_skill_skill as s
where
    s.classname = "Heal" ;

insert into game_ressource_book
    (`item`, `skill`)
select
    i.id as item,
    s.id as skill
from
    game_skill_skill as s,
    game_ressource_item as i
where
    s.classname = "Heal" and
    i.name = concat("Book", s.name) ;

#------------------------------------------------------------------------------
# Parchemins pour apprendre
#------------------------------------------------------------------------------
insert into game_ressource_item
    (`name`, `groupable`, `energy`)
select
    concat("Parchment", s.name) as name,
    1 as groupable,
    0 as energy
from
    game_skill_skill as s
where
    s.classname = "Heal" ;


insert into game_ressource_parchment
    (`item`, `skill`)
select
    i.id as item,
    s.id as skill
from
    game_skill_skill as s,
    game_ressource_item as i
where
    s.classname = "Heal" and
    i.name = concat("Parchment", s.name) ;
