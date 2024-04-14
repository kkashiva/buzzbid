<?php
    include('lib/common.php');

    // Query the database for auctions that have ended
    $query = "SELECT a.item_ID, i.item_name, a.sale_price, a.winner, a.actual_end_time FROM Auction as a, Item as i WHERE a.item_ID = i.item_ID AND a.actual_end_time IS NOT NULL AND a.winner IS NOT NULL ORDER BY actual_end_time DESC;";
    $result = mysqli_query($db, $query);
    //log the results of the query to debug
    // while ($row = mysqli_fetch_assoc($result)) {
    //     echo "item_ID: " . $row['item_ID'] . " item_name: " . $row['item_name'] . " sale_price: " . $row['sale_price'] . " winner: " . $row['winner'] . " auction_end_time: " . $row['auction_end_time'] . "<br>";
    // }
    
?>

<?php include("lib/header.php");?>
<title>Auction Results</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
    <div class="auction-results-box">
        <span class="close" onclick="window.location.href='main_menu.php'">&#10006;</span> <!-- Close symbol -->
        <div class="auction-results-text">Auction Results</div>
        <table class="ratings-table">
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Sale Price</th>
                <th>Winner</th>
                <th>Auction End Time</th>
            </tr>
            <?php
                // loop through all auctions and display them
                $result = mysqli_query($db, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['item_ID'] . "</td>";
                    echo "<td>" . $row['item_name'] . "</td>";
                    echo "<td>" . $row['sale_price'] . "</td>";
                    echo "<td>" . $row['winner'] . "</td>";
                    echo "<td>" . $row['actual_end_time'] . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</body>

