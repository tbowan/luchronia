#------------------------------------------------------------------------------
# Le listener pour recevoir un mail lorsqu'un thread suivi d'un forum est mis à jour
#------------------------------------------------------------------------------

insert into event_listening (event, listener) values
    ("Social_Forum_Follow", "SocialForumFollow") ;
insert into event_listening (event, listener) values
    ("Social_Game_Forum_Follow", "Social_Game_Forum_Follow") ;