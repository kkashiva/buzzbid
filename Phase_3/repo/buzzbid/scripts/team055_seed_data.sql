USE cs6400_sp24_team055;
INSERT INTO `User` (user_name, password, first_name, last_name)
VALUES ('jai', 'jai', 'first', 'last');
Insert into Category values ('Art');
INSERT INTO Item (listed_by, item_name, description, returnable, category, item_condition)
VALUES ('jai', 'painting', null, true, 'Art', 'Poor');
INSERT INTO Auction (item_ID, starting_bid, min_sale_price, getit_now_price, auction_length, scheduled_end_time)
VALUES (1, 1, 0, 5, 3, now());
-- Insert into ItemBid values('jai',1,2.0,now());
INSERT INTO ItemBid (bid_by, item_id, bid_amount, time_of_bid)
VALUES ('jai',1,0, CURRENT_TIMESTAMP);
INSERT INTO ItemRating (rated_by, item_ID, stars, rating_comment, rate_date_time)
VALUES ('jai',1,5,'great',now());
UPDATE Item SET description='about item' WHERE item_id=1;
-- UPDATE Auction
-- SET sale_price=2.5, winner = 'jai', actual_end_time = CURRENT_TIMESTAMP
-- WHERE item_id=3;
-- UPDATE Auction
-- SET cancelation_reason = 'not nice', actual_end_time = CURRENT_TIMESTAMP, canceled_time = CURRENT_TIMESTAMP
-- WHERE item_ID=5;
-- UPDATE Auction
-- SET sale_price=10, winner = 'jai', actual_end_time = now()
-- WHERE item_id=3;
-- UPDATE Auction
-- SET actual_end_time = now()
-- WHERE item_id=3;
