<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Login Page</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body class ="login-body">
    <div class="login-container">
            <img src="../../../assets/logo/logo.png" alt="logo" class="logo">
            <h2>Aki's Fitness Gym</h2>
            <form action="login.php" method="post">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" placeholder="e.g., JhonDoe" required>
                    <label for="password">Password:</label>
                    <input type="password" name="password" placeholder="********" required>
                </div>
                <button type="submit">Login</button>
            </form>
    </div>
=======
    <title>Login | Aki's Fitness Gym</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">

    <section class="auth-wrapper">
        <div class="auth-panel">
            <div class="login-card">

                <div class="login-logo"></div>

                <h1 class="login-title">Aki’s Fitness Gym</h1>

                <form action="process_login.php" method="POST" class="login-form">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="login-btn">Login</button>

                    <p class="register-text">
                        Don’t have an account?
                        <a href="register.php">Register</a>
                    </p>
                </form>

            </div>
        </div>
    </section>

>>>>>>> 591f711b84babce153700f9b2d27948b730dcdc6
</body>
</html>