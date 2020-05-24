
/***** CREATION DES TABLES *****/
DROP TABLE Missions;
DROP TABLE Disponibilite;
DROP TABLE Qualification;
DROP TABLE Skill;
DROP TABLE Schedule;
DROP TABLE Composition;
DROP TABLE Task;
DROP TABLE Event;
DROP TABLE Users;
DROP TABLE Location;



CREATE TABLE Location (
CP DECIMAL(5)
CONSTRAINT PK_Location PRIMARY KEY,
town CHAR VARYING(40) NOT NULL
);

CREATE TABLE Users (
loginUser CHAR VARYING(20) UNIQUE
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

CREATE TABLE Event (
idEvent INTEGER CONSTRAINT PK_Event PRIMARY KEY AUTOINCREMENT,
nameEvent CHAR VARYING(30) NOT NULL UNIQUE,
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
idTask INTEGER CONSTRAINT PK_Task PRIMARY KEY AUTOINCREMENT,
nameTask CHAR VARYING(30) NOT NULL UNIQUE
);

CREATE TABLE Composition (
idEvent INTEGER
CONSTRAINT FK_Composition_Ref_Event REFERENCES Event(idEvent),
nameEvent CHAR VARYING(30)
CONSTRAINT FK_Composition_Ref_Event REFERENCES Event(nameEvent),
idTask INTEGER
CONSTRAINT FK_Composition_Ref_Task REFERENCES Task(idTask),
nameTask CHAR VARYING(30)
CONSTRAINT FK_Composition_Ref_Task REFERENCES Task(nameTask),
nameResp CHAR VARYING(20) NOT NULL,
phoneResp CHAR VARYING NOT NULL
CONSTRAINT DOM_phoneResp CHECK (phoneResp LIKE '+33%'),
CONSTRAINT PK_Affectation PRIMARY KEY (idEvent, idTask)
);

CREATE TABLE Schedule (
idSchedule INTEGER CONSTRAINT PK_Shedule PRIMARY KEY AUTOINCREMENT,
hDeb CHAR VARYING(5),
hFin CHAR VARYING(5)
);

CREATE TABLE Skill (
nameSkill CHAR VARYING(30)
CONSTRAINT PK_Skill PRIMARY KEY
);

CREATE TABLE Qualification(
loginUser CHAR VARYING(20)
CONSTRAINT FK_Qualification_Ref_Users REFERENCES Users(loginUser),
nameSkill CHAR VARYING(30)
CONSTRAINT FK_Qualification_Ref_Skill REFERENCES Skill(nameSkill),
CONSTRAINT PK_Qualification PRIMARY KEY (loginUser, nameSkill)
);

CREATE TABLE Disponibilite(
loginUser CHAR VARYING(20)
CONSTRAINT FK_Qualif_Ref_Users REFERENCES Users(loginUser),
idSchedule INTEGER
CONSTRAINT FK_Qualif_Ref_Sche REFERENCES Schedule(idSchedule),
CONSTRAINT PK_Qualif PRIMARY KEY (loginUser, idSchedule)
);

CREATE TABLE Missions(
nameSkill CHAR VARYING(30)
CONSTRAINT FK_Missions_Ref_Skill REFERENCES Skill(nameSkill),
idSchedule INTEGER
CONSTRAINT FK_Missions_Ref_Sche REFERENCES Schedule(idSchedule),
idTask INTEGER
CONSTRAINT FK_Missions_Ref_Task REFERENCES Task(idTask),
nbBenevoles INTEGER,
CONSTRAINT PK_Missions PRIMARY KEY (nameSkill,idSchedule,idTask)
);


CREATE VIEW DispoPourMission AS
	SELECT loginUser, D.idSchedule, idTask
	FROM disponibilite D JOIN missions M ON D.idSchedule=M.idSchedule;

CREATE VIEW CompetentPourMission AS
	SELECT loginUser, Q.nameSkill, idTask
	FROM qualification Q JOIN missions M ON Q.nameSkill=M.nameSkill;



/*
INSERT INTO Task (nameTask) VALUES ("barrierage"); 

INSERT INTO Task (nameTask) VALUES ("accueil"); 
*/
/*
INSERT INTO Composition (idTask, idEvent, nameTask, nameEvent, nameResp, phoneResp) VALUES (1, 2,"barrierage",   "ekiden", "karim", "+33121234567");
INSERT INTO Composition (idTask, idEvent, nameTask, nameEvent, nameResp, phoneResp) VALUES (2, 2,"accueil",  "ekiden", "coco", "+33144444567");
*/
/*
INSERT INTO Schedule (hDeb, hFin) VALUES ( "8", "10");
INSERT INTO Schedule (hDeb, hFin) VALUES ( "10", "12");
INSERT INTO Schedule (hDeb, hFin) VALUES ( "10", "13");
*/
/*
INSERT INTO Skill (nameSkill) VALUES("port de charges");
INSERT INTO Skill (nameSkill) VALUES("permis de conduire");
INSERT INTO Skill (nameSkill) VALUES("bonne volonte");
*/
/*
INSERT INTO qualification ( loginUser, nameSkill) VALUES ("titi", "port de charges");
INSERT INTO qualification ( loginUser, nameSkill) VALUES ("titi", "bonne volonté");
INSERT INTO qualification ( loginUser, nameSkill) VALUES ("titi", "permis de conduire");
INSERT INTO qualification ( loginUser, nameSkill) VALUES ("toto", "port de charges");
INSERT INTO qualification ( loginUser, nameSkill) VALUES ("zaza", "permis de conduire");
INSERT INTO qualification ( loginUser, nameSkill) VALUES ("dudule", "port de charges");
INSERT INTO qualification ( loginUser, nameSkill) VALUES ("machin", "bonne volonte");
*/
/*
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("titi", 1);
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("titi", 2);
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("toto", 1);
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("zaza", 1);
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("zaza", 2);
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("dudule", 2);
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("machin", 1);
INSERT INTO disponibilite ( loginUser, idSchedule) VALUES ("machin",3);
*/
/*
INSERT INTO missions ( nameSkill,  idSchedule, idTask, nbBenevoles) VALUES( "port de charges",  1, 1, 2);
INSERT INTO missions ( nameSkill,  idSchedule, idTask, nbBenevoles) VALUES( "permis de conduire", 1, 1, 1);
INSERT INTO missions ( nameSkill,  idSchedule, idTask, nbBenevoles) VALUES("port de charges", 2, 1, 2);
INSERT INTO missions ( nameSkill,  idSchedule, idTask, nbBenevoles) VALUES("permis de conduire", 2, 1, 1);
INSERT INTO missions ( nameSkill,  idSchedule, idTask, nbBenevoles) VALUES("bonne volonte", 1, 2, 1);
INSERT INTO missions ( nameSkill,  idSchedule, idTask, nbBenevoles) VALUES("bonne volonte", 3, 2, 1);
*/


/*
SELECT DISTINCT loginUser
FROM DispoPourMission
WHERE idTask=1 AND idSchedule=1;

SELECT DISTINCT loginUser
FROM CompetentPourMission
WHERE idTask=1 AND nameSkill="port de charges";

SELECT DISTINCT loginUser
FROM DispoPourMission
WHERE idTask=1 AND idSchedule=1
INTERSECT 
SELECT DISTINCT loginUser
FROM CompetentPourMission
WHERE idTask=1 AND nameSkill="port de charges";

*/

/* 
SELECT S.idSchedule, hDeb, hFin, nameEvent
FROM (Missions M JOIN Composition C ON M.idTask = C.idTask) JOIN Schedule S ON S.idSchedule = M.idSchedule
WHERE nameEvent = "Ekiden"; 

*/












