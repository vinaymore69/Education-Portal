<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        .dashboard-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Welcome to the Website</h1>

    <?php if ($loggedIn): ?>
        <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
    <?php else: ?>
        <p>Please <a href="login.php">login</a> to access your dashboard.</p>
    <?php endif; ?>

</body>
</html>
