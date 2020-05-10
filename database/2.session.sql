DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
    user_id integer unsigned NOT NULL,
    token varchar(100) NOT NULL,
    created_at datetime NOT NULL DEFAULT current_timestamp(),
    deleted_at datetime,
    INDEX session_IN_token (token),
    FOREIGN KEY session_FK_id_user (user_id) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Sessions table'