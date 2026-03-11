<?php
require_once("../includes/db.php");

$query = "
    SELECT 
        t.transaction_id,
        t.transaction_code,
        t.amount,
        t.payment_method,
        t.payment_status,
        t.payment_date,
        m.first_name,
        m.last_name,
        s.plan_name
    FROM tbl_transactions t
    INNER JOIN tbl_member m ON t.member_id = m.member_id
    INNER JOIN tbl_subscription s ON t.subscription_id = s.subscription_id
    ORDER BY t.transaction_id DESC
";

$transactions = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transactions | Aki's Fitness Gym</title>
<link rel="stylesheet" href="/akisgym/assets/css/style.css">

<style>
.transaction-toolbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

.transaction-search{
    width:100%;
    max-width:360px;
}

.transaction-search input{
    width:100%;
    padding:12px 14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    outline:none;
    font-size:14px;
    background:#fff;
}

.transaction-table{
    background:#ffffff;
    border-radius:20px;
    padding:20px;
    border:1px solid #e5e7eb;
    box-shadow:0 6px 18px rgba(15, 23, 42, 0.04);
    overflow:auto;
}

.transaction-table table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

.transaction-table th{
    text-align:left;
    padding:14px;
    font-size:14px;
    color:#64748b;
}

.transaction-table td{
    padding:14px;
    border-top:1px solid #f1f5f9;
}

.transaction-table tr:hover{
    background:#f8fafc;
}

.page-title{
    margin-bottom:8px;
}

.page-title h2{
    font-size:24px;
    margin:0;
}

.page-subtitle{
    color:#64748b;
    font-size:14px;
    margin-bottom:20px;
}

.method-badge,
.payment-badge{
    display:inline-block;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    text-transform:capitalize;
}

.method-badge{
    background:#ede9fe;
    color:#5b21b6;
}

.payment-paid{
    background:#dcfce7;
    color:#166534;
}

.payment-pending{
    background:#fef3c7;
    color:#92400e;
}

.payment-failed{
    background:#fee2e2;
    color:#991b1b;
}

.payment-refunded{
    background:#e5e7eb;
    color:#374151;
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
            <a href="dashboard.php" class="saas-link">
                <span class="saas-link-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a href="members.php" class="saas-link">
                <span class="saas-link-icon">👤</span>
                <span>Members</span>
            </a>

            <a href="logs.php" class="saas-link">
                <span class="saas-link-icon">◔</span>
                <span>Activity Logs</span>
            </a>

            <a href="subscription.php" class="saas-link">
                <span class="saas-link-icon">◉</span>
                <span>Subscription</span>
            </a>

            <a href="transaction.php" class="saas-link active">
                <span class="saas-link-icon">▣</span>
                <span>Transactions</span>
            </a>
        </nav>
    </aside>

    <div class="saas-main">

        <header class="saas-topbar">
            <div class="saas-topbar-left">
                <div>
                    <h2>Transactions</h2>
                    <p>Track subscription payments and payment history</p>
                </div>
            </div>

            <div class="saas-topbar-right">
                <div class="saas-user">
                    <div class="saas-user-avatar">A</div>
                    <div>
                        <strong>Admin</strong>
                        <small>Administrator</small>
                    </div>
                </div>

                <a href="../auth/logout.php" class="saas-logout-btn">Log Out</a>
            </div>
        </header>

        <main class="saas-content">

            <div class="page-title">
                <h2>Transaction List</h2>
            </div>
            <div class="page-subtitle">View all recorded payments and transaction statuses.</div>

            <div class="transaction-toolbar">
                <div class="transaction-search">
                    <input type="text" id="transactionSearch" placeholder="Search by member, plan, method, or code">
                </div>
            </div>

            <div class="transaction-table">
                <table id="transactionTable">
                    <thead>
                        <tr>
                            <th>Transaction Code</th>
                            <th>Member</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($transactions)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['transaction_code']); ?></td>
                            <td><?php echo htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name'])); ?></td>
                            <td><?php echo htmlspecialchars($row['plan_name']); ?></td>
                            <td>₱<?php echo number_format($row['amount'], 2); ?></td>
                            <td>
                                <span class="method-badge">
                                    <?php echo htmlspecialchars($row['payment_method']); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                    $status = strtolower($row['payment_status']);
                                    $class = "payment-badge ";
                                    if ($status === "paid") $class .= "payment-paid";
                                    elseif ($status === "pending") $class .= "payment-pending";
                                    elseif ($status === "failed") $class .= "payment-failed";
                                    else $class .= "payment-refunded";
                                ?>
                                <span class="<?php echo $class; ?>">
                                    <?php echo htmlspecialchars($row['payment_status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<script>
const transactionSearch = document.getElementById("transactionSearch");

transactionSearch.addEventListener("keyup", function(){
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll("#transactionTable tbody tr");

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});
</script>

</body>
</html>