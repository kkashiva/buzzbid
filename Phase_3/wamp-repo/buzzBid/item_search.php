<?php 
include("lib/header.php"); 
include('lib/common.php');
session_start();
$_SESSION['search_cache'] = '';
?>

<title>Item Search</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
    <div class="register-box">
    <span class="close" id="closeButton">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">Item Search</div>
        <div id="main_container">
            <div class="center_content">
                <div class="text_box">
                    <form action="item_search_results.php" method="post">
                        <div class="form_group">
                            <label for="keyword">Keyword</label>
                            <input type="text" name="keyword" id="keyword">
                        </div>
                        <div class="form_group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                            <option value = ''></option>
                            <?php 
                            $query = "SELECT category_name AS category FROM Category";
                            $result = mysqli_query($db, $query);
                            if (mysqli_num_rows($result) == 0) {?>
                            <?php } else { while ($row = mysqli_fetch_assoc($result)) { ?>
                                <option value="<?php echo $row['category'];?>"> <?php echo $row['category']; ?> </option>
                            <?php } }?>
                            </select>
                        </div>
                        <div class="form_group">
                            <label for="minprice">Minimum Price $</label>
                            <input type="text" name="minprice" id="minprice">
                        </div>
                        <div class="form_group">
                            <label for="maxprice">Maximum Price $</label>
                            <input type="text" name="maxprice" id="maxprice">
                        </div>
                        <div class="form_group">
                            <label for="itemCondition">Condition at least</label>
                            <select id="itemCondition" name = "itemCondition">
                            <option value = ''></option>
                            <option value = "New">New</option>  
                            <option value = "Very Good">Very Good</option>
                            <option value = "Good">Good</option>
                            <option value = "Fair">Fair</option>
                            <option value = "Poor">Poor</option>  
                            <select>
                        </div>
                        <div class="form_group">
                            <button type="reset" style="margin: 0 auto;">Cancel</button>
                            <button type="submit" style="margin: 0 auto;">Submit</button>
                        </div>
						
						<!-- <div style="text-align: center;">
                            <div style="margin-top: 10px;">
                                <span class="error"><?php echo $firstNameError; ?></span><br>
                                <span class="error"><?php echo $lastNameError; ?></span><br>
                                <span class="error"><?php echo $usernameError; ?></span><br>
                                <span class="error"><?php echo $passwordError; ?></span><br>
                                <span class="error"><?php echo $confirmPasswordError; ?></span>
                            </div>
                        </div> -->
						
                    </form>
                <!-- </div>
                <?php include("lib/error.php"); ?>
            </div> -->
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
