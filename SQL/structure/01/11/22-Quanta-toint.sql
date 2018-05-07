

#------------------------------------------------------------------------------
# Quantité entière pour les utilisateurs
#------------------------------------------------------------------------------

ALTER TABLE identity_user
    CHANGE quanta quanta int(11) not null ;
