<?php
session_start(); // Start the session to check user login status

// Include the database configuration file
require 'config/database.php';

$users = [];
$errors = [];
$perPage = 5; // Number of records per page

// Get the current page from the URL, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    // Get the total number of users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $totalUsers = $stmt->fetchColumn();
    $totalPages = ceil($totalUsers / $perPage);

    // Fetch users for the current page
    $stmt = $pdo->prepare("SELECT * FROM users LIMIT :start, :perPage");
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="stylesheet" href="./css/users.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="index.php" class="nav-link">Home</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </nav>

    <h2>Registered Users</h2>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Date of Birth</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['firstname']) ?></td>
                        <td><?= htmlspecialchars($user['lastname']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['mobilenumber']) ?></td>
                        <td><?= htmlspecialchars($user['dob']) ?></td>
                        <td>
                            <!-- Check if user is logged in before showing Edit and Delete links -->
                             <div class="table-actions">
                                 <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="edit_user.php?id=<?= htmlspecialchars($user['id']) ?> " class="edit">Edit</a> |
                                    <a href="delete_user.php?id=<?= htmlspecialchars($user['id']) ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    <?php else: ?>
                                        Login to edit or delete
                                        <?php endif; ?>
                                    </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination controls -->
    <!-- Pagination controls -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="button">Previous</a>
    <?php else: ?>
        <span class="button disabled">Previous</span>
    <?php endif; ?>

    <span>Page <?= $page ?> of <?= $totalPages ?></span>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" class="button">Next</a>
    <?php else: ?>
        <span class="button disabled">Next</span>
    <?php endif; ?>
</div>

</body>
</html>
