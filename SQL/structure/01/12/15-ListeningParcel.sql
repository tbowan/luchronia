#------------------------------------------------------------------------------
# Le listener pour recevoir un mail lorsqu'on a un message privé
#------------------------------------------------------------------------------

insert into event_listening (event, listener) values
    ("Social_Parcel", "SocialParcel") ;