CREATE DATABASE dbmccoy;
USE dbmccoy;
CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, login VARCHAR(45) NULL, passwd VARCHAR(35) NULL, PRIMARY KEY (id));
insert into users (login,passwd) values ('admin',md5('lambay'));
insert into users (login,passwd) values ('john',md5('doe'));
insert into users (login,passwd) values ('flag_S3ri3ux_m4n_f4ut_f1ltr3r_l3s_1nputs', 'flag_P0wn3d_Y0L0');
CREATE TABLE messages (id INT NOT NULL AUTO_INCREMENT, idmsg VARCHAR(45) NULL, msg VARCHAR(2000) NULL, PRIMARY KEY (id));
insert into messages (idmsg,msg) values ('673489', 'flag{T0uj0urs_f1ltr3r_l3s_1nputs}'); 
insert into messages (idmsg,msg) values ('892345', 'Penser à breveter le system d authentification');
