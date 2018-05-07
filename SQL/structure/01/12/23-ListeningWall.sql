#------------------------------------------------------------------------------
# Le listener pour recevoir un mail lorsqu'un mur d'actualité est mis à jour
#------------------------------------------------------------------------------

insert into event_listening (event, listener) values
    ("Social_Wall_Publication", "SocialWallPublication") ;
insert into event_listening (event, listener) values
    ("Social_Wall_Comment", "SocialWallComment") ;
