DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  id integer unsigned NOT NULL AUTO_INCREMENT,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(100) NOT NULL,
  description varchar(500),
  img varchar(100),
  created_at datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY user_PK (id),
  UNIQUE KEY user_UN_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User table'