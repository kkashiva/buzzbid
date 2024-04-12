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
    $description = mysqli_real_escape_string($db, $_GET['itemDesc']);

    if (empty($itemID)) {
        echo "No data found.";
    }

    $query =
        "UPDATE Item SET description='$description'
        WHERE item_ID = $itemID";

    echo $query;

    $result = mysqli_query($db, $query);

    // Check if query was successful
    if (!$result) {
        die("Error updating item description: " . mysqli_error($db));
    }
    else{
        header('Location: '.'view_item.php?itemID='.$itemID);
        exit();
    }

}