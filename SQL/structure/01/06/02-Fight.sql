
#------------------------------------------------------------------------------
# Tous les bâtiments peuvent être barricadés
#------------------------------------------------------------------------------

ALTER TABLE game_building_instance
  ADD `barricade` double NOT NULL ;

ALTER table game_building_wall
  DROP COLUMN `defense` ;

#------------------------------------------------------------------------------
# Choix des compétences qui sont "de base"
#------------------------------------------------------------------------------

ALTER table game_skill_skill
  ADD `start` tinyint(1) NOT NULL ;

#------------------------------------------------------------------------------
# Ajout de la compétence pour se barricader
#------------------------------------------------------------------------------

SET @for = (SELECT id FROM `game_characteristic` WHERE `name` = "Strength") ;
SET @per = (SELECT id FROM `game_characteristic` WHERE `name` = "Perception") ;
SET @out = (SELECT id FROM `game_building_job`   WHERE `name` = "Outside") ;

INSERT INTO game_skill_skill
  (`name`, `classname`, building_job, building_type, by_hand, characteristic, `start`)
values
  ("Barricade", "Barricade", null, null,  1.0, @for, true),
  ("Fight",     "Fight",     @out, null,  0.1, @for, true),
  ("Archery",   "Fight",     @out, null, null, @for, false),
  ("Crossbow",  "Fight",     @out, null, null, @for, false),
  ("Pistol",    "Fight",     @out, null, null, @per, false),
  ("Rifle",     "Fight",     @out, null, null, @per, false),
  ("Bayonet",   "Fight",     @out, null, null, @for, false),
  ("Cane",      "Fight",     @out, null, null, @per, false),
  ("Foil",      "Fight",     @out, null, null, @per, false),
  ("Sword",     "Fight",     @out, null, null, @per, false),
  ("Saber",     "Fight",     @out, null, null, @for, false) ;

insert into game_skill_character
  (`skill`, `character`, `uses`, `mastery`, `level`, `learning`)
select
  gss.id    as `skill`,
  gc.id     as `character`,
  0         as `uses`,
  0         as `mastery`,
  1         as `level`,
  200       as `learning`
from
  game_skill_skill as gss,
  game_character as gc
where
  gss.start
;

#------------------------------------------------------------------------------
# Ajout de la compétence pour se barricader
#------------------------------------------------------------------------------

update game_skill_skill set `start` = true where `name` = "Walk" ;
  