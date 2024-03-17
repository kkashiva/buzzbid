
DROP DATABASE IF EXISTS `cs6400_sp24_team055`;

CREATE DATABASE IF NOT EXISTS cs6400_sp24_team055;

USE cs6400_sp24_team055;


/*Tables*/
CREATE TABLE `User` (
  user_name varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  PRIMARY KEY (user_name)
);

CREATE TABLE `AdminUser` (
user_name varchar(50) NOT NULL,
    	position varchar(100) NULL,
    	PRIMARY KEY (user_name)
);

CREATE TABLE `RegularUser` (
user_name varchar(50) NOT NULL,
    	PRIMARY KEY (user_name)
);

CREATE TABLE `Item` (
item_ID int(16) NOT NULL AUTO_INCREMENT,
listed_by varchar(20) NOT NULL,
item_name varchar(100) NOT NULL,
description varchar(250),
returnable boolean NOT NULL,
item_condition varchar(20) NOT NULL,
category varchar(20) NOT NULL,
PRIMARY KEY(item_ID)
);

CREATE TABLE `Category` (
category_name varchar(20) NOT NULL,
PRIMARY KEY(category_name)
);

CREATE TABLE `Auction` (
item_ID int(16) NOT NULL,
starting_bid double NOT NULL,
min_sale_price double NOT NULL,
getit_now_price double,
auction_length int(10) NOT NULL,
scheduled_end_time datetime NOT NULL,
actual_end_time datetime,
sale_price double,
winner varchar(20),
canceled_time datetime,
canceled_by varchar(20),
cancelation_reason varchar(250),
PRIMARY KEY(item_ID)
);


CREATE TABLE `ItemRating` (
rated_by varchar(50) NOT NULL,
item_ID int(16) NOT NULL,
stars int(5) NOT NULL,
comment varchar(1000),
rate_date_time datetime NOT NULL,
PRIMARY KEY (rated_by,item_ID,rate_date_time)
);


CREATE TABLE `ItemBid` (
bid_by varchar(50) NOT NULL,
item_ID int(16) NOT NULL,
bid_amount double NOT NULL,
time_of_bid datetime NOT NULL,
PRIMARY KEY (bid_by,item_ID)
);

/*Constraints*/
ALTER TABLE AdminUser
  ADD CONSTRAINT fk_AdminUser_username_User_username FOREIGN KEY (user_name) 
REFERENCES `User` (user_name);
  
ALTER TABLE RegularUser
  ADD CONSTRAINT fk_RegularUser_username_User_username FOREIGN KEY (user_name)
REFERENCES `User` (user_name);

ALTER TABLE Item 
ADD CONSTRAINT fk_Item_listedBy_User_username FOREIGN KEY
(listed_by) REFERENCES `User` (user_name);

ALTER TABLE Auction ADD CONSTRAINT fk_Auction_itemID_Item_itemID FOREIGN KEY
(item_ID) REFERENCES `item` (item_ID),
ADD CONSTRAINT chk_Auction_auctionLength
CHECK (auction_length IN (1,3,5,7));

ALTER TABLE Item 
ADD CONSTRAINT fk_Item_category_Category_categoryName 
FOREIGN KEY (category) REFERENCES `category` (category_name),
ADD CONSTRAINT chk_Item_itemCondition
CHECK (item_condition IN ('New','Very Good','Good','Fair','Poor'));

ALTER TABLE ItemRating
ADD CONSTRAINT fk_ItemRating_itemID_Item_item_ID FOREIGN KEY (item_ID) 
REFERENCES Item (item_ID),
ADD CONSTRAINT fk_ItemRating_rated_by_User_username FOREIGN KEY (rated_by) 
REFERENCES User (user_name);

ALTER TABLE ItemBid
ADD CONSTRAINT fk_ItemBid_itemID_Item_itemID FOREIGN KEY (item_ID)
REFERENCES Item (item_ID),
ADD CONSTRAINT fk_ItemBid_bid_by_User_username FOREIGN KEY (bid_by) 
REFERENCES User (user_name);



