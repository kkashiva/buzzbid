<?php
include('lib/common.php');

// SQL query to fetch data from the table
$query = "SELECT
 i.item_id,
 i.listed_by,
 a.canceled_time,
 a.cancelation_reason
FROM Auction a
INNER JOIN Item i ON a.item_ID = i.item_ID
WHERE a.canceled_time IS NOT NULL
ORDER BY i.item_ID DESC"; 

// Perform the query
$result = mysqli_query($db, $query);

// Check if query was successful
if (!$result) {
    die("Error fetching data: " . mysqli_error($db));
}
?>

<?php include("lib/header.php"); ?>
<title>Cancelled Auction Details</title>
</head>
<body>
    <div class="cancelled-report-box">
        <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">Cancelled Auction Details</div>
        
        <!-- Display table result -->
        <div>
            <?php if (mysqli_num_rows($result) == 0) {
                echo "No data found.";
            } else { ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Listed by</th>
                        <th>Cancelled Date</th>
                        <th>Reason</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['item_id']; ?></td>
                            <td><?php echo $row['listed_by']; ?></td>
                            <td><?php echo $row['canceled_time']; ?></td>
                            <td><?php echo $row['cancelation_reason']; ?></td>
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
