
#------------------------------------------------------------------------------
# Le listener pour recevoir un mail lors de clotures de transactions commerciales
#------------------------------------------------------------------------------

insert into event_listening (event, listener) values
    ("Social_Commerce_Item", "SocialCommerceItem") ;
insert into event_listening (event, listener) values
    ("Social_Commerce_Skill", "SocialCommerceSkill") ;