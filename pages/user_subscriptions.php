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

/*
|--------------------------------------------------------------------------
| PLAN FEATURES / INCLUSIONS
|--------------------------------------------------------------------------
| You can edit these anytime
*/
$plan_features = [
    'Regular' => [
        'Access to gym equipment',
        'Locker room access',
        'Basic fitness assessment',
        'Standard workout area',
    ],
    'Premium' => [
        'Access to gym equipment',
        'Group exercise classes',
        'Locker room access',
        'Basic fitness assessment',
        'Priority support at reception',
    ],
    'VIP' => [
        'Full gym equipment access',
        'Group exercise classes',
        'Personal trainer support',
        'Priority locker room access',
        'Exclusive VIP assistance',
        'Advanced fitness assessment',
    ]
];

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

.plan-inclusion-title{
    font-size:15px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:10px;
}

.plan-inclusion-list{
    margin:0 0 22px;
    padding-left:18px;
    color:#475569;
    font-size:14px;
}

.plan-inclusion-list li{
    margin-bottom:8px;
    line-height:1.4;
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
    text-decoration:none;
    display:flex;
    align-items:center;
    justify-content:center;
}

.choose-plan-btn:hover{
    opacity:.95;
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
                    <?php
                        $current_plan_name = trim($plan['plan_name']);
                        $features = $plan_features[$current_plan_name] ?? [
                            'Access to available gym facilities',
                            'Standard membership benefits'
                        ];
                    ?>
                    <div class="plan-card">
                        <div>
                            <div class="plan-name"><?php echo htmlspecialchars($plan['plan_name']); ?></div>
                            <div class="plan-desc"><?php echo htmlspecialchars($plan['description']); ?></div>
                            <div class="plan-price">₱<?php echo number_format($plan['price'], 2); ?></div>
                            <div class="plan-duration"><?php echo htmlspecialchars($plan['duration_days']); ?> days</div>
                            <div class="plan-access"><?php echo htmlspecialchars($plan['access_level']); ?></div>

                            <div class="plan-inclusion-title">Plan Inclusions</div>
                            <ul class="plan-inclusion-list">
                                <?php foreach($features as $feature): ?>
                                    <li><?php echo htmlspecialchars($feature); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <a href="user_payment.php?subscription_id=<?php echo $plan['subscription_id']; ?>" class="choose-plan-btn">
                            Choose Plan
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>

        </main>
    </div>
</div>

</body>
</html>