<?php
session_start();
require_once("../includes/db.php");

if(!isset($_SESSION['member_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$member_id = $_SESSION['member_id'];

$query = "
    SELECT 
        m.member_code,
        m.first_name,
        m.last_name,
        m.email,
        m.phone,
        m.status,
        s.plan_name,
        ms.start_date,
        ms.end_date,
        ms.status AS subscription_status,
        t.amount,
        t.payment_method,
        t.payment_status,
        t.payment_date
    FROM tbl_member m
    LEFT JOIN tbl_member_subscriptions ms ON m.member_id = ms.member_id
    LEFT JOIN tbl_subscription s ON ms.subscription_id = s.subscription_id
    LEFT JOIN tbl_transactions t ON m.member_id = t.member_id
    WHERE m.member_id = '$member_id'
    ORDER BY ms.member_subscription_id DESC, t.transaction_id DESC
    LIMIT 1
";

$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard | Aki's Fitness Gym</title>
<link rel="stylesheet" href="/akisgym/assets/css/style.css">

<style>
.user-dashboard-grid{
    display:grid;
    grid-template-columns:320px 1fr;
    gap:20px;
}

.user-card,
.user-subscription-card,
.user-payment-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:24px;
    padding:24px;
    box-shadow:0 6px 18px rgba(15, 23, 42, 0.04);
}

.user-card{
    text-align:center;
}

.user-avatar-large{
    width:90px;
    height:90px;
    margin:0 auto 16px;
    border-radius:50%;
    background:#111827;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    font-weight:800;
}

.user-name{
    font-size:24px;
    font-weight:800;
    margin-bottom:6px;
    color:#0f172a;
}

.user-email{
    color:#64748b;
    font-size:14px;
    margin-bottom:18px;
}

.user-info{
    display:grid;
    gap:12px;
    text-align:left;
}

.user-info-box{
    padding:14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    background:#f8fafc;
}

.user-info-box label{
    display:block;
    font-size:12px;
    color:#64748b;
    margin-bottom:4px;
    font-weight:600;
}

.user-info-box span{
    font-size:14px;
    font-weight:700;
    color:#0f172a;
}

.user-main{
    display:grid;
    gap:20px;
}

.user-subscription-card h3,
.user-payment-card h3{
    margin:0 0 6px;
    font-size:22px;
}

.user-subscription-card p,
.user-payment-card p{
    margin:0 0 20px;
    color:#64748b;
    font-size:14px;
}

.user-sub-grid{
    display:grid;
    grid-template-columns:repeat(2, 1fr);
    gap:14px;
}

.user-sub-box{
    padding:16px;
    border:1px solid #e5e7eb;
    border-radius:16px;
    background:#f8fafc;
}

.user-sub-box label{
    display:block;
    font-size:12px;
    color:#64748b;
    margin-bottom:6px;
    font-weight:600;
}

.user-sub-box span{
    font-size:16px;
    font-weight:800;
    color:#0f172a;
}

.user-status{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    background:#dcfce7;
    color:#166534;
}

.user-payment-status{
    display:inline-block;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    background:#dbeafe;
    color:#1d4ed8;
}

@media (max-width:900px){
    .user-dashboard-grid{
        grid-template-columns:1fr;
    }

    .user-sub-grid{
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
    <a href="user_dashboard.php" class="saas-link active">
        <span class="saas-link-icon">☺</span>
        <span>User Dashboard</span>
    </a>

    <a href="user_subscriptions.php" class="saas-link">
        <span class="saas-link-icon">◉</span>
        <span>Subscription Plans</span>
    </a>
</nav>
    </aside>

    <div class="saas-main">

        <header class="saas-topbar">
            <div class="saas-topbar-left">
                <div>
                    <h2>User Dashboard</h2>
                    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['member_name']); ?></p>
                </div>
            </div>

            <div class="saas-topbar-right">
                <a href="../auth/logout.php" class="saas-logout-btn">Log Out</a>
            </div>
        </header>

        <main class="saas-content">

            <div class="user-dashboard-grid">

                <div class="user-card">
                    <div class="user-avatar-large">
                        <?php echo strtoupper(substr($user['first_name'], 0, 1)); ?>
                    </div>

                    <div class="user-name">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </div>

                    <div class="user-email">
                        <?php echo htmlspecialchars($user['email']); ?>
                    </div>

                    <div class="user-info">
                        <div class="user-info-box">
                            <label>Member Code</label>
                            <span><?php echo htmlspecialchars($user['member_code']); ?></span>
                        </div>

                        <div class="user-info-box">
                            <label>Phone</label>
                            <span><?php echo htmlspecialchars($user['phone']); ?></span>
                        </div>

                        <div class="user-info-box">
                            <label>Account Status</label>
                            <span><?php echo htmlspecialchars($user['status']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="user-main">

                    <div class="user-subscription-card">
                        <h3>Current Subscription</h3>
                        <p>Your latest gym membership plan details</p>

                        <div class="user-sub-grid">
                            <div class="user-sub-box">
                                <label>Plan</label>
                                <span><?php echo htmlspecialchars($user['plan_name'] ?? 'No Plan'); ?></span>
                            </div>

                            <div class="user-sub-box">
                                <label>Status</label>
                                <span class="user-status"><?php echo htmlspecialchars($user['subscription_status'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="user-sub-box">
                                <label>Start Date</label>
                                <span><?php echo htmlspecialchars($user['start_date'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="user-sub-box">
                                <label>End Date</label>
                                <span><?php echo htmlspecialchars($user['end_date'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="user-payment-card">
                        <h3>Latest Payment</h3>
                        <p>Your most recent payment transaction</p>

                        <div class="user-sub-grid">
                            <div class="user-sub-box">
                                <label>Amount Paid</label>
                                <span><?php echo isset($user['amount']) ? '₱' . number_format($user['amount'], 2) : 'N/A'; ?></span>
                            </div>

                            <div class="user-sub-box">
                                <label>Payment Method</label>
                                <span><?php echo htmlspecialchars($user['payment_method'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="user-sub-box">
                                <label>Payment Date</label>
                                <span><?php echo htmlspecialchars($user['payment_date'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="user-sub-box">
                                <label>Payment Status</label>
                                <span class="user-payment-status"><?php echo htmlspecialchars($user['payment_status'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>
</div>

</body>
</html>