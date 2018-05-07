
update game_ressource_eatable
    join game_ressource_item
        on game_ressource_eatable.item = game_ressource_item.id
    set game_ressource_eatable.energy = 84
    where game_ressource_item.name in (
        "AvoroPancakes",
        "LichojPancakes")
    ;