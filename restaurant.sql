-- ****************** SqlDBM: MySQL ******************;
-- ***************************************************;

DROP TABLE `Ticket_Menu_Item`;


DROP TABLE `Ticket`;


DROP TABLE `Server`;


DROP TABLE `Menu_Item_Side`;


DROP TABLE `Store`;


DROP TABLE `Side`;


DROP TABLE `Menu_Item`;



-- ************************************** `Store`

CREATE TABLE `Store`
(
 `store_id`  int NOT NULL ,
 `store_num` int NOT NULL ,
 `city`      varchar(45) NOT NULL ,
 `state`     varchar(45) NOT NULL ,
 `zip`       int NOT NULL ,
 `phone_num` varchar(45) NOT NULL ,
PRIMARY KEY (`store_id`)
);






-- ************************************** `Side`

CREATE TABLE `Side`
(
 `side_id`   int NOT NULL ,
 `side_name` varchar(45) NOT NULL ,
PRIMARY KEY (`side_id`)
);






-- ************************************** `Menu_Item`

CREATE TABLE `Menu_Item`
(
 `menu_item_id` int NOT NULL ,
 `item_name`    varchar(45) NOT NULL ,
 `item_price`   double NOT NULL ,
PRIMARY KEY (`menu_item_id`)
);






-- ************************************** `Server`

CREATE TABLE `Server`
(
 `server_id`    int NOT NULL ,
 `employee_num` int NOT NULL ,
 `first_name`   varchar(45) NOT NULL ,
 `last_name`    varchar(45) NOT NULL ,
 `start_date`   date NOT NULL ,
 `store_id`     int NOT NULL ,
PRIMARY KEY (`server_id`),
KEY `fkIdx_35` (`store_id`),
CONSTRAINT `FK_35` FOREIGN KEY `fkIdx_35` (`store_id`) REFERENCES `Store` (`store_id`)
);






-- ************************************** `Menu_Item_Side`

CREATE TABLE `Menu_Item_Side`
(
 `menu_item_side_id` int NOT NULL ,
 `menu_item_id`      int NOT NULL ,
 `side_id`           int NOT NULL ,
PRIMARY KEY (`menu_item_side_id`),
KEY `fkIdx_67` (`menu_item_id`),
CONSTRAINT `FK_67` FOREIGN KEY `fkIdx_67` (`menu_item_id`) REFERENCES `Menu_Item` (`menu_item_id`),
KEY `fkIdx_75` (`side_id`),
CONSTRAINT `FK_75` FOREIGN KEY `fkIdx_75` (`side_id`) REFERENCES `Side` (`side_id`)
);






-- ************************************** `Ticket`

CREATE TABLE `Ticket`
(
 `ticket_id`    int NOT NULL ,
 `ticket_num`   int NOT NULL ,
 `ticket_date`  date NOT NULL ,
 `payment_type` varchar(45) NOT NULL ,
 `tip_amount`   double NOT NULL ,
 `if_paid`      bit NOT NULL ,
 `server_id`    int NOT NULL ,
PRIMARY KEY (`ticket_id`),
KEY `fkIdx_46` (`server_id`),
CONSTRAINT `FK_46` FOREIGN KEY `fkIdx_46` (`server_id`) REFERENCES `Server` (`server_id`)
);






-- ************************************** `Ticket_Menu_Item`

CREATE TABLE `Ticket_Menu_Item`
(
 `Ticket_menu_item_id` int NOT NULL ,
 `ticket_id`           int NOT NULL ,
 `menu_item_id`        int NOT NULL ,
PRIMARY KEY (`Ticket_menu_item_id`),
KEY `fkIdx_52` (`ticket_id`),
CONSTRAINT `FK_52` FOREIGN KEY `fkIdx_52` (`ticket_id`) REFERENCES `Ticket` (`ticket_id`),
KEY `fkIdx_61` (`menu_item_id`),
CONSTRAINT `FK_61` FOREIGN KEY `fkIdx_61` (`menu_item_id`) REFERENCES `Menu_Item` (`menu_item_id`)
);





