-- ****************** SqlDBM: MySQL ******************;
-- ***************************************************;
DROP TABLE Ticket_Menu_Item;


DROP TABLE Ticket;


DROP TABLE Server;


DROP TABLE Menu_Item_Side;


DROP TABLE Store;


DROP TABLE Side;


DROP TABLE Menu_Item;


DROP TABLE Ticket;


DROP TABLE Server;


DROP TABLE Menu_Item_Side;


DROP TABLE Store;


DROP TABLE Side;


DROP TABLE Menu_Item;


COMMIT;

-- ************************************** Store

CREATE TABLE Store
(
 store_id  int GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1),
 store_num decimal NOT NULL ,
 city      varchar(45) NOT NULL ,
 state     varchar(45) NOT NULL ,
 zip       int NOT NULL ,
 phone_num varchar(45) NOT NULL ,
PRIMARY KEY (store_id)
);






-- ************************************** Side

CREATE TABLE Side
(
 side_id   int GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1),
 side_name varchar(45) NOT NULL ,
PRIMARY KEY (side_id)
);






-- ************************************** Menu_Item

CREATE TABLE Menu_Item
(
 menu_item_id int GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1),
 item_name    varchar(45) NOT NULL ,
<<<<<<< HEAD
 item_price   decimal NOT NULL ,
=======
 item_price   DECIMAL(5,2) NOT NULL ,
>>>>>>> f4e8e3f3466e17eadc3f5c40ba64e71d72c88232
PRIMARY KEY (menu_item_id)
);






-- ************************************** Server

CREATE TABLE Server
(
 server_id    int GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1),
 employee_num int NOT NULL ,
 first_name   varchar(45) NOT NULL ,
 last_name    varchar(45) NOT NULL ,
 start_date   date NOT NULL ,
 store_id     int NOT NULL ,
PRIMARY KEY (server_id),
CONSTRAINT FK_35 FOREIGN KEY (store_id) REFERENCES Store (store_id)
);






-- ************************************** Menu_Item_Side

CREATE TABLE Menu_Item_Side
(
 menu_item_side_id int GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1),
 menu_item_id      int NOT NULL ,
 side_id           int NOT NULL ,
PRIMARY KEY (menu_item_side_id),
CONSTRAINT FK_67 FOREIGN KEY (menu_item_id) REFERENCES Menu_Item (menu_item_id),
CONSTRAINT FK_75 FOREIGN KEY (side_id) REFERENCES Side (side_id)
);






-- ************************************** Ticket

CREATE TABLE Ticket
(
 ticket_id    int GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1),
 ticket_num   int NOT NULL ,
 ticket_date  date NOT NULL ,
 payment_type varchar(45) NOT NULL ,
<<<<<<< HEAD
 tip_amount   decimal NOT NULL ,
=======
 tip_amount   DECIMAL(5,2) NOT NULL ,
>>>>>>> f4e8e3f3466e17eadc3f5c40ba64e71d72c88232
 if_paid      varchar(45) NOT NULL ,
 server_id    int NOT NULL ,
PRIMARY KEY (ticket_id),
CONSTRAINT FK_46 FOREIGN KEY (server_id) REFERENCES Server (server_id)
);






-- ************************************** Ticket_Menu_Item

CREATE TABLE Ticket_Menu_Item
(
 Ticket_menu_item_id int GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1),
 ticket_id           int NOT NULL ,
 menu_item_id        int NOT NULL ,
PRIMARY KEY (Ticket_menu_item_id),
CONSTRAINT FK_52 FOREIGN KEY (ticket_id) REFERENCES Ticket (ticket_id),
CONSTRAINT FK_61 FOREIGN KEY (menu_item_id) REFERENCES Menu_Item (menu_item_id)
);


COMMIT;



