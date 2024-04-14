<?php
include ('lib/common.php');
include ("lib/header.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $itemID = mysqli_real_escape_string($db, $_GET['itemID']);
    $price = mysqli_real_escape_string($db, $_GET['price']);
    $isGetItNow = mysqli_real_escape_string($db, $_GET['is_getit_now']);

    $user = $_SESSION['username'];

    if (empty($itemID)) {
        echo "No data found.";
    }

    //TODO: disable/handle user bidding second itme on same item
    $existingBidsByUser = "SELECT bid_amount FROM ItemBid WHERE item_id=$itemID AND bid_by='$user'";
    $existingBidResult = mysqli_query($db, $existingBidsByUser);

    // Check if query was successful
    if (!$existingBidResult) {
        die("Error getting existing bid by user: " . mysqli_error($db));
    }

    if (mysqli_num_rows($existingBidResult) > 0) {
        $bidRow = mysqli_fetch_assoc($existingBidResult);
        $currentBid = $bidRow["bid_amount"];
        if ($price <= $currentBid) {
            header('Location: ' . 'view_item.php?itemID=' . $itemID);
            exit();
        }
        $query = "UPDATE ItemBid SET bid_amount = $price, time_of_bid = now() 
        WHERE item_id=$itemID ";
        //echo $query;
        $result = mysqli_query($db, $query);

        // Check if query was successful
        if (!$result) {
            die("Error updating item bid for get it now: " . mysqli_error($db));
        }

    } else {
        $query = "INSERT INTO ItemBid (bid_by, item_id, bid_amount, time_of_bid)
    VALUES ('$user', $itemID, $price, now())";

        $result = mysqli_query($db, $query);

        // Check if query was successful
        if (!$result) {
            die("Error adding item bid for get it now: " . mysqli_error($db));
        }

    }
    if (!empty($isGetItNow)) {
        $query =
            "UPDATE Auction
        SET sale_price=$price, winner = '$user', actual_end_time = now()
        WHERE item_id=$itemID";


        //echo 'q: ' . $query;

        $result = mysqli_query($db, $query);

        // Check if query was successful
        if (!$result) {
            die("Error updating item winner for get it now: " . mysqli_error($db));
        } 
        header('Location: ' . 'item_auction_results.php?itemID=' . $itemID);
        exit();
    }
    else {
        header('Location: ' . 'view_item.php?itemID=' . $itemID);
        exit();
    }
}