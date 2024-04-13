<?php
include ('lib/common.php');
include ("lib/header.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

//TODO: calculate item results
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Sanitize input data
    $itemID = mysqli_real_escape_string($db, $_GET['itemID']);

    if (empty($itemID)) {
        echo "No data found.";
    }
    // $_SESSION['caller'] = 'item_auction_results.php'. "?itemID=" . $itemID;
    // header("Location: calc_winners.php");

    $query =
        "SELECT i.item_ID, item_name, description, category, item_condition, returnable, getit_now_price,i.listed_by,
        a.actual_end_time auction_end_time, a.sale_price, a.winner,a.canceled_time, 'ADMINISTRATOR' as canceled_by, cancelation_reason
        FROM Item i
        INNER JOIN Auction a ON i.item_ID = a.item_ID
        WHERE i.item_ID = $itemID";

    // echo $query;

    $result = mysqli_query($db, $query);

    // Check if query was successful
    if (!$result) {
        die("Error fetching item description: " . mysqli_error($db));
    }

    $bidsQuery = "SELECT bid_by,bid_amount,time_of_bid FROM ItemBid WHERE item_ID = $itemID
     ORDER BY time_of_bid DESC
    LIMIT 4 ";

    $bidResult = mysqli_query($db, $bidsQuery);

    if (!$bidResult) {
        die("Error fetching item bids: " . mysqli_error($db));
    }
}
?>
<title>Item for Sale</title>
<style>
    #new_bid {
        width: 50px;
    }

    #desc_table tr td:first-child {
        width: 200px;
        text-align: left;
    }

    #desc_table tr td:nth-child(2) {
        width: 400px;
        text-align: left;
        font-style: bold;
    }

    #desc_table tr td:nth-child(3) {
        width: 200px;
        text-align: left;
    }

    #bids_table tr td {
        width: 800px;
        text-align: left;
        left: 200px;
    }
    #winner_bid tr td:nth-child(1) {
        background-color: green;
    }
    #no_winner tr td:nth-child(1) {
        background-color: yellow;
    }
    #cancel_bid tr td:nth-child(1) {
        background-color: red;
    }
</style>
</head>

<body>
    <div class="category-report-box">
        <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">Item for Sale</div>

        <!-- Display table result -->
        <div id="main_container">
            <div class="center_content">
                <div class="text_box">
                    <form action="item_bid.php" method="post">
                        <?php if (mysqli_num_rows($result) == 0) {
                        } else {
                            $row = mysqli_fetch_assoc($result);

                            $userQuery = "SELECT u.user_name FROM User u 
                            INNER JOIN adminuser a ON u.user_name= a.user_name
                            WHERE u.user_name=";

                            $userResult = mysqli_query($db, $bidsQuery);

                            if (!$userResult) {
                                die("Error fetching user details: " . mysqli_error($db));
                            }
                            $userRow = mysqli_fetch_assoc($userResult);
                            $isAdminUser = mysqli_num_rows($result) != 0;
                            ?>
                            <table id="desc_table">
                                <tr>
                                    <td><label>Item ID</label></td>
                                    <td><label>
                                            <?php echo $row['item_ID']; ?>
                                        </label></td>
                                    <td><a href="ratings_view.php">View Ratings</a></td>
                                </tr>
                                <tr>
                                    <td> <label>Item Name</label></td>
                                    <td> <label>
                                            <?php echo $row['item_name']; ?>
                                        </label></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><label>Description</label></td>
                                    <td><textarea id="description" name="description" rows="4" cols="30" readonly
                                            style="resize: none;text-align:left; overflow:auto; border:0px outset #000000;">
                                                <?php echo $row['description']; ?></textarea></td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td><label>Category</label>
                                    </td>
                                    <td><label>
                                            <?php echo $row['category']; ?>
                                        </label></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><label>Condition</label>
                                    </td>
                                    <td><label>
                                            <?php echo $row['item_condition']; ?>
                                        </label></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><label>Returns Accepted?</label>
                                    </td>
                                    <td><input type="checkbox" name="returable" <?php echo ($row['returnable'] == 1 ? 'checked' : ''); ?> onclick="return false;">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><label>Get it Now Price</label></td>
                                    <td><label>
                                            <?php $getit_now_price = $row['getit_now_price'];
                                            $convNum = number_format(floatval($getit_now_price), 2); // 2 dp
                                            echo empty($getit_now_price) ? '-' : '$' . $convNum ;
                                            //for future use
                                            $maxBid = $row['max_bid'];
                                            $minSalePrice = $row['min_sale_price'];
                                            ?>
                                        </label></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><label>Auction Ended:</label></td>
                                    <td><label>
                                            <?php $date = $row['auction_end_time'];
                                            $newDate = date("m/d/Y H:iA", strtotime($date));
                                            echo $newDate ?>
                                        </label></td>
                                    <td></td>
                                </tr>
                                <tr></tr>
                            </table>

                            <table id="bids_table" style="border=1px;">
                                <caption><b><u>Bid History</u><b></caption>
                                <td>
                                    <tr>
                                        <th>Bid Amount</th>
                                        <th>Time of Bid</th>
                                        <th>Username</th>
                                    </tr>
                                    
                                    <?php 
                                    $canceled_time = $row['canceled_time'];
                                    if(!empty($canceled_time)){
                                        ?>
                                        <tr>
                                            <td>Cancelled</td>
                                            <td>
                                            <?php 
                                            $newDate = date("Y/m/d H:iA", strtotime($canceled_time));
                                            echo $newDate ?>    </td>
                                            <td>Administrator</td>
                                        </tr>
                                        
                                        <?php
                                        
                                    }
                                    
                                    
                                    while ($bidRow = mysqli_fetch_assoc($bidResult)) { ?>
                                        <tr>
                                            <td>
                                                <?php $bid_amount = $bidRow['bid_amount'];
                                                $convNum = number_format(floatval($bid_amount), 2); // 2 dp
                                                echo empty($bid_amount) ? '-' : '$' . $convNum ?>
                                            </td>
                                            <td>
                                                <?php $date = $bidRow['time_of_bid'];
                                                $newDate = date("Y/m/d H:iA", strtotime($date));
                                                echo $newDate ?>
                                            </td>
                                            <td>
                                                <?php echo $bidRow['bid_by']; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </td>
                            </table>

                        <?php } ?>
                        <div class="form_group">
                            <?php $_SESSION['search_cache'] = true; ?>
                            <input type="button" value="Close"
                                onclick="window.location.href='item_search_results.php'" />
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // JavaScript to handle the close button click
        document.getElementById("closeButton").addEventListener("click", function () {
            window.location.href = "main_menu.php"; // Redirect to main_menu page
        });
    </script>

</body>

</html>

<?php
// Free result set
mysqli_free_result($result);

// Close database connection
mysqli_close($db);
?>