<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Aki's Fitness Gym</title>
    <link rel="stylesheet" href="/akisgym/assets/css/style.css">
</head>
<body class="saas-body">

<div class="saas-layout">

    <aside class="saas-sidebar" id="sidebar">
        <div class="saas-brand">
            <div class="saas-brand-logo"></div>
            <div class="saas-brand-text">Aki's Fitness Gym</div>
        </div>

        <nav class="saas-menu">
            <a href="dashboard.php" class="saas-link active">
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

            <a href="transaction.php" class="saas-link">
                <span class="saas-link-icon">▣</span>
                <span>Transactions</span>
            </a>
        </nav>
    </aside>

    <div class="saas-main">

        <header class="saas-topbar">
            <div class="saas-topbar-left">
                <button class="saas-menu-btn" id="menuBtn" type="button">☰</button>
                <div>
                    <h2>Dashboard</h2>
                    <p>Welcome back, Admin</p>
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

            <section class="saas-stats">
                <div class="saas-stat-card">
                    <div>
                        <p>Active Members</p>
                        <h3>35</h3>
                    </div>
                    <span>👥</span>
                </div>

                <div class="saas-stat-card">
                    <div>
                        <p>Monthly Revenue</p>
                        <h3>₱7,500</h3>
                    </div>
                    <span>💵</span>
                </div>

                <div class="saas-stat-card">
                    <div>
                        <p>Expired Members</p>
                        <h3>10</h3>
                    </div>
                    <span>⌛</span>
                </div>

                <div class="saas-stat-card">
                    <div>
                        <p>Total Transactions</p>
                        <h3>45</h3>
                    </div>
                    <span>📄</span>
                </div>
            </section>

            <section class="saas-chart-card">
                <div class="saas-card-header">
                    <div>
                        <h3>Monthly Revenue</h3>
                        <p>Revenue performance across the year</p>
                    </div>
                </div>

                <div class="saas-chart">
                    <div class="saas-bars">
                        <div class="saas-bar-item"><div class="saas-bar" style="height:120px;"></div><span>Jan</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:150px;"></div><span>Feb</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:100px;"></div><span>Mar</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:165px;"></div><span>Apr</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:150px;"></div><span>May</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:50px;"></div><span>Jun</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:195px;"></div><span>Jul</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:128px;"></div><span>Aug</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:82px;"></div><span>Sep</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:100px;"></div><span>Oct</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:140px;"></div><span>Nov</span></div>
                        <div class="saas-bar-item"><div class="saas-bar" style="height:75px;"></div><span>Dec</span></div>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<script>
const menuBtn = document.getElementById("menuBtn");
const sidebar = document.getElementById("sidebar");

if (menuBtn && sidebar) {
    menuBtn.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");
    });
}
</script>

</body>
</html>