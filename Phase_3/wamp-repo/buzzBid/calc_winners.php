<?php
include ('lib/common.php');
include ("lib/header.php");

function calc_winner_results()
{

    include ('lib/common.php');
    include ("lib/header.php");

    if (isset($_SESSION['lastResultTime']) && !empty($_SESSION['lastResultTime'])) {
        $lastResultTime = $_SESSION['lastResultTime'];
        $currentTime = new DateTime();
        $minutes = abs($currentTime->getTimestamp() - $lastResultTime->getTimestamp()) / 60;
        if ($minutes < 1) {
            return;
        }
    }

    $query =
        "SELECT i.item_ID FROM item i
        INNER JOIN auction a 
        ON i.item_ID = a.item_ID
        WHERE a.scheduled_end_time <= now() AND a.actual_end_time IS NULL";

    //    echo $query;

    $result = mysqli_query($db, $query);

    // Check if query was successful
    if (!$result) {
        die("Error getting items awaiting result calculation: " . mysqli_error($db));
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $itemId = $row["item_ID"];
            $maxBidQuery = "WITH MAX_BID(item_ID, max_bid) AS
            (SELECT item_ID, max(bid_amount)
            FROM ItemBid
            WHERE item_ID=$itemId
            GROUP BY item_ID)
            SELECT bid_by, i.item_ID, max_bid
            FROM ItemBid i
            INNER JOIN MAX_BID m
            ON i.item_ID = m.item_ID;";

            $maxBidResult = mysqli_query($db, $maxBidQuery);

            if (!$maxBidResult) {
                die("Error retrieving item bids: " . mysqli_error($db));
            }
            if (mysqli_num_rows($maxBidResult) == 0) {
                //no bids, complete auction with no winners
                $endAuctionQuery = "UPDATE Auction
                SET actual_end_time = now()
                WHERE item_id=$itemId;";

                // echo $endAuctionQuery;

                $endAuctionResult = mysqli_query($db, $endAuctionQuery);
                if (!$result) {
                    die("Error updating auction results with no bids: " . mysqli_error($db));
                }
            } else {
                $maxBidRow = mysqli_fetch_assoc($maxBidResult);
                $maxBid = $maxBidRow["max_bid"];
                $bidBy = $maxBidRow["bid_by"];

                $auctionWinnerQuery = "UPDATE Auction
                SET sale_price=$maxBid, winner = '$bidBy', actual_end_time = now()
                WHERE item_id=$itemId;";

                //echo $auctionWinnerQuery;
                $auctionWinnerResult = mysqli_query($db, $auctionWinnerQuery);

                if (!$auctionWinnerResult) {
                    die("Error updating auction winner" . mysqli_error($db));
                }
            }
        }

        $curTime = new DateTime();
        $_SESSION['lastResultTime'] = $curTime;

    }
}