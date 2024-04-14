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
    $user = $_SESSION['username'];

    if (empty($itemID)) {
        echo "No data found.";
    }

//TODO: disable/handle user bidding second itme on same item
    $query = "INSERT INTO ItemBid (bid_by, item_id, bid_amount, time_of_bid)
    VALUES ('$user', $itemID, $price, now())";

    $result = mysqli_query($db, $query);

    // Check if query was successful
    if (!$result) {
        die("Error updating item bid for get it now: " . mysqli_error($db));
    }

    $query =
        "UPDATE Auction
        SET sale_price=$price, winner = '$user', actual_end_time = now()
        WHERE item_id=$itemID";

    
    echo 'q: '.$query;

    $result = mysqli_query($db, $query);

    // Check if query was successful
    if (!$result) {
        die("Error updating item winner for get it now: " . mysqli_error($db));
    } else {
        header('Location: ' . 'view_item.php?itemID=' . $itemID);
        exit();
    }

}