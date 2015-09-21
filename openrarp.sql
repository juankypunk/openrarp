CREATE SEQUENCE user_seq;
CREATE TABLE users (
user_id		integer NOT NULL PRIMARY KEY DEFAULT nextval('user_seq'),
name		varchar(50) NOT NULL,
email 		varchar(60) NOT NULL,
password	varchar(60),
social_id 	varchar(100),
picture 	varchar(250),
created 	timestamp DEFAULT CURRENT_TIMESTAMP
);
