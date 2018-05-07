#------------------------------------------------------------------------------
# Le listener pour recevoir un mail lorsqu'on a des demandes d'amis
#------------------------------------------------------------------------------

insert into event_listening (event, listener) values
    ("Social_Friendship_Request", "SocialFriendshipRequest") ;
insert into event_listening (event, listener) values
    ("Social_Friendship_Accept", "SocialFriendshipAccept") ;
insert into event_listening (event, listener) values
    ("Social_Friendship_Refuse", "SocialFriendshipRefuse") ;
insert into event_listening (event, listener) values
    ("Social_Friendship_Suppress", "SocialFriendshipSuppress") ;