
#------------------------------------------------------------------------------
# Row for last seen timestamp
#------------------------------------------------------------------------------

ALTER TABLE game_city
    add `last_seen` int(11) NULL ;

#------------------------------------------------------------------------------
# data from stats
#------------------------------------------------------------------------------

UPDATE game_city set last_seen =
    (
        select max(last_visit) as last_seen
        from stats_game_moves
        where city = game_city.id
        group by game_city.id
    ) ;