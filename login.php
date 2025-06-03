<?php
session_start();
include 'connection.php'; // Your DB connection file
$loggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? null; // Safe way to get role if set
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .login-form {
            max-width: 300px;
            margin: auto;
        }
        input, button {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
        }
        .dashboard-btn, .logout-btn {
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            margin-right: 10px;
        }
        .dashboard-btn:hover, .logout-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <?php if ($loggedIn): ?>
        <p>You are already logged in.</p>
        <?php if ($role === 'teacher'): ?>
            <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
        <?php elseif ($role === 'student'): ?>
            <a href="studentDashboard.php" class="dashboard-btn">Go to Student Dashboard</a>
        <?php else: ?>
            <p>Unknown role. Please contact admin.</p>
        <?php endif; ?>
        
        <!-- Logout button -->
        <a href="logout.php" class="logout-btn">Logout</a>

    <?php else: ?>
        <div class="login-form">
            <h2>Login</h2>
            <form method="POST" action="login_process.php">
                <input type="text" name="username" placeholder="Username" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Login</button>
            </form>
        </div>
    <?php endif; ?>

</body>
</html>
