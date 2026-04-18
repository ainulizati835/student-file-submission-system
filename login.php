<?php
session_start();
include "db/connection.php";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user'] = $user;

        if($user['role'] == 'admin'){
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
    } else {
        echo "Login failed!";
    }
}
?>

<form method="POST">
    Email: <input type="email" name="email"><br>
    Password: <input type="password" name="password"><br>
    <button name="login">Login</button>
</form>