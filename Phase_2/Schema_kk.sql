CREATE TABLE `User` (
  UserName varchar(50) NOT NULL,
  Password varchar(50) NOT NULL,
  FirstName varchar(50) NOT NULL,
  LastName varchar(50) NOT NULL,
  PRIMARY KEY (UserName)
);

CREATE TABLE RegularUser (
	UserName varchar(50) NOT NULL,
    PRIMARY KEY (UserName),
    FOREIGN KEY (UserName)
		REFERENCES `User` (UserName)
);

CREATE TABLE AdminUser (
	UserName varchar(50) NOT NULL,
    Position varchar(100) NULL,
    PRIMARY KEY (UserName),
    FOREIGN KEY (UserName)
		REFERENCES `User` (UserName)
);