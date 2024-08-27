<?php
// Include the database configuration file
require 'config/database.php';

$errors = [];
$success = '';
$user = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int) $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect form data
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $dob = trim($_POST['dob']);

        // Basic validation
        if (empty($firstname)) {
            $errors[] = 'First name is required';
        }
        if (empty($lastname)) {
            $errors[] = 'Last name is required';
        }
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        if (empty($mobile)) {
            $errors[] = 'Mobile number is required';
        } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
            $errors[] = 'Mobile number must be 10 digits';
        }
        if (empty($dob)) {
            $errors[] = 'Date of birth is required';
        }

        // Update user data
        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, mobilenumber = ?, dob = ? WHERE id = ?");
                $stmt->execute([$firstname, $lastname, $email, $mobile, $dob, $userId]);

                $success = 'User updated successfully!';
                header("Location: users.php");
                exit();
            } catch (PDOException $e) {
                $errors[] = 'Error: ' . $e->getMessage();
            }
        }
    } else {
        // Fetch user details
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errors[] = 'Error: ' . $e->getMessage();
        }
    }
} else {
    $errors[] = 'Invalid user ID';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/edit-user.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>

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
            <form method="POST" action="">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required><br>

                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required><br>

                <label for="email">Email ID:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

                <label for="mobile">Mobile Number:</label>
                <input type="text" id="mobile" name="mobile" value="<?= htmlspecialchars($user['mobilenumber']) ?>" required><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required><br>

                <input type="submit" value="Update">
            </form>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
</body>

</html>
