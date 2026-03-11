<?php
session_start();
require_once("../includes/db.php");

if(!isset($_SESSION['member_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$member_id = $_SESSION['member_id'];

/* AUTO STATUS UPDATE */
mysqli_query($conn, "
    UPDATE tbl_member_subscriptions
    SET status = 'pending'
    WHERE start_date > CURDATE()
    AND status != 'cancelled'
");

mysqli_query($conn, "
    UPDATE tbl_member_subscriptions
    SET status = 'active'
    WHERE start_date <= CURDATE()
    AND end_date >= CURDATE()
    AND status != 'cancelled'
");

mysqli_query($conn, "
    UPDATE tbl_member_subscriptions
    SET status = 'expired'
    WHERE end_date < CURDATE()
    AND status != 'cancelled'
");

/* CHOOSE PLAN */
if(isset($_POST['choose_plan'])){
    $plan_id = mysqli_real_escape_string($conn, $_POST['plan_id']);

    $active_check = mysqli_query($conn, "
        SELECT member_subscription_id
        FROM tbl_member_subscriptions
        WHERE member_id = '$member_id'
        AND status IN ('active', 'pending')
        LIMIT 1
    ");

    if(mysqli_num_rows($active_check) > 0){
        header("Location: user_subscriptions.php?exists=1");
        exit();
    }

    $plan_query = mysqli_query($conn, "
        SELECT subscription_id, duration_days
        FROM tbl_subscription
        WHERE subscription_id = '$plan_id'
        LIMIT 1
    ");

    if($plan_query && mysqli_num_rows($plan_query) > 0){
        $plan = mysqli_fetch_assoc($plan_query);

        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d", strtotime("+".$plan['duration_days']." days"));

        mysqli_query($conn, "
            INSERT INTO tbl_member_subscriptions
            (member_id, subscription_id, start_date, end_date, status)
            VALUES
            ('$member_id', '".$plan['subscription_id']."', '$start_date', '$end_date', 'active')
        ");

        header("Location: user_subscriptions.php?success=1");
        exit();
    }
}

/* LOAD AVAILABLE PLANS */
$plans = mysqli_query($conn, "
    SELECT *
    FROM tbl_subscription
    WHERE status = 'active'
    ORDER BY price ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Choose Subscription | Aki's Fitness Gym</title>
<link rel="stylesheet" href="/akisgym/assets/css/style.css">

<style>
.plan-grid{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:20px;
}

.plan-card{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:24px;
    padding:24px;
    box-shadow:0 6px 18px rgba(15, 23, 42, 0.04);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.plan-name{
    font-size:24px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:8px;
}

.plan-desc{
    color:#64748b;
    font-size:14px;
    margin-bottom:18px;
    min-height:40px;
}

.plan-price{
    font-size:34px;
    font-weight:800;
    color:#111827;
    margin-bottom:6px;
}

.plan-duration{
    font-size:14px;
    color:#64748b;
    margin-bottom:18px;
}

.plan-access{
    display:inline-block;
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    background:#ede9fe;
    color:#5b21b6;
    margin-bottom:18px;
    text-transform:capitalize;
}

.choose-plan-btn{
    width:100%;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg, #4f46e5, #4338ca);
    color:#fff;
    font-size:15px;
    font-weight:700;
    height:46px;
    cursor:pointer;
}

.success-box{
    background:#dcfce7;
    color:#166534;
    border:1px solid #bbf7d0;
    padding:14px 16px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
    font-weight:600;
}

.warning-box{
    background:#fef3c7;
    color:#92400e;
    border:1px solid #fde68a;
    padding:14px 16px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
    font-weight:600;
}

@media (max-width: 1000px){
    .plan-grid{
        grid-template-columns:1fr;
    }
}
</style>
</head>
<body class="saas-body">

<div class="saas-layout">

    <aside class="saas-sidebar">
        <div class="saas-brand">
            <div class="saas-brand-logo"></div>
            <div class="saas-brand-text">Aki's Fitness Gym</div>
        </div>

        <nav class="saas-menu">
            <a href="user_dashboard.php" class="saas-link">
                <span class="saas-link-icon">☺</span>
                <span>User Dashboard</span>
            </a>

            <a href="user_subscriptions.php" class="saas-link active">
                <span class="saas-link-icon">◉</span>
                <span>Subscription Plans</span>
            </a>
        </nav>
    </aside>

    <div class="saas-main">

        <header class="saas-topbar">
            <div class="saas-topbar-left">
                <div>
                    <h2>Subscription Choices</h2>
                    <p>Select a membership plan that fits your needs</p>
                </div>
            </div>

            <div class="saas-topbar-right">
                <a href="../auth/logout.php" class="saas-logout-btn">Log Out</a>
            </div>
        </header>

        <main class="saas-content">

            <?php if(isset($_GET['success'])): ?>
                <div class="success-box">Subscription selected successfully.</div>
            <?php endif; ?>

            <?php if(isset($_GET['exists'])): ?>
                <div class="warning-box">You already have an active or pending subscription.</div>
            <?php endif; ?>

            <div class="plan-grid">
                <?php while($plan = mysqli_fetch_assoc($plans)): ?>
                    <div class="plan-card">
                        <div>
                            <div class="plan-name"><?php echo htmlspecialchars($plan['plan_name']); ?></div>
                            <div class="plan-desc"><?php echo htmlspecialchars($plan['description']); ?></div>
                            <div class="plan-price">₱<?php echo number_format($plan['price'], 2); ?></div>
                            <div class="plan-duration"><?php echo htmlspecialchars($plan['duration_days']); ?> days</div>
                            <div class="plan-access"><?php echo htmlspecialchars($plan['access_level']); ?></div>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="plan_id" value="<?php echo $plan['subscription_id']; ?>">
                            <button type="submit" name="choose_plan" class="choose-plan-btn">Choose Plan</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>

        </main>
    </div>
</div>

</body>
</html>