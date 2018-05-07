
#------------------------------------------------------------------------------
# Suppression des outils
#------------------------------------------------------------------------------

create view `tools` as
select
    i.id as id,
    i.`name` as `name`
from
    game_ressource_item as i,
    game_skill_tool as t
where
    i.id = t.item ;
    

# 1. Traductions

delete from i18n_translation
using
    i18n_translation
    join tools
    where
        i18n_translation.`key` like concat("%", tools.name , "%")
    ;

# 2. items

delete from
    game_ressource_item
using
    game_ressource_item
    join tools on tools.id = game_ressource_item.id
    ;

drop view tools ;

#------------------------------------------------------------------------------
# Suppression des compétences
#------------------------------------------------------------------------------

create view `useless_skill` as
select
    s.id as `id`,
    s.`name` as `name`
from
    game_skill_skill as s
left join
    game_skill_out as o
on o.skill = s.id
where
    s.classname = "Secondary" and
    isnull(o.id)
;

create view `useless_item` as
select
    i.id as id,
    i.`name` as `name`
from
    game_ressource_item as i,
    game_ressource_book as b,
    game_ressource_parchment as p,
    useless_skill as u
where
    (p.skill = u.id and i.id = p.item) or
    (b.skill = u.id and i.id = b.item)
group by
    i.id ;

# 1. Traductions

delete from i18n_translation
using
    i18n_translation
    join useless_skill
    where
        i18n_translation.`key` like concat("%", useless_skill.name , "%")
    ;

delete from i18n_translation
using
    i18n_translation
    join useless_item
    where
        i18n_translation.`key` like concat("%", useless_item.name , "%")
    ;

# 2. items (parchment et book)

delete from game_ressource_item
using game_ressource_item
join useless_item
on game_ressource_item.id = useless_item.id ;

# 3. Skills

delete from game_skill_skill
using game_skill_skill
join useless_skill
on game_skill_skill.id = useless_skill.id ;

drop view useless_skill, useless_item ;
