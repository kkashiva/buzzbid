<?php
    include("lib/common.php");
    // get itemID from URL parameter, this needs to be appended to the URL when the user clicks on Cancel Item button in view_item.php
    $itemID = mysqli_real_escape_string($db, $_GET['itemID']);
    // log itemID to debug
    // echo "itemID: " . $itemID;

    // get username from session
    $username = $_SESSION['username'];
    // log username to debug
    // echo 'User: '. $username;
    
    // check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // retrieve form input
        $itemID = mysqli_real_escape_string($db, $_POST['itemID']);
        $cancelReason = mysqli_real_escape_string($db, $_POST['cancel_reason']);
        // log cancel reason to debug
        // echo "Cancel Reason: " . $cancelReason;
        // record current datetime as the cancellation time
        $canceledTime = date('Y-m-d H:i:s', time() -0*24*60*60);
        // log canceled time to debug
        // echo "Canceled Time: " . $canceledTime;
        // query to cancel the auction
        $query = "UPDATE Auction SET `cancelation_reason` = '$cancelReason', `actual_end_time` = '$canceledTime', `canceled_time` = '$canceledTime', `canceled_by` = '$username' WHERE `item_ID` = $itemID;";
        // if query successful redirect to main menu
        if (mysqli_query($db, $query)) {
            // Print message to user "Item cancelled from auction." before redirecting
            echo '<p>Item auction has been cancelled.</p>';
            // Redirect to main menu after 2 seconds
            echo '<script>setTimeout(function(){ window.location.href = "main_menu.php"; }, 2000);</script>';
            exit();
        } else {
            // log error to debug
            echo mysqli_error($db);
            array_push($error_msg, "Cancelation failed.");
        }
    }
?>

<?php include("lib/header.php"); ?>
<title>Cancel Auction</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
    <div class="cancel-box">
        <span class="close" onclick="window.location.href='main_menu.php'">&#10006;</span> <!-- Close symbol -->
        <div class="cancel-text">Cancel Auction</div>
        <!-- text area field for cancellation reason -->
        <form action="cancel_item.php" method="post">
            <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
            <div class="form-row" id="cancel_reason">
            <textarea name="cancel_reason" placeholder="Enter reason for cancellation"></textarea>
            </div>
            <button type="submit" class="cancel-button">Cancel Auction</button>
        </form>
    </div>
</body>