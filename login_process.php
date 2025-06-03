<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);


    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {


        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();


        if (password_verify($password, $hashed_password)) {


            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            if ($role === 'teacher') {

                header("Location: dashboard.php");

            } elseif ($role === 'student') {

                header("Location: studentDashboard.php");

            } else {



                echo "Unknown role.";

            }
            exit();


        } else {

            echo "Invalid password.";
        }
    } else {

        echo "User not found.";

    }

    $stmt->close();


    $conn->close();


}
?>