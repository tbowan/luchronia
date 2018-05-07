#------------------------------------------------------------------------------
# Une livraison est-elle le fait des admins ?
#------------------------------------------------------------------------------

ALTER TABLE game_ressource_delivery
    add backoffice tinyint(1) not null ;
