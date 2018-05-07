#------------------------------------------------------------------------------
# Message (text) et date d'envoi
#------------------------------------------------------------------------------

ALTER TABLE game_post_parcel
    change message message text not null,
    add sended int(11) not null ;

