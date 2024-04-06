<?php
include('lib/common.php');

// Initialize variables
$firstName = $lastName = $username = $password = $confirmPassword = '';
$firstNameError = $lastNameError = $usernameError = $passwordError = $confirmPasswordError = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $firstName = mysqli_real_escape_string($db, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($db, $_POST['last_name']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($db, $_POST['confirm_password']);

    // Validate input fields
    if (empty($firstName)) {
		array_push($error_msg, "Please enter your first name");
        $firstNameError = 'Please enter your first name';
    }

    if (empty($lastName)) {
        $lastNameError = 'Please enter your last name';
    }

    if (empty($username)) {
        $usernameError = 'Please enter a username';
    }

    if (empty($password)) {
        $passwordError = 'Please enter a password';
    } elseif (strlen($password) < 8) {
        $passwordError = 'Password must be at least 8 characters long';
    }

    if ($password !== $confirmPassword) {
        $confirmPasswordError = 'Passwords do not match';
    }

    // If there are no errors, proceed with registration
    if (empty($firstNameError) && empty($lastNameError) && empty($usernameError) && empty($passwordError) && empty($confirmPasswordError)) {
        // Hash the password
        $hashedPassword = $password;

        // Insert user data into the database
        $insertQuery = "INSERT INTO User (first_name, last_name, user_name, password) 
                        VALUES ('$firstName', '$lastName', '$username', '$hashedPassword')";

        if (mysqli_query($db, $insertQuery)) {
            // Registration successful
            header("Location: login.php");
            exit();
        } else {
            array_push($error_msg, 'Error: ' . mysqli_error($db));
        }
    }
}
?>

<?php include("lib/header.php"); ?>
<title>User Registration</title>
</head>
<body>
    <div class="header">
        <h1></h1>
    </div>
    <div class="register-box">
        <span class="close">&#10006;</span> <!-- Close symbol -->
        <div class="login-text">BuzzBid New User Registration</div>
        <img class="login-image" src="img/Buzzbid.png" alt="Image Description">
        <div id="main_container">
            <div class="center_content">
                <div class="text_box">
                    <form action="register.php" method="post">
                        <div class="form_group">
                            <label for="first_name">First Name:</label>
                            <input type="text" name="first_name" id="first_name" required>
                    
                        </div>
                        <div class="form_group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" name="last_name" id="last_name" required>
                        </div>
                        <div class="form_group">
                            <label for="username">Username:</label>
                            <input type="text" name="username" id="username" required>
                        </div>
                        <div class="form_group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <div class="form_group">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" name="confirm_password" id="confirm_password" required>
                        </div>
                        <div class="form_group" style>
                            <button type="submit" style="margin: 0 auto;">Register</button>
                        </div>
						
						<div style="text-align: center;">
                            <div style="margin-top: 10px;">
                                <span class="error"><?php echo $firstNameError; ?></span><br>
                                <span class="error"><?php echo $lastNameError; ?></span><br>
                                <span class="error"><?php echo $usernameError; ?></span><br>
                                <span class="error"><?php echo $passwordError; ?></span><br>
                                <span class="error"><?php echo $confirmPasswordError; ?></span>
                            </div>
                        </div>
						
                    </form>
                </div>
                <?php include("lib/error.php"); ?>
            </div>
        </div>
    </div>
</body>
</html>
