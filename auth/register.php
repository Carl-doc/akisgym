<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Aki's Fitness Gym</title>
<link rel="stylesheet" href="../assets/css/style.css">

<style>
.register-card{
    width:100%;
    max-width:520px;
    background:rgba(255,255,255,0.96);
    backdrop-filter:blur(10px);
    border:1px solid rgba(255,255,255,0.35);
    border-radius:28px;
    padding:36px 30px 28px;
    box-shadow:0 24px 60px rgba(2, 6, 23, 0.35);
}

.register-title{
    font-size:30px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:8px;
    text-align:center;
}

.register-subtitle{
    text-align:center;
    color:#64748b;
    font-size:14px;
    margin-bottom:24px;
}

.register-form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.register-form .full{
    grid-column:1 / -1;
}

.register-field{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.register-field label{
    font-size:14px;
    font-weight:700;
    color:#334155;
}

.register-field input,
.register-field select{
    width:100%;
    height:46px;
    border:1px solid #dbe4ee;
    border-radius:14px;
    background:#f8fafc;
    padding:0 14px;
    font-size:15px;
    color:#0f172a;
    outline:none;
}

.register-field input:focus,
.register-field select:focus{
    background:#ffffff;
    border-color:#6366f1;
    box-shadow:0 0 0 4px rgba(99,102,241,0.12);
}

.register-btn{
    width:100%;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg, #4f46e5, #4338ca);
    color:white;
    font-size:15px;
    font-weight:700;
    height:48px;
    margin-top:6px;
    cursor:pointer;
    box-shadow:0 10px 20px rgba(79,70,229,0.25);
}

.login-link{
    text-align:center;
    font-size:14px;
    margin-top:16px;
    color:#475569;
}

.login-link a{
    color:#4f46e5;
    text-decoration:none;
    font-weight:700;
}

.login-link a:hover{
    text-decoration:underline;
}

@media (max-width:640px){
    .register-card{
        max-width:92%;
        padding:30px 20px 24px;
    }

    .register-form{
        grid-template-columns:1fr;
    }

    .register-form .full{
        grid-column:auto;
    }
}
</style>
</head>
<body class="auth-body">

<section class="auth-wrapper">
    <div class="auth-panel">

        <div class="register-card">
            <h1 class="register-title">Create Account</h1>
            <p class="register-subtitle">Register as a gym member</p>

            <form action="process_register.php" method="POST" class="register-form">

                <div class="register-field">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="register-field">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                <div class="register-field full">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="register-field">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="register-field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="register-field full">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="register-field full">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="register-field full">
                    <button type="submit" name="register_user" class="register-btn">Create Account</button>
                </div>

            </form>

            <p class="login-link">
                Already have an account?
                <a href="login.php">Login</a>
            </p>
        </div>

    </div>
</section>

</body>
</html>