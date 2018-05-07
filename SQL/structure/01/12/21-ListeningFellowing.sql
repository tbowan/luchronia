#------------------------------------------------------------------------------
# Le listener pour recevoir un mail lorsqu'on a des suivi d'actualité
#------------------------------------------------------------------------------

insert into event_listening (event, listener) values
    ("Social_Fellow", "SocialFellow") ;
insert into event_listening (event, listener) values
    ("Social_Unfellow", "SocialUnfellow") ;