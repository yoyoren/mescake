 create table mes_admin(
 id int(4) not null primary key auto_increment,
 username char(20) not null,
 password varchar(400) not null,
 type int(4) not null
);
#yuchengwebadmin_mescake
INSERT INTO mes_admin (username,password,type) values ('mescakewebadmin','0B6763665B8AB1786EA78780E590ABFB',1);