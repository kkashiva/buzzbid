<?php
include('lib/common.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user information from session
$username = $_SESSION['username'];
$position = '';
// Fetch user information from the database
$query = "SELECT first_name, last_name FROM User WHERE user_name='$username'";
$result = mysqli_query($db, $query);

$query_admin = "SELECT user_name,position FROM adminuser WHERE user_name='$username'";
$result_admin = mysqli_query($db, $query_admin);
$row1 = mysqli_fetch_assoc($result_admin);

// Check if the query was successful
if ($result) {
    // Fetch the user's first name and last name
    $row = mysqli_fetch_assoc($result);
    $userFirstName = $row['first_name'];
    $userLastName = $row['last_name'];
} else {
    // Handle error if the query fails
    die("Error fetching user information: " . mysqli_error($db));
}

$adminPosition = ''; // Fetch admin position from database or session if user is an admin

// Determine menu options based on user type
$menuOptions = [
    ['label' => 'Search for Items', 'url' => 'item_search.php'],
    ['label' => 'List Item', 'url' => 'item_list.php'],
    ['label' => 'View Auction Results', 'url' => 'view_auction_results.php']
];

if ($row1['position']<>'') {
    // Add admin-specific menu options
    $adminOptions = [
    ['label' => 'Category Report', 'url' => 'category_report.php'],
    ['label' => 'User Report', 'url' => 'user_report.php'],
    ['label' => 'Top Rated Items', 'url' => 'top_rated_items.php'],
	['label' => 'Auction Statistics', 'url' => 'auction_statistics.php'],
['label' => 'Cancelled Auction Details', 'url' => 'cancelled_auction_details.php']];
	$position = $row1['position'];
    // Add more admin menu options as needed
}
?>

<?php include("lib/header.php"); ?>
<title>Main Menu</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
	<div class="main-menu-box">
    <span class="close">&#10006;</span> <!-- Close symbol -->

	<div class="login-text">BuzzBid Main Menu</div>

	<img class="login-image" src="img/Buzzbid.png" alt="Image Description">
	<p>Welcome, <?php echo $userFirstName . " " . $userLastName."!"; ?></p>
    <?php if ($position<> '') { ?>
        <p>Administrative position: <?php echo $position; ?></p>
    <?php } ?>
	
	
	<div class="options">
            <div class="menu-options">
                <b><u>Auction Options</u></b>
                <?php foreach ($menuOptions as $option) { ?>
                    <div><a href="<?php echo $option['url']; ?>"><?php echo $option['label']; ?></a></div>
                <?php } ?>
            </div>
	
		<!-- Generate Report Links -->
        <?php if ($position <> '') { ?>
		<div class="admin-options">
                    <b><u>Reports</u></b>
                    <?php foreach ($adminOptions as $option) { ?>
                        <div><a href="<?php echo $option['url']; ?>"><?php echo $option['label']; ?></a></div>
                    <?php } ?>
                </div>
        <?php } ?>
	

	</div>
</body>
</html>