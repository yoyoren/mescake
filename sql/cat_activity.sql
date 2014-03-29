 create table cat_activity(
 id int(32) not null primary key auto_increment,
 weibo_name varchar(32) not null,
 img varchar(256) not null,
 status int(4) not null,
 times int(32) not null,
 add_time varchar(32) not null
);

ALTER TABLE cat_activity ADD add_time varchar( 32 ) NOT NULL DEFAULT '0';