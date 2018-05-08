create table users (id int not null primary key auto_increment,
 username varchar(40) not null,
 email varchar(40),
 tlmcoin int(2),
 password char(40) not null,
 random_salt char(40) not null);

create table login_attempts (user_id int not null primary key,
 date_time datetime not null);

create table classes (id int not null primary key auto_increment,
 course varchar(100) not null,
 lesson varchar(100) not null,
 price decimal(8,2) not null,
 datetime_start datetime not null,
 datetime_end datetime not null);

create table events (id int not null primary key auto_increment,
 date_time datetime not null,
 class_id int not null,
 user_id int not null,
 teacher tinyint(1));

create table diplomas (id int not null primary key auto_increment,
 diploma_oid int(11),
 diploma_secret varchar(16),
 entregado tinyint(1) not null default 0);