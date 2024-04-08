<?php
    include('lib/common.php');
    // Temporarily set itemID session variable to 2
   // $_SESSION['itemID'] = 2;
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
        <?php
        // get all ratings for the item with same item_name as $itemName
        $query = "SELECT R.rated_by, R.rate_date_time, R.stars, R.rating_comment FROM Item AS I, ItemRating AS R WHERE I.item_name = '$itemName' AND I.item_ID = R.item_ID";
        $result = mysqli_query($db, $query);
        // loop through all ratings and display them
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="ratings-block">';
            echo '<div class="rater-date">';
            echo '<div>';
            echo '<div>Rated by: </div>';
            echo '<div><b>' . $row['rated_by'] . '</b></div>';
            echo '</div><div>';
            echo '<div>Date: </div>';
            echo '<div><b>' . $row['rate_date_time'] . '</b></div>';
            echo '</div></div>';
            echo '<div class="stars">';
            // display empty and filled stars based on rating
            for ($i = 0; $i < $row['stars']; $i++) {
                echo '<span class="filled-star">&#9733;</span>'; // filled star
            }
            for ($i = $row['stars']; $i < 5; $i++) {
                echo '&#9734;'; // empty star
            }
            echo '</div>';
            echo '<div class="comment">' . $row['rating_comment'] . '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
