
/***** CREATION DES TABLES *****/
DROP TABLE Affectation;
DROP TABLE Candidate;
DROP TABLE Task;
DROP TABLE Event;
DROP TABLE Event_new;
DROP TABLE Schedule;
DROP TABLE Skill;
DROP TABLE Location;
DROP TABLE Users;


CREATE TABLE Location (
CP DECIMAL(5)
CONSTRAINT PK_Location PRIMARY KEY,
town CHAR VARYING(40) NOT NULL
);

CREATE TABLE usersT (
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL UNIQUE
);

CREATE TABLE Users (
loginUser CHAR VARYING(20)
CONSTRAINT PK_Users PRIMARY KEY,
firstname CHAR VARYING(20),
lastname CHAR VARYING(20),
birth DATE NOT NULL,
mailUser CHAR VARYING(40) NOT NULL
CONSTRAINT DOM_mailUser CHECK (mailUser LIKE '%@%'),
phoneUser CHAR VARYING (12) NOT NULL
CONSTRAINT DOM_phoneUser CHECK (phoneUser LIKE '+33%'),
password TEXT NOT NULL UNIQUE
);

CREATE TABLE Schedule (
hDeb CHAR VARYING(5),
hFin CHAR VARYING(5),
CONSTRAINT PK_Schedule PRIMARY KEY (hDeb, hFin)
);

CREATE TABLE Skill (
nameSkill CHAR VARYING(30)
CONSTRAINT PK_Skill PRIMARY KEY
);

CREATE TABLE Event_new (
idEvent INTEGER CONSTRAINT PK_Event PRIMARY KEY AUTOINCREMENT,
nameEvent CHAR VARYING(30) NOT NULL,
dateEvent DATE NOT NULL,
deadline DATE NOT NULL,
typeEvent CHAR VARYING(20) NOT NULL,
descriptionEvent CHAR VARYING (2000) NOT NULL,
road CHAR VARYING(30) NOT NULL,
mailEvent CHAR VARYING(40) NOT NULL
CONSTRAINT DOM_mailEvent CHECK (mailEvent LIKE '%@%'),
website CHAR VARYING(40),
picture CHAR VARYING(40),
CP DECIMAL(5)
CONSTRAINT FK_Event_Ref_Location REFERENCES Location(CP),
loginUser CHAR VARYING(20)
CONSTRAINT FK_Event_Ref_Users REFERENCES Users(loginUser)
);

CREATE TABLE Event(
idEvent INTEGER CONSTRAINT PK_Event PRIMARY KEY AUTOINCREMENT,
nameEvent CHAR VARYING(30) NOT NULL,
dateEvent DATE NOT NULL,
deadline DATE NOT NULL,
typeEvent CHAR VARYING(20) NOT NULL,
descriptionEvent CHAR VARYING (2000) NOT NULL,
road CHAR VARYING(30) NOT NULL,
mailEvent CHAR VARYING(40) NOT NULL
CONSTRAINT DOM_mailEvent CHECK (mailEvent LIKE '%@%'),
website CHAR VARYING(40),
picture CHAR VARYING(40),
CP DECIMAL(5)
CONSTRAINT FK_Event_Ref_Location REFERENCES Location(CP),
loginUser CHAR VARYING(20)
CONSTRAINT FK_Event_Ref_Users REFERENCES Users(loginUser)
);



CREATE TABLE Task (
idTask DECIMAL(5),
idEvent DECIMAL(5)
CONSTRAINT FK_Task_Ref_Event REFERENCES Event(idEvent),
nameTask CHAR VARYING(30) NOT NULL,
nameResp CHAR VARYING(20) NOT NULL,
phoneResp CHAR VARYING NOT NULL
CONSTRAINT DOM_phoneResp CHECK (phoneResp LIKE '+33%'),
CONSTRAINT PK_Task PRIMARY KEY (idTask, idEvent)
);

CREATE TABLE Candidate (
idEvent DECIMAL(5)
CONSTRAINT FK_Candidate_Ref_Event REFERENCES Event(idEvent),
loginUSer CHAR VARYING(20)
CONSTRAINT FK_Candidate_Ref_Users REFERENCES Users(loginuser),
CONSTRAINT PK_Candidate PRIMARY KEY (idEvent, loginUSer)
);

CREATE TABLE Affectation (
idEvent DECIMAL(5)
CONSTRAINT FK_Affectation_Ref_Event REFERENCES Event(idEvent),
idTask DECIMAL(5)
CONSTRAINT FK_Affectation_Ref_Task REFERENCES Task(idTask),
hDeb CHAR VARYING(5)
CONSTRAINT FK_Affectation_Ref_Schedule REFERENCES Schedule(hDeb),
hFin CHAR VARYING(5)
CONSTRAINT FK_Affectation_Ref_Schedule REFERENCES Schedule(hFin),
nameSkill CHAR VARYING(20)
CONSTRAINT FK_Affectation_Ref_Skill REFERENCES Skill(nameSkill),
nbBenevole DECIMAL(3) NOT NULL,
loginUser CHAR VARYING(20)
CONSTRAINT FK_Affectation_Ref_Users REFERENCES Users(loginUser),
CONSTRAINT PK_Affectation PRIMARY KEY (idEvent, idTask, hDeb, hFin, nameSkill)
);




/*INSERT INTO Event(nameEvent, dateEvent, deadline,typeEvent, descriptionEvent, road, mailEvent, website, CP, loginUSer) VALUES ("testEvent", 04/05/2020, 20/03/2020, "sport", "évenement factice pour test fonctionnement du site", "bdprincipal", "aubertsab13@gmail.com", "http://pageperso.lif.univ-mrs.fr/bertrand.estellon/index.php", 13009, "tata"); */


/*DELETE FROM Users Where loginUser='doudou';*/


/*INSERT INTO Event(nameEvent, dateEvent, deadline,typeEvent, descriptionEvent, road, mailEvent, website, CP, loginUSer) VALUES ("testFuvelaine", 10/01/2021, 20/09/2020, "sport", "Test basé sur le fuvealines pour continuer de tester le fonctionnement du site", "foret des planes", "test@gmail.com", "https://courirafuveau.fr/", 13710, "tata"); */









/*DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS photos;
DROP TABLE IF EXISTS users;

CREATE TABLE albums(
  album_id INTEGER PRIMARY KEY AUTOINCREMENT,  
  album_name TEXT NOT NULL
);

CREATE TABLE photos(
  photo_id INTEGER PRIMARY KEY AUTOINCREMENT, 
  album_id INTEGER NOT NULL, 
  photo_name TEXT NOT NULL,
  fullsize BLOB TEXT NOT NULL,
  thumbnail BLOB TEXT NOT NULL,
  FOREIGN KEY(album_id) REFERENCES albums(album_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL UNIQUE
);*/




