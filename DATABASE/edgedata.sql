

CREATE DATABASE /*!32312 IF NOT EXISTS*/ edgedata;
USE edgedata;



DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_username` varchar(500) NOT NULL DEFAULT '',
  `admin_password` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


INSERT INTO `admin` (`admin_id`,`admin_username`,`admin_password`) VALUES 
 (1,'admin','admin');



DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(5000) NOT NULL DEFAULT '',
  `item_price` double DEFAULT NULL,
  `item_image` varchar(5000) NOT NULL DEFAULT '',
  `item_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;


INSERT INTO `items` (`item_id`,`item_name`,`item_price`,`item_image`,`item_date`) VALUES 
 (5,'Item2 ',100,'147124.jpg','2016-11-10'),
 (6,'Item3',50,'181757.jpg','2016-11-10'),
 (7,'Item4',60,'783298.jpg','2016-11-10'),
 (8,'Item5',55,'14231.jpg','2016-11-10'),
 (9,'Item6',90,'289865.jpg','2016-11-10'),
 (11,'Item1',40,'722934.jpg','2016-11-10'),
 (12,'Item101',1000,'838084.jpg','2016-11-14'),
 (13,'Item102',500,'320199.jpg','2016-11-14'),
 (14,'Item103',300,'361204.jpg','2016-11-14'),
 (15,'Item105',500,'444526.jpg','2016-11-14'),
 (16,'Item106',600,'956983.jpg','2016-11-14'),
 (17,'Item107',300,'855187.jpg','2016-11-14'),
 (18,'Item108',400,'45968.jpg','2016-11-14'),
 (19,'item909',50.5,'158191.jpg','2016-11-14');



CREATE TABLE `orderdetails` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_name` varchar(1000) NOT NULL DEFAULT '',
  `order_price` double NOT NULL DEFAULT '0',
  `order_quantity` int(10) unsigned NOT NULL DEFAULT '0',
  `order_total` double NOT NULL DEFAULT '0',
  `order_status` varchar(45) NOT NULL DEFAULT '',
  `order_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`order_id`),
  KEY `FK_orderdetails_1` (`user_id`),
  CONSTRAINT `FK_orderdetails_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;


INSERT INTO `orderdetails` (`order_id`,`user_id`,`order_name`,`order_price`,`order_quantity`,`order_total`,`order_status`,`order_date`) VALUES 
 (20,4,'Item2 ',100,2,200,'Ordered_Finished','2016-11-14'),
 (23,4,'Item2 ',100,3,300,'Ordered_Finished','2016-11-14'),
 (30,4,'Item2 ',100,1,100,'Ordered','2016-11-15'),
 (32,4,'Item4',60,2,120,'Ordered','2016-11-15');


CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(1000) NOT NULL,
  `user_password` varchar(1000) NOT NULL,
  `user_firstname` varchar(1000) NOT NULL,
  `user_lastname` varchar(1000) NOT NULL,
  `user_address` varchar(1000) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


INSERT INTO `users` (`user_id`,`user_email`,`user_password`,`user_firstname`,`user_lastname`,`user_address`) VALUES 
 (1,'gebb.freelancer@gmail.com','gebbz03','Gebb','Ebero','Badas'),
 (3,'gebb.sage@gmail.com','gebbz03','sdffs','adad','ssad'),
 (4,'mik@gmail.com','mik','Gebb','Ebero','Badas');
