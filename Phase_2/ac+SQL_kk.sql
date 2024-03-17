-- Register
INSERT INTO `User` (UserName, Password, FirstName, LastName)
VALUES ($UserName, $Password, $FirstName, $LastName);

-- List item to sell
-- Display Category field dropdown values
SELECT * FROM Category;

-- Create new records for Item and Auction entity
-- application gets UserName of current user as $UserName
INSERT INTO Item (ListedBy, ItemName, Description, Returnable, Category, Condition)
VALUES ($UserName, $ItemName, $Description, $Returnable, $Category, $Condition);

-- application calculates $ScheduledEndTime from $AuctionLength and current date time
INSERT INTO Auction (StartingBid, MinSalePrice, GetItNowPrice, AuctionLength, ScheduledEndTime)
VALUES ($StartingBid, $MinSalePrice, $GetItNowPrice, $AuctionLength, $ScheduledEndTime);