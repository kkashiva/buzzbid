<?php
    include('lib/common.php');
    // Temporarily set itemID session variable to 2
    $_SESSION['itemID'] = 2;
    // get itemID from 'view item' page TBD with Jai
    $itemID = $_SESSION['itemID'];
    // get item name from database
    $query = "SELECT item_name FROM Item WHERE item_ID = '$itemID'";
    $result = mysqli_query($db, $query);
    $itemName = mysqli_fetch_assoc($result)['item_name'];
    // get average rating from database for all items with the same item_name as $itemName
    $query = "SELECT AVG(stars) FROM Item AS I, ItemRating AS R WHERE I.item_name = '$itemName' AND I.item_ID = R.item_ID";
    $result = mysqli_query($db, $query);
    $averageRating = mysqli_fetch_assoc($result)['AVG(stars)'];
?>

<?php include("lib/header.php"); ?>
<title>View Ratings</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
    <div class="ratings-box">
        <span class="close">&#10006;</span> <!-- Close symbol -->
        <div class="ratings-text">Item Ratings</div>
        <div class="ratings-row">
            <div>Item ID</div>
            <div><b><?php echo $itemID; ?></b></div>
        </div>
        <div class="ratings-row">
            <div>Item Name</div>
            <div><b><?php echo $itemName; ?></b></div>
        </div>
        <div class="ratings-row">
            <div>Average Rating</div>
            <div><b><?php echo number_format($averageRating, 1); echo ' stars'?></b></div>
        </div>
        <div class="ratings-block">
            <?php
            // get all ratings and comments for the same item_name as $itemName
            $query = "SELECT R.rated_by, R.rate_date_time, R.stars, R.rating_comment FROM Item AS I, ItemRating AS R WHERE I.item_name = '$itemName' AND I.item_ID = R.item_ID";
            $result = mysqli_query($db, $query);
            // loop through the result set and display each rating and comment
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="ratings-row">';
                echo '<div>' . $row['stars'] . ' stars</div>';
                echo '<div>' . $row['rating_comment'] . '</div>';
                echo '<div>' . $row['rated_by'] . '</div>';
                echo '<div>' . $row['rate_date_time'] . '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
