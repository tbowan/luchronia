#------------------------------------------------------------------------------
# Coût du rapatriement
#------------------------------------------------------------------------------

ALTER TABLE game_city
  ADD `repatriate_allowed` tinyint(1) NOT NULL,
  ADD `repatriate_cost`    double     NOT NULL ;
