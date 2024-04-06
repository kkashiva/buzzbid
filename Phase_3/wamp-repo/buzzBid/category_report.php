<?php
include('lib/common.php');

// SQL query to fetch data from the table
$query = "SELECT
    c.category_name,
    COUNT(a.item_id) AS TotalItems,
    MIN(CASE WHEN a.getit_now_price IS NOT NULL THEN a.getit_now_price END) AS MinPrice,
    MAX(CASE WHEN a.getit_now_price IS NOT NULL THEN a.getit_now_price END) AS MaxPrice,
    AVG(CASE WHEN a.getit_now_price IS NOT NULL THEN a.getit_now_price END) AS AveragePrice
FROM Category c
LEFT JOIN Item i ON c.category_name = i.category
LEFT JOIN Auction a ON i.item_id = a.item_id
WHERE a.canceled_time IS NULL
GROUP BY c.category_name"; 

// Perform the query
$result = mysqli_query($db, $query);

// Check if query was successful
if (!$result) {
    die("Error fetching data: " . mysqli_error($db));
}
?>

<?php include("lib/header.php"); ?>
<title>Category Report</title>
</head>
<body>
    <div class="category-report-box">
        <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">Category Report</div>
        
        <!-- Display table result -->
        <div>
            <?php if (mysqli_num_rows($result) == 0) {
                echo "No data found.";
            } else { ?>
                <table>
                    <tr>
                        <th>Category</th>
                        <th>Total Items</th>
                        <th>Min Price</th>
                        <th>Max Price</th>
                        <th>Average Price</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><?php echo $row['TotalItems']; ?></td>
                            <td><?php echo $row['MinPrice']; ?></td>
                            <td><?php echo $row['MaxPrice']; ?></td>
                            <td><?php echo $row['AveragePrice']; ?></td>
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
