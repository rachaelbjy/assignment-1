<?php
// 🚨 1. 开启 Session
session_start();
require_once('settings.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, trim($_POST['login'])); 
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    // 🚨 3. 第一步：检查是不是 Admin (管理员)
    $admin_sql = "SELECT id FROM `admin` WHERE username = ? AND password = ?";
    $admin_stmt = mysqli_prepare($conn, $admin_sql);
    mysqli_stmt_bind_param($admin_stmt, "ss", $username, $password);
    mysqli_stmt_execute($admin_stmt);
    mysqli_stmt_store_result($admin_stmt);

    if (mysqli_stmt_num_rows($admin_stmt) > 0) {
        // 验证成功，是管理员
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;
        mysqli_stmt_close($admin_stmt);
        mysqli_close($conn);
        // 管理员登录成功，跳去首页 (或者你可以改去 manager.php)
        echo "<script>alert('Welcome back, Admin!'); window.location.href = 'index.php';</script>";
        exit();
    }
    mysqli_stmt_close($admin_stmt);

    // 🚨 4. 第二步：如果不是 Admin，检查是不是 User (普通顾客)
    $user_sql = "SELECT id, fname FROM `user` WHERE username = ? AND password = ?";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "ss", $username, $password);
    mysqli_stmt_execute($user_stmt);
    mysqli_stmt_store_result($user_stmt);

    if (mysqli_stmt_num_rows($user_stmt) > 0) {
        // 验证成功，是普通顾客
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $username;
        mysqli_stmt_close($user_stmt);
        mysqli_close($conn);
        // 顾客登录成功，跳去首页
        echo "<script>alert('Login Successful!'); window.location.href = 'index.php';</script>";
        exit();
    }
    mysqli_stmt_close($user_stmt);

    // 🚨 5. 第三步：账号或密码错误
    // 把填好的账号装进背包，退回 login 页面
    $_SESSION['login_data'] = ['login' => $username];
    header("Location: login.php?error=invalid_credentials");
    exit();

} else {
    header("Location: login.php");
    exit();
}
?>