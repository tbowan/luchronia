
delete from game_skill_skill where
    `name` like "%draw%" and
    `id`   not in (select skill from game_building_map)
    ;
