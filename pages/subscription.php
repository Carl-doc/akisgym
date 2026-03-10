<?php
require_once("../includes/db.php");


/* 1. Future subscriptions = pending */
mysqli_query($conn, "
    UPDATE tbl_member_subscriptions
    SET status = 'pending'
    WHERE start_date > CURDATE()
    AND status != 'cancelled'
");

/* 2. Current subscriptions = active */
mysqli_query($conn, "
    UPDATE tbl_member_subscriptions
    SET status = 'active'
    WHERE start_date <= CURDATE()
    AND end_date >= CURDATE()
    AND status != 'cancelled'
");

/* 3. Past subscriptions = expired */
mysqli_query($conn, "
    UPDATE tbl_member_subscriptions
    SET status = 'expired'
    WHERE end_date < CURDATE()
    AND status != 'cancelled'
");

/* ADD SUBSCRIPTION */
if(isset($_POST['add_subscription'])){

    $member_id = mysqli_real_escape_string($conn, $_POST['member_id']);
    $plan_id = mysqli_real_escape_string($conn, $_POST['plan_id']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn,"
        INSERT INTO tbl_member_subscriptions
        (member_id, subscription_id, start_date, end_date, status)
        VALUES
        ('$member_id','$plan_id','$start_date','$end_date','$status')
    ");

    header("Location: subscription.php");
    exit();
}

/* LOAD MEMBERS */
$members = mysqli_query($conn,"SELECT member_id, first_name, last_name FROM tbl_member ORDER BY first_name ASC");

/* LOAD PLANS */
$plans = mysqli_query($conn,"SELECT subscription_id, plan_name FROM tbl_subscription ORDER BY plan_name ASC");

/* LOAD SUBSCRIPTIONS */
$query = "
SELECT 
    ms.member_subscription_id,
    m.first_name,
    m.last_name,
    s.plan_name,
    ms.start_date,
    ms.end_date,
    ms.status
FROM tbl_member_subscriptions ms
INNER JOIN tbl_member m ON ms.member_id = m.member_id
INNER JOIN tbl_subscription s ON ms.subscription_id = s.subscription_id
ORDER BY ms.member_subscription_id DESC
";

$subscriptions = mysqli_query($conn,$query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscription | Aki's Fitness Gym</title>
<link rel="stylesheet" href="/akisgym/assets/css/style.css">

<style>
.subscription-toolbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

.subscription-search{
    width:100%;
    max-width:360px;
}

.subscription-search input{
    width:100%;
    padding:12px 14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    outline:none;
    font-size:14px;
    background:#fff;
}

.add-subscription-btn{
    border:none;
    background:#4f46e5;
    color:#fff;
    padding:12px 18px;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}

.add-subscription-btn:hover{
    background:#4338ca;
}

.subscription-table{
    background:#ffffff;
    border-radius:20px;
    padding:20px;
    border:1px solid #e5e7eb;
    box-shadow:0 6px 18px rgba(15, 23, 42, 0.04);
}

.subscription-table table{
    width:100%;
    border-collapse:collapse;
}

.subscription-table th{
    text-align:left;
    padding:14px;
    font-size:14px;
    color:#64748b;
}

.subscription-table td{
    padding:14px;
    border-top:1px solid #f1f5f9;
}

.subscription-table tr:hover{
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

.status-badge{
    display:inline-block;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    text-transform:capitalize;
}

.status-active{
    background:#dcfce7;
    color:#166534;
}

.status-expired{
    background:#fee2e2;
    color:#991b1b;
}

.status-pending{
    background:#fef3c7;
    color:#92400e;
}

.status-cancelled{
    background:#e5e7eb;
    color:#374151;
}

/* Modal */
.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(15, 23, 42, 0.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:1000;
    padding:20px;
}

.modal-overlay.show{
    display:flex;
}

.modal-card{
    width:100%;
    max-width:520px;
    background:#fff;
    border-radius:20px;
    padding:24px;
    box-shadow:0 20px 50px rgba(0,0,0,0.18);
}

.modal-card h3{
    margin:0 0 18px;
    font-size:22px;
}

.modal-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.modal-field{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.modal-field.full{
    grid-column:1 / -1;
}

.modal-field label{
    font-size:14px;
    font-weight:600;
    color:#334155;
}

.modal-field input,
.modal-field select{
    padding:12px 14px;
    border:1px solid #e5e7eb;
    border-radius:12px;
    outline:none;
    background:#fff;
}

.modal-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:20px;
}

.modal-cancel,
.modal-save{
    border:none;
    padding:12px 18px;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}

.modal-cancel{
    background:#e2e8f0;
    color:#0f172a;
}

.modal-save{
    background:#4f46e5;
    color:#fff;
}

@media (max-width:640px){
    .modal-grid{
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

            <a href="subscription.php" class="saas-link active">
                <span class="saas-link-icon">◉</span>
                <span>Subscription</span>
            </a>

            <a href="transaction.php" class="saas-link">
                <span class="saas-link-icon">▣</span>
                <span>Transactions</span>
            </a>
        </nav>
    </aside>

    <div class="saas-main">

        <header class="saas-topbar">
            <div class="saas-topbar-left">
                <div>
                    <h2>Subscription</h2>
                    <p>Manage member plans and subscription periods</p>
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
                <h2>Subscription List</h2>
            </div>
            <div class="page-subtitle">View active, expired, and pending subscriptions.</div>

            <div class="subscription-toolbar">
                <div class="subscription-search">
                    <input type="text" id="subscriptionSearch" placeholder="Search by member or plan">
                </div>

                <button class="add-subscription-btn" id="openSubscriptionModal">+ Add Subscription</button>
            </div>

            <div class="subscription-table">
                <table id="subscriptionTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Member</th>
                            <th>Plan</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($subscriptions)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['member_subscription_id']); ?></td>
                            <td><?php echo htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name'])); ?></td>
                            <td><?php echo htmlspecialchars($row['plan_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                            <td>
                                <?php
                                    $status = strtolower($row['status']);
                                    $class = "status-badge ";
                                    if ($status === "active") $class .= "status-active";
                                    elseif ($status === "expired") $class .= "status-expired";
                                    elseif ($status === "pending") $class .= "status-pending";
                                    else $class .= "status-cancelled";
                                ?>
                                <span class="<?php echo $class; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="subscriptionModal">
    <div class="modal-card">
        <h3>Add Subscription</h3>

        <form method="POST">
            <div class="modal-grid">

                <div class="modal-field full">
                    <label>Member</label>
                    <select name="member_id" required>
                        <option value="" disabled selected>Select member</option>
                        <?php while($m = mysqli_fetch_assoc($members)): ?>
                            <option value="<?php echo $m['member_id']; ?>">
                                <?php echo htmlspecialchars($m['first_name'] . " " . $m['last_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="modal-field full">
                    <label>Plan</label>
                    <select name="plan_id" required>
                        <option value="" disabled selected>Select plan</option>
                        <?php while($p = mysqli_fetch_assoc($plans)): ?>
                            <option value="<?php echo $p['subscription_id']; ?>">
                                <?php echo htmlspecialchars($p['plan_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="modal-field">
                    <label>Start Date</label>
                    <input type="date" name="start_date" required>
                </div>

                <div class="modal-field">
                    <label>End Date</label>
                    <input type="date" name="end_date" required>
                </div>

                <div class="modal-field full">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="expired">Expired</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

            </div>

            <div class="modal-actions">
                <button type="button" class="modal-cancel" id="closeSubscriptionModal">Cancel</button>
                <button type="submit" name="add_subscription" class="modal-save">Save Subscription</button>
            </div>
        </form>
    </div>
</div>

<script>
const subscriptionSearch = document.getElementById("subscriptionSearch");
const openSubBtn = document.getElementById("openSubscriptionModal");
const subModal = document.getElementById("subscriptionModal");
const closeSubBtn = document.getElementById("closeSubscriptionModal");

subscriptionSearch.addEventListener("keyup", function(){
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll("#subscriptionTable tbody tr");

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});

openSubBtn.addEventListener("click", () => {
    subModal.classList.add("show");
});

closeSubBtn.addEventListener("click", () => {
    subModal.classList.remove("show");
});

subModal.addEventListener("click", (e) => {
    if (e.target === subModal) {
        subModal.classList.remove("show");
    }
});
</script>

</body>
</html>