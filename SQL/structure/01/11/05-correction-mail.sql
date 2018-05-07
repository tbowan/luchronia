
#------------------------------------------------------------------------------
# Nom des boites mail est un varchar
#------------------------------------------------------------------------------

ALTER TABLE game_post_mailbox
    change `name` `name` varchar(32) ;

update game_post_mailbox set `name` = "" where name = "0" ;
