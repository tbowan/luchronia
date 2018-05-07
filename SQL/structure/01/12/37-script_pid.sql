
#------------------------------------------------------------------------------
# Row for last seen timestamp
#------------------------------------------------------------------------------

DELETE FROM stats_script ;

ALTER TABLE stats_script
    add `pid` int(11) NOT NULL ;
