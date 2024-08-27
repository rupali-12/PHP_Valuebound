<?php
// Include the database configuration file
require 'config/database.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int) $_GET['id'];

    try {
        // Delete user from the database
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        header("Location: users.php"); // Redirect to the users list
        exit;

    } catch (PDOException $e) {
        $errors[] = 'Error: ' . $e->getMessage();
    }
} else {
    $errors[] = 'Invalid user ID';
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<p class="error">' . htmlspecialchars($error) . '</p>';
    }
}
?>
