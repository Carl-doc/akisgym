<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>
</html>