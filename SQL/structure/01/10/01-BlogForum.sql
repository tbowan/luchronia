
#------------------------------------------------------------------------------
# Blog : image et description
#------------------------------------------------------------------------------

ALTER TABLE blog_category
    ADD `description`   varchar(255)    NOT NULL,
    ADD `image`         varchar(255)    NOT NULL;

#------------------------------------------------------------------------------
# Forum : image
#------------------------------------------------------------------------------

ALTER TABLE forum_category
    CHANGE `description` `description` varchar(255) NOT NULL,
    ADD `image` varchar(255) NOT NULL ;
