<?php
session_start();

require_once("../includes/db.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){
        echo "<script>alert('Please fill all fields'); window.location.href='login.php';</script>";
        exit();
    }

    $email = mysqli_real_escape_string($conn,$email);

    /* CHECK ADMIN FIRST */
    $admin_query = "SELECT * FROM tbl_admin WHERE email='$email' LIMIT 1";
    $admin_result = mysqli_query($conn,$admin_query);

    if($admin_result && mysqli_num_rows($admin_result) == 1){
        $admin = mysqli_fetch_assoc($admin_result);

        if($admin['status'] != "active"){
            echo "<script>alert('Admin account inactive'); window.location.href='login.php';</script>";
            exit();
        }

        if($password === $admin['password']){
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'];

            header("Location: ../pages/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect admin password'); window.location.href='login.php';</script>";
            exit();
        }
    }

    /* CHECK MEMBER */
    $member_query = "SELECT * FROM tbl_member WHERE email='$email' LIMIT 1";
    $member_result = mysqli_query($conn,$member_query);

    if($member_result && mysqli_num_rows($member_result) == 1){
        $member = mysqli_fetch_assoc($member_result);

        if(password_verify($password, $member['password'])){
            $_SESSION['member_id'] = $member['member_id'];
            $_SESSION['member_name'] = trim($member['first_name'] . ' ' . $member['last_name']);
            $_SESSION['member_email'] = $member['email'];

            header("Location: ../pages/user_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect user password'); window.location.href='login.php';</script>";
            exit();
        }
    }

    echo "<script>alert('Account not found'); window.location.href='login.php';</script>";
    exit();
}
?>