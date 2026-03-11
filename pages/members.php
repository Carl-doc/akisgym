<?php
require_once("../includes/db.php");

if (isset($_POST['save_member'])) {
    $member_id = trim($_POST['member_id']);
    $full_name = trim($_POST['member_name']);
    $contact = trim($_POST['member_contact']);
    $email = trim($_POST['member_email']);

    $name_parts = preg_split('/\s+/', $full_name, 2);
    $first_name = $name_parts[0] ?? '';
    $last_name = $name_parts[1] ?? '';

    $member_code = "MBR-" . str_pad($member_id, 3, "0", STR_PAD_LEFT);

    $check = mysqli_query($conn, "SELECT member_id FROM tbl_member WHERE member_code='$member_code'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "
            UPDATE tbl_member 
            SET first_name='$first_name', last_name='$last_name', phone='$contact', email='$email'
            WHERE member_code='$member_code'
        ");
    } else {
        mysqli_query($conn, "
            INSERT INTO tbl_member (member_code, first_name, last_name, gender, email, phone, status, joined_date)
            VALUES ('$member_code', '$first_name', '$last_name', 'other', '$email', '$contact', 'active', CURDATE())
        ");
    }

    header("Location: members.php");
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM tbl_member WHERE member_id='$delete_id'");
    header("Location: members.php");
    exit();
}

$members = mysqli_query($conn, "SELECT * FROM tbl_member ORDER BY member_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Members | Aki's Fitness Gym</title>
<link rel="stylesheet" href="/akisgym/assets/css/style.css">

<style>
.members-toolbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    margin-bottom:20px;
    flex-wrap:wrap;
}
.members-search{
    width:100%;
    max-width:360px;
}
.members-search input{
    width:100%;
    padding:12px 14px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    outline:none;
    font-size:14px;
    background:#fff;
}
.add-member-btn{
    border:none;
    background:#4f46e5;
    color:#fff;
    padding:12px 18px;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}
.add-member-btn:hover{
    background:#4338ca;
}
.members-table{
    background:#ffffff;
    border-radius:20px;
    padding:20px;
    border:1px solid #e5e7eb;
    box-shadow:0 6px 18px rgba(15, 23, 42, 0.04);
}
.members-table table{
    width:100%;
    border-collapse:collapse;
}
.members-table th{
    text-align:left;
    padding:14px;
    font-size:14px;
    color:#64748b;
}
.members-table td{
    padding:14px;
    border-top:1px solid #f1f5f9;
}
.members-table tr:hover{
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
.action-buttons{
    display:flex;
    gap:8px;
}
.edit-btn{
    background:#22c55e;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
}
.delete-btn{
    background:#ef4444;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    text-decoration:none;
}
.edit-btn:hover{
    background:#16a34a;
}
.delete-btn:hover{
    background:#dc2626;
}
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
    max-width:500px;
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
.modal-field input{
    padding:12px 14px;
    border:1px solid #e5e7eb;
    border-radius:12px;
    outline:none;
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
            <div class="saas-brand-logo">
    <img src="../assets/logo/logo.png" alt="Gym Logo">
</div>
            <div class="saas-brand-text">Aki's Fitness Gym</div>
        </div>

        <nav class="saas-menu">
            <a href="dashboard.php" class="saas-link">
                <span class="saas-link-icon">▦</span>
                <span>Dashboard</span>
            </a>

            <a href="members.php" class="saas-link active">
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
                    <h2>Members</h2>
                    <p>Manage gym members</p>
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
                <h2>Members List</h2>
            </div>
            <div class="page-subtitle">Search, browse, and add registered members.</div>

            <div class="members-toolbar">
                <div class="members-search">
                    <input type="text" id="memberSearch" placeholder="Search by name, contact, or email">
                </div>
<div style="text-align: right;">
    <button class="add-member-btn" id="openModalBtn" style="padding: 4px 10px; font-size: 13px;">+ Add Member</button>
</div>

            <div class="members-table" style="width: 100%;">
    <table id="membersTable" style="width: 100%;">
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($members)): ?>
            <tr
                data-db-id="<?php echo $row['member_id']; ?>"
                data-code="<?php echo htmlspecialchars(str_replace('MBR-', '', $row['member_code'])); ?>"
                data-name="<?php echo htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name'])); ?>"
                data-contact="<?php echo htmlspecialchars($row['phone']); ?>"
                data-email="<?php echo htmlspecialchars($row['email']); ?>"
            >
                <td><?php echo htmlspecialchars(str_replace('MBR-', '', $row['member_code'])); ?></td>
                <td><?php echo htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name'])); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="action-buttons">
                    <button class="edit-btn" type="button">Edit</button>
                    <a class="delete-btn" href="members.php?delete=<?php echo $row['member_id']; ?>" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="memberModal">
    <div class="modal-card">
        <h3 id="modalTitle">Add Member</h3>

        <form method="POST">
            <div class="modal-grid">
                <div class="modal-field">
                    <label for="memberId">Member ID</label>
                    <input type="text" id="memberId" name="member_id" placeholder="005" required>
                </div>

                <div class="modal-field">
                    <label for="memberContact">Contact</label>
                    <input type="text" id="memberContact" name="member_contact" placeholder="09123456789" required>
                </div>

                <div class="modal-field full">
                    <label for="memberName">Full Name</label>
                    <input type="text" id="memberName" name="member_name" placeholder="Juan Dela Cruz" required>
                </div>

                <div class="modal-field full">
                    <label for="memberEmail">Email</label>
                    <input type="email" id="memberEmail" name="member_email" placeholder="juan@gmail.com" required>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-cancel" id="closeModalBtn">Cancel</button>
                <button type="submit" class="modal-save" name="save_member">Save Member</button>
            </div>
        </form>
    </div>
</div>

<script>
const memberSearch = document.getElementById("memberSearch");
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");
const memberModal = document.getElementById("memberModal");
const modalTitle = document.getElementById("modalTitle");

function filterRows() {
    const value = memberSearch.value.toLowerCase();
    const rows = document.querySelectorAll("#membersTable tbody tr");

    rows.forEach((row) => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(value) ? "" : "none";
    });
}

memberSearch.addEventListener("keyup", filterRows);

openModalBtn.addEventListener("click", () => {
    modalTitle.textContent = "Add Member";
    document.getElementById("memberId").value = "";
    document.getElementById("memberName").value = "";
    document.getElementById("memberContact").value = "";
    document.getElementById("memberEmail").value = "";
    memberModal.classList.add("show");
});

closeModalBtn.addEventListener("click", () => {
    memberModal.classList.remove("show");
});

memberModal.addEventListener("click", (e) => {
    if (e.target === memberModal) {
        memberModal.classList.remove("show");
    }
});

document.addEventListener("click", function(e){
    if(e.target.classList.contains("edit-btn")){
        const row = e.target.closest("tr");

        document.getElementById("memberId").value = row.dataset.code;
        document.getElementById("memberName").value = row.dataset.name;
        document.getElementById("memberContact").value = row.dataset.contact;
        document.getElementById("memberEmail").value = row.dataset.email;

        modalTitle.textContent = "Edit Member";
        memberModal.classList.add("show");
    }
});
</script>

</body>
</html>