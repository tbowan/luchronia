
#------------------------------------------------------------------------------
# Date de notification des posts du blog
#------------------------------------------------------------------------------

alter table blog_post
    add notified int(11) null ;


#------------------------------------------------------------------------------
# Le listener pour pour les posts du blog
#------------------------------------------------------------------------------

insert into event_listening (event, listener) values
    ("Blog_Published", "Blog") ;