<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | Aki's Fitness Gym</title>

<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-body">

<section class="auth-wrapper">

<div class="auth-panel">

<div class="login-card">

<div class="login-logo">
    <img src="../assets/logo/logo.png" alt="Gym Logo">
</div>  

<h1 class="login-title">Aki’s Fitness Gym</h1>

<form action="process_login.php" method="POST" class="login-form">

<div class="form-group">
<label for="username">Username</label>
<input 
type="text"
id="username"
name="username"
placeholder="Enter your email"
required
>
</div>

<div class="form-group">
<label for="password">Password</label>
<input 
type="password"
id="password"
name="password"
placeholder="********"
required
>
</div>

<button type="submit" class="login-btn">
Login
</button>

<p class="register-text">
Don’t have an account?
<a href="register.php">Register</a>
</p>

</form>

</div>

</div>

</section>

</body>
</html>