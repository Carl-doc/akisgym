<?php
session_start();
require_once("../includes/db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}

$first_name = mysqli_real_escape_string($conn, trim($_POST['first_name'] ?? ''));
$last_name  = mysqli_real_escape_string($conn, trim($_POST['last_name'] ?? ''));
$email      = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
$phone      = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
$gender     = mysqli_real_escape_string($conn, trim($_POST['gender'] ?? ''));
$password   = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

if (
    empty($first_name) ||
    empty($last_name) ||
    empty($email) ||
    empty($phone) ||
    empty($gender) ||
    empty($password) ||
    empty($confirm_password)
) {
    echo "<script>alert('Please fill in all fields.'); window.location.href='register.php';</script>";
    exit();
}

if ($password !== $confirm_password) {
    echo "<script>alert('Passwords do not match.'); window.location.href='register.php';</script>";
    exit();
}

$check_email = mysqli_query($conn, "SELECT member_id FROM tbl_member WHERE email='$email' LIMIT 1");
if (!$check_email) {
    die("Check email query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($check_email) > 0) {
    echo "<script>alert('Email already exists.'); window.location.href='register.php';</script>";
    exit();
}

$next_number = 1;
$latest = mysqli_query($conn, "SELECT member_id FROM tbl_member ORDER BY member_id DESC LIMIT 1");
if (!$latest) {
    die("Latest member query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($latest) > 0) {
    $row = mysqli_fetch_assoc($latest);
    $next_number = $row['member_id'] + 1;
}

$member_code = "MBR-" . str_pad($next_number, 3, "0", STR_PAD_LEFT);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "
    INSERT INTO tbl_member
    (member_code, first_name, last_name, gender, email, phone, password, status, joined_date)
    VALUES
    ('$member_code', '$first_name', '$last_name', '$gender', '$email', '$phone', '$hashed_password', 'active', CURDATE())
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Insert failed: " . mysqli_error($conn));
}

echo "<script>alert('Registration successful. You can now log in.'); window.location.href='login.php';</script>";
exit();
?>