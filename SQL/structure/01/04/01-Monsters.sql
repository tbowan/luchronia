
#------------------------------------------------------------------------------
# Colonnes pour les monstres
#------------------------------------------------------------------------------

ALTER TABLE game_city
  ADD `monster_in`  double  NOT NULL,
  ADD `monster_out` double  NOT NULL,
  ADD `fitness`     double  NOT NULL,
  ADD `sunrise`     int(11) NOT NULL,
  ADD `sunset`      int(11) NOT NULL ;

UPDATE game_city
  SET monster_in = 20 * albedo ;

update game_city
  left join
     (select sum(`level`) as f, city as city from game_building_instance group by game_building_instance.city) as gbi
  on gbi.city = game_city.id
  set game_city.fitness = gbi.f
;


