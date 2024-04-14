// verify bgcolors as red, yellow or green for bids 
Update auction set canceled_time=now() where item_id=5;
commit;
Update auction set canceled_time=null where item_id=5;
commit;

Update auction set winner=null where item_id=5;
commit;

Update auction set winner='jai' where item_id=5;
commit;