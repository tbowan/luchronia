
#------------------------------------------------------------------------------
# Durée de vie bonus des sessions
#------------------------------------------------------------------------------

ALTER TABLE quantyl_session
    ADD `lifetime` int(11) not null ;
