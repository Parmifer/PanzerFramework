CREATE DATABASE PanzerTest;

use PanzerTest;

create table role (
id int AUTO_INCREMENT,
label varchar(50),
clef char,
PRIMARY KEY(id)
);

insert into role (label, clef) values ('SUPER_ADMIN', 'S');
insert into role (label, clef) values ('ADMIN', 'A');
insert into role (label, clef) values ('USER', 'U');
insert into role (label, clef) values ('VISITEUR', 'V');

create table user (
id int AUTO_INCREMENT,
nom varchar(50),
adresse_email varchar(100),
ext_role int,
PRIMARY KEY(id),
FOREIGN KEY (ext_role) references role (id)
);

insert into user (nom, adresse_email, ext_role) values ('Crystale', 'canard@coin.coin', 1);
insert into user (nom, adresse_email, ext_role) values ('Parmifer', 'lardon@gras.gras', 2);