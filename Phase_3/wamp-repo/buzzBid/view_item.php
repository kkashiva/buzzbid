<?php
include ('lib/common.php');
include ("lib/header.php");

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // echo "here : ".$_GET['itemID'];
    // Sanitize input data
    $itemID = mysqli_real_escape_string($db, $_GET['itemID']);

    if (empty($itemID)) {
        echo "No data found.";
    }

    $query =
        "WITH ItemHighestBid AS
        (SELECT item_ID, max(bid_amount) max_bid FROM ItemBid WHERE
        item_ID = $itemID
        GROUP BY item_ID)
        SELECT i.item_ID, item_name, description, category, item_condition, returnable, getit_now_price, b.max_bid,i.listed_by,
        a.scheduled_end_time auction_end_time,a.actual_end_time, a.min_sale_price
        FROM Item i
        INNER JOIN Auction a ON i.item_ID = a.item_ID
        LEFT JOIN ItemHighestBid b ON i.item_ID = b.item_ID
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
                        <?php if (mysqli_num_rows($result) == 0) {
                        } else {
                            $row = mysqli_fetch_assoc($result);

                            $actual_end_time = $row["actual_end_time"];

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
                                        </label>
                                    <input type="hidden" id="item_ID" value=<?php echo $row['item_ID']; ?>></input>
                                    </td>
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
                                    <td><input type ="text" id="item_desc" name="description" value="<?php echo $row['description']; ?>"
                                            style="resize: none;text-align:left; overflow:auto; border:0px outset #000000;" readonly>
                        </input></td>
                                    <td>
                                        <?php
                                        $listedBy = $row['listed_by'];
                                        if ($_SESSION['username'] == $listedBy) {
                                            ?>
                                            <div id="saveDescDiv">
                                            <a id="save_item_desc" href="#">Save Description</a>
                                            </div>
                                            <div id="editDescDiv">                                            
                                            <a id="edit_item_desc" href="#" onclick="editDesc()" >Edit Description</a>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </td>
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
                                            echo empty($getit_now_price) ? '-' : '$' . $convNum;
                                            //for future use
                                            $maxBid = $row['max_bid'];
                                            $minSalePrice = $row['min_sale_price'];
                                            ?>
                                        </label></td>
                                    <td>
                                        <?php if (!empty($getit_now_price) && $_SESSION['username'] != $listedBy && empty($actual_end_time)) { ?>
                                            <input type="button" id="get_it_now_btn" onclick="getitnow()" value="Get it Now!"></input>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Auction Ends:</label></td>
                                    <td><label>
                                            <?php $date = $row['auction_end_time'];
                                            $newDate = date("Y/m/d H:iA", strtotime($date));
                                            echo $newDate ?>
                                        </label></td>
                                    <td></td>
                                </tr>
                                <tr></tr>
                            </table>

                            <table id="bids_table" style="border=1px;">
                                <caption><b><u>Latest Bids</u><b></caption>
                                <td>
                                    <tr>
                                        <th>Bid Amount</th>
                                        <th>Time of Bid</th>
                                        <th>Username</th>
                                    </tr>
                                    <?php while ($row = mysqli_fetch_assoc($bidResult)) { ?>
                                        <tr>
                                            <td>
                                                <?php $bid_amount = $row['bid_amount'];
                                                $convNum = number_format(floatval($bid_amount), 2); // 2 dp
                                                echo empty($bid_amount) ? '-' : '$' . $convNum ?>
                                            </td>
                                            <td>
                                                <?php $date = $row['time_of_bid'];
                                                $newDate = date("Y/m/d H:iA", strtotime($date));
                                                echo $newDate ?>
                                            </td>
                                            <td>
                                                <?php echo $row['bid_by']; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </td>
                            </table>


                            <table id="desc_table">
                                <tr>
                                    <td> <label for="new_bid">Your bid</label></td>
                                    <td>$<input type="text" id="new_bid" onblur="validateBid(<?php echo $getit_now_price ?>,
                                <?php echo $minSalePrice ?>,<?php echo $maxBid ?>)"></input></td>
                                    <td>(minimum bid
                                        <?php
                                        $new_bid_inc = empty($maxBid) ? $minSalePrice : $maxBid + 1.00;
                                        $convNum = number_format(floatval($new_bid_inc), 2);
                                        echo '$' . $convNum ?>)
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><label id="bid_err_msg"></label></td>
                                </tr>
                            </table>
                        <?php } ?>
                        <div class="form_group">
                            <?php $_SESSION['search_cache'] = true; ?>
                            <input type="button" value="Close"
                                onclick="window.location.href='item_search_results.php'" />
                            <?php if ($isAdminUser) {
                                ?>
                                <input type="button" value="Cancel This Item"
                                    onclick="window.location.href='cancel_item.php?itemID=<?php echo $row['item_ID']; ?>'" />
                            <? } ?>
                            <?php
                            if ($_SESSION['username'] != $listedBy && empty($actual_end_time)) {
                                ?>
                                <input id="bid_btn" type="button" value="Bid On This Item" onclick="bidnow()" disabled=true />
                            <?php } ?>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            var saveDiv=document.getElementById("saveDescDiv");
            saveDiv.style.display = 'none';
        };
        // JavaScript to handle the close button click
        document.getElementById("closeButton").addEventListener("click", function () {
            window.location.href = "main_menu.php"; // Redirect to main_menu page
        });
    
        document.getElementById("edit_item_desc").addEventListener("click", function () {
            //alert('can edit');
            document.getElementById('item_desc').removeAttribute('readonly');
        });

        document.getElementById("save_item_desc").addEventListener("click", function () {
            document.getElementById('item_desc').setAttribute('readonly', 'readonly');
            var id=document.getElementById("item_ID").value;
            var desc=document.getElementById("item_desc").value;
            var url = 'update_description.php?itemID='+id+'&itemDesc='+desc;
            document.location.href = url;   
        });

        function getitnow() {
            var id=document.getElementById("item_ID").value;
            var price = <?php echo $getit_now_price ?>;
            var url = 'item_bid.php?itemID='+id+"&price="+price+"&is_getit_now=true";
            document.location.href = url;   
        }
        function bidnow() {
            var id=document.getElementById("item_ID").value;
            var price = document.getElementById("new_bid").value;
            var url = 'item_bid.php?itemID='+id+"&price="+price;
            document.location.href = url;   
        }
        function editDesc(){
            //alert("edit desc");
            var editDiv =document.getElementById("editDescDiv");
            var saveDiv=document.getElementById("saveDescDiv");

            saveDiv.style.display = "block";
            editDiv.style.display = "none";            
        }

        function validateBid(getit_now_price, minSalePrice, maxBid) {
            curBid = parseFloat(document.getElementById("new_bid").value);
            if (isNaN(parseFloat(curBid))) {
                document.getElementById("bid_err_msg").innerHTML = "Invalid Bid amount";
                document.getElementById("bid_btn").disabled = true;
            }
            else{
                min_new_bid = (isNaN(maxBid) ? minSalePrice : maxBid) + 1.00;
                if (curBid >= getit_now_price) {
                    document.getElementById("bid_err_msg").innerHTML = "Use Get it Now button for purchase ";
                    document.getElementById("bid_btn").disabled = true;
                }
                else if (curBid < min_new_bid) {
                    document.getElementById("bid_err_msg").innerHTML = "Your bid is less than minimum bid value. ";
                    document.getElementById("bid_btn").disabled = true;
                }
                else {
                    document.getElementById("bid_err_msg").innerHTML = "";
                    document.getElementById("bid_btn").disabled = false;
                }
            }
        }
    </script>

</body>

</html>

<?php
// Free result set
mysqli_free_result($result);

// Close database connection
mysqli_close($db);
?>