<?php
session_start();

require_once("../includes/db.php");
require_once("../includes/log_helper.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){
        echo "<script>alert('Please fill all fields'); window.location.href='login.php';</script>";
        exit();
    }

    $email = mysqli_real_escape_string($conn,$email);

    $query = "SELECT * FROM tbl_admin WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) == 1){

        $admin = mysqli_fetch_assoc($result);

        if($admin['status'] != "active"){
            echo "<script>alert('Account inactive'); window.location.href='login.php';</script>";
            exit();
        }

        if($password === $admin['password']){

            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'];

            create_log($conn, $admin['admin_id'], "LOGIN", "Admin logged into the system");

            header("Location: ../pages/dashboard.php");
            exit();

        }else{

            echo "<script>alert('Incorrect password'); window.location.href='login.php';</script>";
            exit();

        }

    }else{

        echo "<script>alert('User not found'); window.location.href='login.php';</script>";
        exit();

    }

}else{

    header("Location: login.php");
    exit();

}
?>