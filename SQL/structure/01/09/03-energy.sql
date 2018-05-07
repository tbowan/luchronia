#------------------------------------------------------------------------------
# Changement des gains d'énergie pour les plats et ressources
#  - le détail est dans le fichier doc
#------------------------------------------------------------------------------


update game_ressource_drinkable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_drinkable.item
    set game_ressource_drinkable.`energy` = 280
    where game_ressource_item.`name` like "%soup%" ;


update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 105
    where game_ressource_item.`name` like "%smoothie%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 105
    where game_ressource_item.`name` like "%milk%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 180
    where game_ressource_item.`name` like "%roasted%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 650
    where game_ressource_item.`name` like "%cereal%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 20
    where game_ressource_item.`name` like "%candy%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 25
    where game_ressource_item.`name` like "%lavocandy%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 760
    where game_ressource_item.`name` like "%candied%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 760
    where game_ressource_item.`name` like "%jam%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 90
    where game_ressource_item.`name` like "%cake%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 190
    where game_ressource_item.`name` like "%pancake%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 90
    where game_ressource_item.`name` in ("AvoroPancakes", "LichojPancakes") ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 270
    where
        game_ressource_item.`name` like "%gratin%" or
        game_ressource_item.`name` like "%steamed%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 180
    where game_ressource_item.`name` in ("Sugar") ;


# Cyborgs

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 50
    where game_ressource_item.`name` like "%Coal%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 700
    where game_ressource_item.`name` like "%Coke%" ;

# Selenites

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 50
    where game_ressource_item.`name` like "%sand%" ;

update game_ressource_eatable
    inner join game_ressource_item
    on game_ressource_item.id = game_ressource_eatable.item
    set game_ressource_eatable.`energy` = 50
    where game_ressource_item.`name` like "%iron%" ;

