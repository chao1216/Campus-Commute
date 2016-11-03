/*
Chao Lin
Liz Zuniga
Maggie Koella
CS 284
SQL TABLE CREATION
*/

CREATE DATABASE campusCommute;

USE campusCommute;
 
CREATE TABLE userInfo(
   userId      INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,fName       VARCHAR(100)
  ,lName       VARCHAR(100)
  ,schoolId    INT
  ,userTypeId  INT
);

CREATE TABLE driverOrPassenger(
   userTypeId  INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,userType    VARCHAR(20)
);

CREATE TABLE contact(
   contactId  INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,phone      VARCHAR(20)
  ,email      VARCHAR(100)
  ,userId     INT
);

CREATE TABLE whoGoWhere(
   userId         INT
  ,destinationId  INT
);

CREATE TABLE destination(
   destinationId  INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,city           VARCHAR(100)
  ,state          VARCHAR(100)
  ,regionId       INT
);

CREATE TABLE university(
   schoolId  INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,campus    VARCHAR(100)
);

CREATE TABLE region(
  regionId  INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name      VARCHAR(50)
);


CREATE TABLE departure(
   dateId         INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,departureDate  date
);

CREATE TABLE whoDepartWhen(
   userId  INT
  ,dateId  INT
);

CREATE TABLE vehicleInfo(
   vehicleId  INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,type       VARCHAR(50)
);

CREATE TABLE whoOwnWhatVehicle(
   userId     INT
  ,vehicleId  INT
);

CREATE TABLE questions(
   questionId  INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,question    VARCHAR(100)
);

CREATE TABLE yesOrNoAnswer(
   y_n_Id   INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,answer     VARCHAR(10) 
);

CREATE TABLE numericAnswer(
   numId   INT AUTO_INCREMENT NOT NULL PRIMARY KEY
  ,answer  INT
);

CREATE TABLE response(
   questionId  INT
  ,y_n_Id      INT
  ,numId       INT
  ,userId      INT
);


/*Predefined table values*/

INSERT INTO driverOrPassenger(userType)
  VALUES("passenger"),
        ("driver"),
        ("both");

INSERT INTO numericAnswer(answer)
  VALUES(1),
        (2),
        (3),
        (4);

INSERT INTO yesOrNoAnswer(answer)
  VALUES("yes"),
        ("no");

INSERT INTO questions(question)
  VALUES("smokeQ"),
        ("gasQ"),
        ("driverQ"),
        ("petQ"),
        ("carSickQ"),
        ("luggage");

INSERT INTO vehicleInfo(type)
  VALUES("suv"),
        ("truck"),
        ("sedan"),
        ("van"),
        ("convertible"),
        ("coupe"),
        ("wagon"),
        ("other");

INSERT INTO region(name)
  VALUES('Northeast'),
        ('Northwest'),
        ('Southeast'),
        ('Southwest'),
        ('West'),
        ('Midwest');
