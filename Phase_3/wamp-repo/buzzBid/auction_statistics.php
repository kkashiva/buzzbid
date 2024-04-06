<?php
include('lib/common.php');

// SQL query to fetch data from the table
$query = "SELECT total_active_auctions,total_finished_auctions,winning_auctions,total_canceled_auctions,items_rated,items_not_rated FROM 
(SELECT COUNT(*) total_active_auctions from Auction WHERE NOW() < scheduled_end_time 
AND canceled_time IS NULL) AS total_active_auctions ,
(SELECT COUNT(*) total_finished_auctions FROM Auction WHERE NOW() > scheduled_end_time 
AND canceled_time IS NULL) AS total_finished_auctions,
(SELECT COUNT(*) winning_auctions FROM Auction WHERE canceled_time IS NULL AND winner 
IS NOT NULL) AS winning_auctions,
(SELECT COUNT(*) total_canceled_auctions FROM Auction WHERE canceled_time IS NOT NULL) 
AS total_canceled_auctions, 
(SELECT COUNT(*) items_rated FROM Item i INNER JOIN ItemRating ir ON i.item_id=ir.item_id) 
AS items_rated,
(SELECT COUNT(*) items_not_rated FROM Item WHERE item_id NOT IN ( SELECT i.item_id 
FROM Item i INNER JOIN ItemRating ir ON i.item_id=ir.item_id)) AS items_not_rated;"; 

// Perform the query
$result = mysqli_query($db, $query);

// Check if query was successful
if (!$result) {
    die("Error fetching data: " . mysqli_error($db));
}

// Fetching the single row of result
$row = mysqli_fetch_assoc($result);
?>

<?php include("lib/header.php"); ?>
<title>Auction Statistics</title>
</head>
<body>
    <div class="auction-statistics-box">
        <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">Auction Statistics</div>
        
        <!-- Display table result -->
        <div>
            <?php if (!$row) {
                echo "No data found.";
            } else { ?>
                <p class="statistics-label">Total Active Auctions</p><span><?php echo $row['total_active_auctions']; ?></span>

                <p class="statistics-label">Total Finished Auctions</p><span><?php echo $row['total_finished_auctions']; ?></span>

                <p class="statistics-label">Winning Auctions</p><span><?php echo $row['winning_auctions']; ?></span>

                <p class="statistics-label">Total Canceled Auctions</p><span><?php echo $row['total_canceled_auctions']; ?></span>

                <p class="statistics-label">Items Rated</p><span><?php echo $row['items_rated']; ?></span>

                <p class="statistics-label">Items Not Rated</p><span><?php echo $row['items_not_rated']; ?></span>
            <?php } ?>
        </div>
    </div>
	
	<script>
        // JavaScript to handle the close button click
        document.getElementById("closeButton").addEventListener("click", function() {
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
