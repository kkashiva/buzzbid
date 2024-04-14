CREATE TABLE `User` (
  `username` VARCHAR(1024),
  `first_name` VARCHAR(1024),
  `last_name` VARCHAR(1024),
  `password` VARCHAR(1024),
  `position` VARCHAR(1024) NULL
);

INSERT INTO `User` (`username`,`first_name`,`last_name`,`password`,`position`)
VALUES
('ablack','Alex','Black','1234',NULL),
('admin1','Riley','Fuiss','opensesame','Technical Support'),
('admin2','Tonnis','Kinser','opensesayou','Chief Techy'),
('apink','Alice','Pink','1234',NULL),
('jbrian','James','O''Brian','1234',NULL),
('jgreen','John','Green','1234',NULL),
('jsmith','John','Smith','1234',NULL),
('mred','Michael','Red','12345','CEO'),
('o''brian','Jack','Brian','1234',NULL),
('pbrown','Peter','Brown','1234',NULL),
('Pink','apink','Alice','1234',NULL),
('porange','Peter','Orange','1234',NULL),
('tblue','Tom','Blue','1234',NULL),
('trichards','Tom','Richards','1234',NULL),
('user1','Danite','Kelor','pass1',NULL),
('user2','Dodra','Kiney','pass2',NULL),
('user3','Peran','Bishop','pass3',NULL),
('user4','Randy','Roran','pass4',NULL),
('user5','Ashod','Iankel','pass5',NULL),
('user6','Cany','Achant','pass6',NULL);
