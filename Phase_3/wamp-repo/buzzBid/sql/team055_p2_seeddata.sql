-- INSERT INTO `User` (user_name, password, first_name, last_name) VALUES ('jai', 'jai', 'Jainandhini', 'Srinivasan');
-- INSERT INTO `Category`(`category_name`) VALUES ('electronics');

-- INSERT INTO `item`(`listed_by`, `item_name`, `description`, `returnable`, `item_condition`, `category`) VALUES ('jai','item1','watch',0,'good','electronics');
-- INSERT INTO `item`(`listed_by`, `item_name`, `description`, `returnable`, `item_condition`, `category`) VALUES ('jai','item2','watch',0,'good','electronics');

INSERT INTO `auction`(`item_ID`, `starting_bid`, `min_sale_price`, `getit_now_price`, `auction_length`, `scheduled_end_time`, `actual_end_time`, `sale_price`, `winner`, `canceled_time`, `canceled_by`, `cancelation_reason`) VALUES ('1','10','9','15',3,DATE_ADD(CURRENT_TIMESTAMP , INTERVAL 3 DAY),NULL,'11','jai',NULL,'','');
INSERT INTO `auction`(`item_ID`, `starting_bid`, `min_sale_price`, `getit_now_price`, `auction_length`, `scheduled_end_time`, `actual_end_time`, `sale_price`, `winner`, `canceled_time`, `canceled_by`, `cancelation_reason`) VALUES ('2','10','9','15',3,DATE_ADD(CURRENT_TIMESTAMP , INTERVAL 3 DAY),NULL,NULL,'',CURRENT_TIMESTAMP,'salini','wrong entry');


INSERT INTO `itemrating`(`rated_by`, `item_ID`, `stars`, `rating_comment`, `rate_date_time`) VALUES ('jai','2','4','good',CURRENT_TIMESTAMP);
