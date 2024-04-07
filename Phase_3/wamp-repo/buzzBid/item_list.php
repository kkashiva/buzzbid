<?php
include('lib/common.php');

// initialize variables
$itemName = $description = $category = $condition = $startBid = $minSalePrice = $auctionEnds = $getItNowPrice = '';
$returnsAccepted = 0;

// check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $itemName = $_POST['itemName'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $condition = $_POST['condition'];
    $startBid = $_POST['startBid'];
    $minSalePrice = $_POST['minSalePrice'];
    $auctionEnds = $_POST['auctionEnds'];
    $getItNowPrice = $_POST['getItNowPrice'];
    $returnsAccepted = isset($_POST['returnsAccepted']) ? 1 : 0;

    // Retrieve the username of the user listing the item from the session variable
    // Check if the session variable 'username' is set from login.php
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
    // redirect the user to the login page, showing an error
        array_push($error_msg, "You must be logged in to list an item.");
        header("Location: login.php");
        exit();
    }

    // Validate form data
    $errors = array();

    if (empty($itemName)) {
        $errors[] = 'Item Name is required.';
    }

    if (empty($description)) {
        $errors[] = 'Description is required.';
    }

    if (empty($category)) {
        $errors[] = 'Category is required.';
    }

    if (empty($startBid)) {
        $errors[] = 'Start auction bidding at is required.';
    }

    if (empty($minSalePrice)) {
        $errors[] = 'Minimum sale price is required.';
    }

    if (empty($auctionEnds)) {
        $errors[] = 'Auction ends in is required.';
    }

    // If there are no errors, proceed with listing the item
    if (empty($errors)) {
        // insert query to push form data into db table Item
        $insertQuery = "INSERT INTO Item (`listed_by`, `item_name`, `description`, `returnable`, `category`, `item_condition`) 
        VALUES ('$username', '$itemName', '$description', '$returnsAccepted', '$category', '$condition')";
        
        // Hardcoded insert query for testing
        // $insertQuery = "INSERT INTO Item (`listed_by`, `item_name`, `description`, `returnable`, `category`, `item_condition`) VALUES ('kktest', 'Computer', 'Macbook Air', 1, 'Electronics', 'New')";
        
        if(mysqli_query($db, $insertQuery)) {
            // Insert successful
            header('Location: main_menu.php');
            exit;
        } else {
            // Insert failed
            array_push($error_msg, 'Error: ' . mysqli_error($db));
        }
        
    } else {
        // Display the errors
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
    }
}
?>

<?php include("lib/header.php"); ?>
<title>New Item for Auction</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
	<div class="item-list-box">
    <span class="close">&#10006;</span> <!-- Close symbol -->

    <div class="item-list-text">New Item for Auction</div> 

        <form action="item_list.php" method="post">
            <div class="form-row">
                <label for="itemName">Item Name</label>
                <input type="text" id="itemName" name="itemName"><br>
            </div>
            <div class="form-row" id="description">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea><br>
            </div>
            <div class="form-row dropdown">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <?php
                    // Fetch categories from db table 'Category' and sort alphabetically
                    $query = "SELECT category_name FROM Category ORDER BY category_name ASC";
                    $result = mysqli_query($db, $query);

                    // Check if query was successful
                    if ($result) {
                        // Loop through the result set and display options
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['category_name'] . '">' . $row['category_name'] . '</option>';
                        }
                    } else {
                        // Display an error message if query fails
                        echo '<option value="">Error fetching categories</option>';
                    }
                    ?>
                </select><br>
            </div>            
            <div class="form-row dropdown">
                <!-- hardcoded condition values -->
                <label for="condition">Condition</label>
                <select id="condition" name="condition">
                    <option value="New">New</option>
                    <option value="Very Good">Very Good</option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Poor">Poor</option>
                </select><br>
            </div>
            <div class="form-row price">
                <label for="startBid">Start auction bidding at $</label>
                <input type="number" id="startBid" name="startBid" step="0.01" min="0"><br>
            </div>
            <div class="form-row price">
                <label for="minSalePrice">Minimum sale price $</label>
                <input type="number" id="minSalePrice" name="minSalePrice" step="0.01" min="0"><br>
            </div>
            <div class="form-row dropdown">
                <label for="auctionEnds">Auction ends in</label>
                <select id="auctionEnds" name="auctionEnds">
                    <option value="1">1 day</option>
                    <option value="3">3 days</option>
                    <option value="5">5 days</option>
                    <option value="7">7 days</option>
                </select><br>
            </div>
            <div class="form-row price">
                <label for="getItNowPrice">Get it now price $</label>
                <input type="number" id="getItNowPrice" name="getItNowPrice" step="0.01" min="0"><br>
            </div>
            <div class="form-row checkbox">
                <label for="returnsAccepted">Returns accepted</label>
                <input type="checkbox" id="returnsAccepted" name="returnsAccepted">
            </div>
            <div class="form-buttons">    
                <div class="form-button">
                    <input type="button" value="Cancel" onclick="history.back()">
                </div>
                <div class="form-button">
                    <input type="submit" value="List my Item">
                </div>
            </div>
        </form>
        <?php include("lib/error.php"); ?>
    </div>
</body>
</html>
