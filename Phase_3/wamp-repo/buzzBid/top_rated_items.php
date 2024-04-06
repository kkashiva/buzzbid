<?php
include('lib/common.php');

// SQL query to fetch data from the table
$query = "SELECT
I.item_name AS ItemName,
ROUND(AVG(ir.stars),1) AS AverageRating,
COUNT(ir.item_id) AS RatingCount
FROM Item i
INNER JOIN ItemRating ir
ON i.item_id = ir.item_id
GROUP BY
I.item_name
ORDER BY
AverageRating DESC,
I.item_name ASC
LIMIT 10;"; 

// Perform the query
$result = mysqli_query($db, $query);

// Check if query was successful
if (!$result) {
    die("Error fetching data: " . mysqli_error($db));
}
?>

<?php include("lib/header.php"); ?>
<title>Top Rated Items</title>
</head>
<body>
    <div class="category-report-box">
        <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">Top Rated Items</div>
        
        <!-- Display table result -->
        <div>
            <?php if (mysqli_num_rows($result) == 0) {
                echo "No data found.";
            } else { ?>
                <table>
                    <tr>
                        <th>Item Name</th>
                        <th>Average Rating</th>
                        <th>Rating Count</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['ItemName']; ?></td>
                            <td><?php echo $row['AverageRating']; ?></td>
                            <td><?php echo $row['RatingCount']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
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
