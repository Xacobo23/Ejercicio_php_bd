create table IF NOT EXISTS Student (
    id int auto_increment primary key,
    dni char(9), 
    name varchar(50),
    surname varchar(250), 
    age int
    );

insert into Student (dni, name, surname, age) 
values 
    ("11111111A","Draco","Malfoy",25),
    ("22222222B","Hermione","Granger",23),
    ("33333333C","Harry","Potter",20),
    ("44444444D","Ron","Weasley",22);