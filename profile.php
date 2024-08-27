<?php
// Include the database configuration file
require 'config/database.php';

session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$errors = [];
$success = '';
$user = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imagePath = 'uploads/' . basename($image);

    // Handle image upload
    if (!empty($image)) {
        // Validate image file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($imageTmp);
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = 'Only JPG, PNG, and GIF files are allowed';
        }

        // Move uploaded file to the server
        if (empty($errors) && move_uploaded_file($imageTmp, $imagePath)) {
            // Update user data with new image
            try {
                $stmt = $pdo->prepare("UPDATE users SET image = ? WHERE id = ?");
                $stmt->execute([$imagePath, $userId]);
                $success = 'Profile image updated successfully!';
            } catch (PDOException $e) {
                $errors[] = 'Error: ' . $e->getMessage();
            }
        } else {
            $errors[] = 'Error uploading image';
        }
    }
}

// Fetch user details
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>
    <!-- Navigation Bar -->
     <nav class="navbar">
        <a href="index.php" class="nav-link">Home</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </nav>

    <div class="container">
        <h2>User Profile</h2>

        <?php if (!empty($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($user): ?>
            <div class="profile-info">
                <h3>Welcome, <?= htmlspecialchars($user['firstname']) ?>!</h3>
                <p><strong>Email ID:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['dob']) ?></p>
                
                <?php if (!empty($user['image']) && file_exists($user['image'])): ?>
                    <img src="<?= htmlspecialchars($user['image']) ?>" alt="Profile Image" class="profile-image">
                <?php else: ?>
                    <p>No profile image set.</p>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <label for="image">Upload New Profile Image:</label>
                    <input type="file" id="image" name="image" accept="image/*"><br><br>
                    <input type="submit" value="Update Profile Image" class="btn">
                </form>
            </div>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
