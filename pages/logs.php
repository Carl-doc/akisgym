<?php
require_once("../includes/db.php");

$logs = mysqli_query($conn,"SELECT * FROM tbl_logs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Activity Logs</title>
<link rel="stylesheet" href="/akisgym/assets/css/style.css">

<style>

.logs-search{
margin-bottom:20px;
max-width:350px;
}

.logs-search input{
width:100%;
padding:12px;
border:1px solid #e5e7eb;
border-radius:12px;
}

.logs-table{
background:#fff;
border-radius:20px;
padding:20px;
border:1px solid #e5e7eb;
}

.logs-table table{
width:100%;
border-collapse:collapse;
}

.logs-table th{
text-align:left;
padding:14px;
font-size:14px;
color:#64748b;
}

.logs-table td{
padding:14px;
border-top:1px solid #f1f5f9;
}

.logs-table tr:hover{
background:#f8fafc;
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

<a href="logs.php" class="saas-link active">
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
<h2>Activity Logs</h2>
<p>Track system actions</p>
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

<div class="logs-search">
<input type="text" id="logSearch" placeholder="Search logs...">
</div>

<div class="logs-table">

<table id="logsTable">

<thead>
<tr>
<th>ID</th>
<th>Admin ID</th>
<th>Action</th>
<th>Description</th>
<th>Date</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($logs)): ?>

<tr>

<td><?php echo $row['log_id']; ?></td>
<td><?php echo $row['admin_id']; ?></td>
<td><?php echo $row['action']; ?></td>
<td><?php echo $row['description']; ?></td>
<td><?php echo $row['created_at']; ?></td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</main>

</div>

</div>

<script>

const search = document.getElementById("logSearch");

search.addEventListener("keyup", function(){

const value = this.value.toLowerCase();

const rows = document.querySelectorAll("#logsTable tbody tr");

rows.forEach(row=>{

const text = row.textContent.toLowerCase();

row.style.display = text.includes(value) ? "" : "none";

});

});

</script>

</body>
</html>