<?php
session_start();
require_once("../includes/db.php");

if (!isset($_SESSION['member_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$member_id = (int) $_SESSION['member_id'];
$error = "";

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

/* GET PLAN ID */
if (!isset($_GET['subscription_id']) && !isset($_POST['subscription_id'])) {
    header("Location: user_subscriptions.php");
    exit();
}

$subscription_id = isset($_GET['subscription_id'])
    ? (int) $_GET['subscription_id']
    : (int) $_POST['subscription_id'];

/* LOAD PLAN */
$plan_query = mysqli_query($conn, "
    SELECT *
    FROM tbl_subscription
    WHERE subscription_id = '$subscription_id'
    AND status = 'active'
    LIMIT 1
");

if (!$plan_query || mysqli_num_rows($plan_query) == 0) {
    die("Invalid subscription plan.");
}

$plan = mysqli_fetch_assoc($plan_query);

/* HANDLE FORM SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {

    $payment_method = trim($_POST['payment_method'] ?? '');

    $allowed_methods = ['cash', 'gcash', 'bank_transfer', 'card'];

    if (empty($payment_method)) {
        $error = "Please select a payment method.";
    } elseif (!in_array($payment_method, $allowed_methods)) {
        $error = "Invalid payment method selected.";
    } else {

        /* CHECK EXISTING ACTIVE OR PENDING SUBSCRIPTION */
        $active_check = mysqli_query($conn, "
            SELECT member_subscription_id
            FROM tbl_member_subscriptions
            WHERE member_id = '$member_id'
            AND status IN ('active', 'pending')
            LIMIT 1
        ");

        if (mysqli_num_rows($active_check) > 0) {
            $error = "You already have an active or pending subscription.";
        } else {

            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d", strtotime("+" . (int)$plan['duration_days'] . " days"));

            $payment_status = ($payment_method === 'cash') ? 'paid' : 'pending';
            $subscription_status = ($payment_method === 'cash') ? 'active' : 'pending';

            $transaction_code = 'TXN-' . date('YmdHis') . '-' . rand(1000, 9999);

            mysqli_begin_transaction($conn);

            try {
                /* INSERT MEMBER SUBSCRIPTION */
                $insert_subscription = mysqli_query($conn, "
                    INSERT INTO tbl_member_subscriptions
                    (member_id, subscription_id, start_date, end_date, status)
                    VALUES
                    ('$member_id', '{$plan['subscription_id']}', '$start_date', '$end_date', '$subscription_status')
                ");

                if (!$insert_subscription) {
                    throw new Exception("Failed to save subscription: " . mysqli_error($conn));
                }

                $member_subscription_id = mysqli_insert_id($conn);

                if (!$member_subscription_id) {
                    throw new Exception("Failed to get member subscription ID.");
                }

                /* INSERT TRANSACTION */
                $insert_transaction = mysqli_query($conn, "
                    INSERT INTO tbl_transactions
                    (
                        transaction_code,
                        member_subscription_id,
                        member_id,
                        subscription_id,
                        amount,
                        payment_method,
                        payment_status,
                        payment_date
                    )
                    VALUES
                    (
                        '$transaction_code',
                        '$member_subscription_id',
                        '$member_id',
                        '{$plan['subscription_id']}',
                        '{$plan['price']}',
                        '$payment_method',
                        '$payment_status',
                        NOW()
                    )
                ");

                if (!$insert_transaction) {
                    throw new Exception("Failed to save transaction: " . mysqli_error($conn));
                }

                mysqli_commit($conn);

                header("Location: user_dashboard.php?payment=success");
                exit();

            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment | Aki's Fitness Gym</title>
<link rel="stylesheet" href="/akisgym/assets/css/style.css">

<style>
.payment-wrapper{
    max-width:900px;
    margin:0 auto;
}

.payment-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.payment-card,
.summary-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:24px;
    padding:24px;
    box-shadow:0 6px 18px rgba(15, 23, 42, 0.04);
}

.card-title{
    font-size:24px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:8px;
}

.card-subtitle{
    color:#64748b;
    font-size:14px;
    margin-bottom:20px;
}

.summary-box{
    display:grid;
    gap:14px;
}

.summary-item{
    padding:14px 16px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    background:#f8fafc;
}

.summary-item label{
    display:block;
    font-size:12px;
    color:#64748b;
    margin-bottom:4px;
    font-weight:600;
}

.summary-item span{
    font-size:16px;
    font-weight:800;
    color:#0f172a;
}

.payment-methods{
    display:grid;
    gap:14px;
    margin-top:14px;
}

.method-option{
    border:1px solid #e5e7eb;
    border-radius:16px;
    padding:14px 16px;
    background:#f8fafc;
    display:flex;
    align-items:center;
    gap:12px;
}

.method-option input{
    transform:scale(1.1);
}

.method-option label{
    font-size:15px;
    font-weight:700;
    color:#0f172a;
    cursor:pointer;
    width:100%;
}

.info-note{
    margin-top:16px;
    padding:14px 16px;
    border-radius:14px;
    background:#eff6ff;
    border:1px solid #bfdbfe;
    color:#1d4ed8;
    font-size:14px;
    font-weight:600;
}

.error-box{
    background:#fee2e2;
    color:#991b1b;
    border:1px solid #fecaca;
    padding:14px 16px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
    font-weight:600;
}

.payment-actions{
    display:flex;
    gap:12px;
    margin-top:24px;
    flex-wrap:wrap;
}

.pay-btn,
.back-btn{
    text-decoration:none;
    border:none;
    border-radius:14px;
    height:48px;
    padding:0 20px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

.pay-btn{
    background:linear-gradient(135deg, #4f46e5, #4338ca);
    color:#fff;
}

.back-btn{
    background:#e5e7eb;
    color:#111827;
}

.pay-btn:hover,
.back-btn:hover{
    opacity:.95;
}

@media (max-width:900px){
    .payment-grid{
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
                    <h2>Payment Page</h2>
                    <p>Choose your payment method to continue your subscription</p>
                </div>
            </div>

            <div class="saas-topbar-right">
                <a href="../auth/logout.php" class="saas-logout-btn">Log Out</a>
            </div>
        </header>

        <main class="saas-content">
            <div class="payment-wrapper">

                <?php if (!empty($error)): ?>
                    <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="payment-grid">

                    <div class="summary-card">
                        <div class="card-title">Selected Plan</div>
                        <div class="card-subtitle">Review your chosen subscription details</div>

                        <div class="summary-box">
                            <div class="summary-item">
                                <label>Plan Name</label>
                                <span><?php echo htmlspecialchars($plan['plan_name']); ?></span>
                            </div>

                            <div class="summary-item">
                                <label>Description</label>
                                <span><?php echo htmlspecialchars($plan['description']); ?></span>
                            </div>

                            <div class="summary-item">
                                <label>Duration</label>
                                <span><?php echo htmlspecialchars($plan['duration_days']); ?> days</span>
                            </div>

                            <div class="summary-item">
                                <label>Access Level</label>
                                <span><?php echo htmlspecialchars($plan['access_level']); ?></span>
                            </div>

                            <div class="summary-item">
                                <label>Amount to Pay</label>
                                <span>₱<?php echo number_format($plan['price'], 2); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-card">
                        <div class="card-title">Choose Payment Method</div>
                        <div class="card-subtitle">Select one method to complete your subscription</div>

                        <form method="POST">
                            <input type="hidden" name="subscription_id" value="<?php echo $plan['subscription_id']; ?>">

                            <div class="payment-methods">
                                <div class="method-option">
                                    <input type="radio" id="cash" name="payment_method" value="cash" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'cash') ? 'checked' : ''; ?>>
                                    <label for="cash">Cash</label>
                                </div>

                                <div class="method-option">
                                    <input type="radio" id="gcash" name="payment_method" value="gcash" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'gcash') ? 'checked' : ''; ?>>
                                    <label for="gcash">GCash</label>
                                </div>

                                <div class="method-option">
                                    <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'bank_transfer') ? 'checked' : ''; ?>>
                                    <label for="bank_transfer">Bank Transfer</label>
                                </div>

                                <div class="method-option">
                                    <input type="radio" id="card" name="payment_method" value="card" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'card') ? 'checked' : ''; ?>>
                                    <label for="card">Card</label>
                                </div>
                            </div>

                            <div class="info-note">
                                Cash payments are marked as paid immediately. Other methods remain pending until verified.
                            </div>

                            <div class="payment-actions">
                                <a href="user_subscriptions.php" class="back-btn">Back</a>
                                <button type="submit" name="confirm_payment" class="pay-btn">Confirm Payment</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>