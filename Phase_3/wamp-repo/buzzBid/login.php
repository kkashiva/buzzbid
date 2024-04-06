<?php
//this page utilized the GTOnline sample project for OMSCS 6400
include('lib/common.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredusername = mysqli_real_escape_string($db, $_POST['username']);
    $enteredPassword = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($enteredusername)) {
        array_push($error_msg, "Please enter an username.");
    }

    if (empty($enteredPassword)) {
        array_push($error_msg, "Please enter a password.");
    }

    if (!empty($enteredusername) && !empty($enteredPassword)) {
        $query = "SELECT password FROM User WHERE user_name='$enteredusername'";
        
        $result = mysqli_query($db, $query);
        //include('lib/show_queries.php');
        $count = mysqli_num_rows($result); 
        
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $storedPassword = $row['password']; 
            
            $options = [
                'cost' => 8,
            ];
            // Convert the plaintext passwords to their respective hashses
            $storedHash = password_hash($storedPassword, PASSWORD_DEFAULT , $options);   // May not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $enteredHash = password_hash($enteredPassword, PASSWORD_DEFAULT , $options); 
            
            // Depending on if you are storing the hash $storedHash or plaintext $storedPassword 
            if (password_verify($enteredPassword, $storedHash) ) {
                array_push($query_msg, "Password is Valid! ");
                $_SESSION['username'] = $enteredusername;
                array_push($query_msg, "Logging in... ");
                header(REFRESH_TIME . 'url=main_menu.php'); // To view the password hashes and login success/failure
                
            } else {
                array_push($error_msg, "Login failed: " . $enteredusername . NEWLINE);
				array_push($error_msg, "Password is incorrect." );
                //array_push($error_msg, "To demo enter: ". NEWLINE . "michael@bluthco.com". NEWLINE ."michael123");
            }
            
        } else {
            array_push($error_msg, "The username entered does not exist: " . $enteredusername);
        }
    }
}
?>

<?php include("lib/header.php"); ?>
<title>BuzzBid Login</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
	<div class="login-box">
    <span class="close">&#10006;</span> <!-- Close symbol -->

    <div class="login-text">BuzzBid Login</div> <!-- Corrected Title Placement -->
    <img class="login-image" src="img/Buzzbid.png" alt="Image Description">
	
	
    <form action="#" method="post" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
        <input type="button" value="Register" onclick="window.location.href='register.php'">
    </form>
	<?php include("lib/error.php"); ?>
</div>



    <script>
        // JavaScript code to handle redirection when the "Register" button is clicked
        document.querySelector('.register').addEventListener('click', function() {
            window.location.href = 'register.php';
        });
    </script>

    <?php?>
</body>
</html>