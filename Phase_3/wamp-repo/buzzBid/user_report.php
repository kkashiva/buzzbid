<?php
include('lib/common.php');

// SQL query to fetch data from the table
$query = "SELECT
u.User_name AS UserName,
COUNT(DISTINCT i.item_ID) AS total_items_listed,
COUNT(DISTINCT CASE WHEN a.sale_price IS NOT NULL THEN a.item_ID END) AS total_items_sold,
COUNT(DISTINCT CASE WHEN a.winner IS NOT NULL THEN a.item_ID END) AS total_items_won,
COUNT(DISTINCT CASE WHEN ir.stars IS NOT NULL THEN ir.item_ID END) AS total_items_rated,
CASE
WHEN COUNT(i.item_ID) = 0 THEN 'N/A'
ELSE (SELECT item_condition
FROM (
 SELECT 
 listed_by,
 item_condition,
 ROW_NUMBER() OVER (PARTITION BY listed_by ORDER 
BY COUNT(*) DESC, item_condition) AS rn
 FROM Item
 WHERE listed_by = u.user_name
 GROUP BY listed_by, item_condition
 ) AS ranked_conditions
WHERE rn = 1)
 END AS most_frequent_condition
FROM User u
LEFT JOIN Item i ON u.user_name = i.listed_by
LEFT JOIN Auction a ON i.item_ID = a.item_ID
LEFT JOIN ItemRating ir ON u.user_name = ir.rated_by
GROUP BY u.user_name
ORDER BY u.user_name;"; 

// Perform the query
$result = mysqli_query($db, $query);

// Check if query was successful
if (!$result) {
    die("Error fetching data: " . mysqli_error($db));
}
?>

<?php include("lib/header.php"); ?>
<title>User Report</title>
</head>
<body>
    <div class="category-report-box">
        <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">User Report</div>
        
        <!-- Display table result -->
        <div>
            <?php if (mysqli_num_rows($result) == 0) {
                echo "No data found.";
            } else { ?>
                <table>
                    <tr>
                        <th>UserName</th>
                        <th>Listed</th>
                        <th>Sold</th>
                        <th>Won</th>
                        <th>Rated</th>
						<th>Most Frequent Condition</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['UserName']; ?></td>
                            <td><?php echo $row['total_items_listed']; ?></td>
                            <td><?php echo $row['total_items_sold']; ?></td>
                            <td><?php echo $row['total_items_won']; ?></td>
                            <td><?php echo $row['total_items_rated']; ?></td>
							<td><?php echo $row['most_frequent_condition']; ?></td>
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
