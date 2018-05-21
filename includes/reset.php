<?php
die("reset desactivado"); //comentar esta línea para activar esta función reset
//include 'db_connect.php';
try {
	$query = "drop table users; drop table events; drop table classes; drop table login_attempts; drop table diplomas;

	create table users (id int not null primary key auto_increment,
	 username varchar(40) not null,
	 email varchar(40),
	 tlmcoin varchar(30),
	 password char(40) not null,
	 random_salt char(40) not null);

	create table login_attempts (user_id int not null primary key,
	 date_time datetime not null);

	create table classes (id int not null primary key auto_increment,
	 course varchar(100) not null,
	 lesson varchar(100) not null,
	 price decimal(8,2) not null,
	 datetime_start datetime not null,
	 datetime_end datetime not null,
	 diploma_oid int(11),
	 diploma_secret varchar(16));

	create table events (id int not null primary key auto_increment,
	 date_time datetime not null,
	 class_id int not null,
	 course varchar(100) not null,
	 user_id int not null,
	 teacher tinyint(1));

	create table diplomas (id int not null primary key auto_increment,
	 diploma_oid int(11),
	 diploma_secret varchar(16),
	 entregado tinyint(1) not null default 0,
	 date_time datetime,
	 user_id int(11) not null,
	 class_id int(11) not null);

	INSERT INTO users(id,username,email,tlmcoin,password,random_salt) VALUES (1,'america','america@vengador.es','20404-a6e7b0cce9c5e1621500','72221d83ef0f74707edb52e6ab7eb310410ee8b5','bade051d4cdb525f4ccc71d020110ccfc11e0963');
	INSERT INTO users(id,username,email,tlmcoin,password,random_salt) VALUES (2,'hulk','','20403-c5765f1fff4f286192ae','c9cd7e232373c947887ee832ad2fd88f2de565b9','0bcc64cec39d3a210a0ce6347884ea5691d74b03');
	INSERT INTO users(id,username,email,tlmcoin,password,random_salt) VALUES (3,'ironman','ironman@vengador.es','20405-62eeabc4c9a56e507887','4f43c5e0b063526ea6d61f0b217c1ddcbd8b1688','dd1095fe1293276c472af3e1053aef84ace5fe4c');
	INSERT INTO users(id,username,email,tlmcoin,password,random_salt) VALUES (4,'thor','thor@vengador.no','20406-53255fc6f259943c2c49','194f698c47c0c73644c1492509cd7973b83ca243','c83a5142ed069eea8e224d68d30d967a7f16b717');

	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (1,now(),1,'Halterofilia',2,1);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (2,now(),2,'Endurance',1,1);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (3,now(),3,'Senderismo',4,1);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (4,now(),1,'Halterofilia',1,0);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (5,now(),1,'Halterofilia',4,0);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (6,now(),1,'Halterofilia',3,0);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (7,now(),4,'Gimnasia',3,1);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (8,now(),2,'Endurance',2,0);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (9,now(),5,'Endurance',1,1);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (10,now(),5,'Endurance',2,0);
	INSERT INTO events(id,date_time,class_id,course,user_id,teacher) VALUES (11,now(),5,'Endurance',3,0);

	INSERT INTO classes(id,course,lesson,price,datetime_start,datetime_end,diploma_oid,diploma_secret) VALUES (1,'Halterofilia','#1: Levantar 5 kg',0.99,'2018-05-24 08:00:00','2018-05-24 09:00:00',1737,'5867bba3b86f55a0');
	INSERT INTO classes(id,course,lesson,price,datetime_start,datetime_end,diploma_oid,diploma_secret) VALUES (2,'Endurance','#1: Correr 1 hora',4.07,'2018-05-25 10:00:00','2018-05-25 11:00:00',1738,'7d71195a3a391c3e');
	INSERT INTO classes(id,course,lesson,price,datetime_start,datetime_end,diploma_oid,diploma_secret) VALUES (3,'Senderismo','#1: Andar 1 hora',0.99,'2018-05-22 08:00:00','2018-05-22 09:00:00',1739,'50373d5281d55546');
	INSERT INTO classes(id,course,lesson,price,datetime_start,datetime_end,diploma_oid,diploma_secret) VALUES (4,'Gimnasia','#1: Voltereta',0.99,'2018-05-27 17:00:00','2018-05-27 18:00:00',1740,'9ff258eb6429785d');
	INSERT INTO classes(id,course,lesson,price,datetime_start,datetime_end,diploma_oid,diploma_secret) VALUES (5,'Endurance','#2: Correr 2 horas',1.50,'2018-05-26 10:00:00','2018-05-26 12:00:00',1741,'889a8777ebd5cdc0');

	INSERT INTO diplomas(id,diploma_oid,diploma_secret,entregado,user_id,class_id) VALUES (1,1742,'47ec840563234959',0,1,1);
	INSERT INTO diplomas(id,diploma_oid,diploma_secret,entregado,user_id,class_id) VALUES (2,1744,'9eab2f79661081a4',0,4,1);
	INSERT INTO diplomas(id,diploma_oid,diploma_secret,entregado,user_id,class_id) VALUES (3,1747,'2b8a7cccc3038260',0,3,1);
	INSERT INTO diplomas(id,diploma_oid,diploma_secret,entregado,user_id,class_id) VALUES (4,1748,'245e50402504c2f5',0,2,2);
	INSERT INTO diplomas(id,diploma_oid,diploma_secret,entregado,user_id,class_id) VALUES (5,1746,'34623a82f8553224',0,2,5);
	INSERT INTO diplomas(id,diploma_oid,diploma_secret,entregado,user_id,class_id) VALUES (6,1745,'5227dc35471d0fb8',0,3,5);
	INSERT INTO diplomas(id,diploma_oid,diploma_secret,entregado,date_time,user_id,class_id) VALUES (7,1742,'ae09fb070a2710a4',1,now(),1,1);
	 ";

	$stmt = $pdo->prepare($query);
	$stmt->execute();
	echo 'base de datos reseteada';
} catch (Exception $e) {
    echo $e->getMessage();
}
?>