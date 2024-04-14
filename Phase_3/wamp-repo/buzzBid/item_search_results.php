<?php
include ('lib/common.php');
include('calc_winners.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
calc_winner_results() ;
$fromCache = !empty($_SESSION['search_cache']);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $fromCache) {
    // Sanitize input data
    $keyword = !$fromCache ? mysqli_real_escape_string($db, $_POST['keyword']) : $_SESSION['keyword'];
    $_SESSION['keyword'] = $keyword;
    $category = !$fromCache ? mysqli_real_escape_string($db, $_POST['category']) : $_SESSION['category'];
    $_SESSION['category'] = $category;
    $minprice = !$fromCache ? mysqli_real_escape_string($db, $_POST['minprice']) : $_SESSION['minprice'];
    $_SESSION['minprice'] = $minprice;
    if ($minprice == '')
        $minprice = NULL;
    $maxprice = !$fromCache ? mysqli_real_escape_string($db, $_POST['maxprice']) : $_SESSION['maxprice'];
    $_SESSION['maxprice'] = $maxprice;
    if ($maxprice == '')
        $maxprice = NULL;
    $itemCondition = !$fromCache ? mysqli_real_escape_string($db, $_POST['itemCondition']) : $_SESSION['itemCondition'];
    $_SESSION['itemCondition'] = $itemCondition;

    $query = "WITH ItemFilter1(item_ID) AS
    ( SELECT item_ID FROM Item WHERE
    CASE WHEN '$keyword' <>''
    THEN
    description LIKE '%{$keyword}%' OR item_name LIKE '%{$keyword}%'
    ELSE 1=1
    END),
    ItemFilter2(item_ID) AS
    (
    SELECT item_ID FROM Item WHERE
    item_ID IN (SELECT item_ID FROM ItemFilter1) AND
    CASE WHEN '$category' <>''
    THEN
    category LIKE '%$category%'
    ELSE 1=1
    END
    ),
    ItemFilter3(item_ID) AS
    (WITH MaxBid(item_ID,max_bid) AS
    (SELECT item_ID, MAX(bid_amount) max_bid FROM ItemBid WHERE item_ID IN (SELECT
    item_ID FROM ItemFilter2) GROUP BY item_ID)
    SELECT i.item_ID FROM Item i
    INNER JOIN Auction a ON a.item_ID = i.item_ID
    LEFT JOIN MaxBid m ON i.item_ID=m.item_ID
    WHERE i.item_ID IN (SELECT item_ID FROM ItemFilter2) 
    AND
    CASE WHEN '$minprice' <> '' 
    THEN
    COALESCE(m.max_bid, a.min_sale_price) >= COALESCE(CAST('$minprice' as float),0.0) 
    ELSE 1=1
    END
    ),
    ItemFilter4(item_ID) AS
    (WITH MaxBid(item_ID,max_bid) AS
    (SELECT item_ID, MAX(bid_amount) max_bid FROM ItemBid
    WHERE item_ID IN (SELECT item_ID FROM ItemFilter3) GROUP BY item_ID)
    SELECT i.item_ID FROM Item i
    INNER JOIN Auction a ON a.item_ID = i.item_ID
    LEFT JOIN MaxBid m ON i.item_ID=m.item_ID
    WHERE i.item_ID IN (SELECT item_ID FROM ItemFilter3) AND
    CASE WHEN '$maxprice' <>''
    THEN
    a.min_sale_price <= CAST('$maxprice' as float) AND COALESCE(m.max_bid, a.min_sale_price) <= CAST('$maxprice' as float)
    ELSE 1=1 END),
    ItemFilter5(item_ID) AS
    (SELECT item_ID FROM Item WHERE
    item_ID IN (SELECT item_ID FROM ItemFilter4) AND
    CASE
    WHEN '$itemCondition'='New' THEN item_condition IN ('New') 
    WHEN '$itemCondition'='Very Good' THEN item_condition IN ('New','Very Good')
    WHEN '$itemCondition'='Good' THEN item_condition IN ('New','Very Good','Good')
    WHEN '$itemCondition'='Fair' THEN item_condition IN ('New','Very Good','Good','Fair')
    ELSE item_condition IN ('New','Very Good','Good','Fair','Poor')
    END
    ),ItemHighestBid AS
    (SELECT item_ID, max(bid_amount) max_bid FROM ItemBid WHERE
    item_ID IN (SELECT item_ID FROM ItemFilter5)
    GROUP BY item_ID ORDER BY item_ID),
    ItemFilter6(item_ID,current_bid,user_name) AS
    (SELECT ib.item_ID, ib.bid_amount current_bid, ib.bid_by user_name FROM ItemBid ib INNER JOIN
    ItemHighestBid ihb
    ON ib.item_ID=ihb.item_ID AND bid_amount=max_bid
    WHERE ib.item_ID IN (SELECT item_ID FROM ItemFilter5))
    SELECT i.item_ID, i.item_name, a.getit_now_price,a.scheduled_end_time
    auction_end_time,hb.current_bid,hb.user_name,a.actual_end_time
    FROM Item i
    INNER JOIN Auction a ON a.item_ID= i.item_ID
    INNER JOIN ItemFilter5 i5 ON
    i.item_ID = i5.item_ID
    LEFT JOIN ItemFilter6 hb ON i.item_ID = hb.item_ID
    ORDER BY auction_end_time";

    // echo $query;

    $result = mysqli_query($db, $query);

    // Check if query was successful
    if (!$result) {
        die("Error fetching data: " . mysqli_error($db));
    }
}
?>

<?php include ("lib/header.php"); ?>
<title>Search Results</title>
</head>

<body>
    <div class="category-report-box">
        <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">Search Results</div>

        <!-- Display table result -->
        <div>
            <?php if (mysqli_num_rows($result) == 0) {
                echo "No data found.";
            } else { ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Current Bid</th>
                        <th>High Bidder</th>
                        <th>Get it Now Price</th>
                        <th>Auction Ends</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td>
                                <?php echo $row['item_ID']; ?>
                            </td>
                            <td>
                                <?php
                                $actual_end_time = $row['actual_end_time'];
                                $nextPage = empty($actual_end_time) ? "view_item.php" : "item_auction_results.php";
                                $nextPage = $nextPage . "?itemID=" . $row['item_ID'];
                                ?>
                                <a href=<?php echo $nextPage; ?>><?php echo $row['item_name']; ?></a>
                            </td>
                            <td>
                                <?php $curBid = $row['current_bid'];
                                $convNum = number_format(floatval($curBid), 2); // 2 dp
                                echo empty($curBid) ? '-' : '$' . $convNum ?>
                            </td>
                            <td>
                                <?php $userName = $row['user_name'];
                                echo empty($userName) ? '-' : $userName; ?>
                            </td>
                            <td>
                                <?php $gPrice = $row['getit_now_price'];
                                $convNum = number_format(floatval($gPrice), 2); // 2 dp
                                echo empty($gPrice) ? '-' : '$' . $convNum ?>
                            </td>
                            <td>
                                <?php $date = $row['auction_end_time'];
                                $newDate = date("Y/m/d H:iA", strtotime($date));
                                echo $newDate ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <input type="button" value="Back to Search" onclick="window.location.href='item_search.php'" />
                        </td>
                    </tr>
                </table>
            <?php } ?>
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