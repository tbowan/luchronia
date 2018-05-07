
#------------------------------------------------------------------------------
# Quand le système doit envoyer des mails
#------------------------------------------------------------------------------

ALTER TABLE identity_user
    # ----- Social notifications
    ADD mailon_mail             tinyint(1) not null,
    ADD mailon_parcel           tinyint(1) not null,
    ADD mailon_friendship       tinyint(1) not null,
    ADD mailon_following        tinyint(1) not null,
    ADD mailon_wall             tinyint(1) not null,
    # ----- Forum notification
    ADD mailon_forum_answer     tinyint(1) not null,
    ADD mailon_forum_thread     tinyint(1) not null,
    ADD mailon_forum_follow     tinyint(1) not null,
    # ----- Game commerce notification
    ADD mailon_commerce_item    tinyint(1) not null,
    ADD mailon_commerce_skill   tinyint(1) not null,
    # ----- Luchronia News
    ADD mailon_blog             tinyint(1) not null,
    ADD mailon_newsletter       tinyint(1) not null
;

#------------------------------------------------------------------------------
# Suivi des posts du forumgénéral
#------------------------------------------------------------------------------

CREATE TABLE `forum_follow_category` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `user`      int(11) NOT NULL,
    `category`  int(11) NOT NULL,
    `last`      int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`)        REFERENCES `identity_user`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`category`)    REFERENCES `forum_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`last`)        REFERENCES `forum_post`     (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;

CREATE TABLE `forum_follow_thread` (
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `user`      int(11) NOT NULL,
    `thread`    int(11) NOT NULL,
    `last`      int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`)        REFERENCES `identity_user`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`thread`)      REFERENCES `forum_thread`   (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`last`)        REFERENCES `forum_post`     (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB ;
