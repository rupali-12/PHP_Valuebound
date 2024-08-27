<?php
session_start(); // Start the session to check user login status
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <!-- If not logged in -->
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php else: ?>
                    <!-- If logged in -->
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
                <li><a href="users.php">User List</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Welcome to the Home Page</h1>
        
        <?php
        // Display content based on URL parameter
        if (isset($_GET['view']) && $_GET['view'] === 'userlist') {
            echo '<h2>User List</h2>';
            // Include code to display user list
            require 'list_users.php';
        }
        ?>
    </main>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
