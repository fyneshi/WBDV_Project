<?php
session_start();

// Check if user is authenticated and has admin role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

require_once 'db_setup.php';

$adminUsername = $_SESSION['username'] ?? 'Admin User';
$adminEmail    = $_SESSION['email'] ?? 'admin@stationfinds.com';
$adminInitials = 'AD';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Station Finds — Admin Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="CSS/admin.css" />
</head>
<body>

  <div class="admin-container">

    <!-- ==================== LEFT SIDEBAR ==================== -->
    <aside class="admin-sidebar">
      <!-- Logo Header -->
      <div class="sidebar-logo-area">
        <a href="admin_dashboard.php" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <img src="logo2.png" alt="Station Finds" class="sidebar-logo-img" onerror="this.style.display='none'" />
          <span class="sidebar-logo-text">LOGO</span>
        </a>
      </div>

      <!-- Admin Profile Card -->
      <div class="sidebar-profile-card">
        <div class="profile-avatar"><?php echo htmlspecialchars($adminInitials); ?></div>
        <div class="profile-info">
          <span class="profile-name"><?php echo htmlspecialchars($adminUsername); ?></span>
          <span class="profile-role">Super Admin</span>
        </div>
      </div>

      <!-- Sidebar Navigation Menu -->
      <nav class="sidebar-nav">
        <button class="nav-item active" onclick="switchNavSection('overview', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7"></rect>
            <rect x="14" y="3" width="7" height="7"></rect>
            <rect x="14" y="14" width="7" height="7"></rect>
            <rect x="3" y="14" width="7" height="7"></rect>
          </svg>
          <span>Dashboard Overview</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('users', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          <span>User Management</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('listings', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="8" y1="6" x2="21" y2="6"></line>
            <line x1="8" y1="12" x2="21" y2="12"></line>
            <line x1="8" y1="18" x2="21" y2="18"></line>
            <line x1="3" y1="6" x2="3.01" y2="6"></line>
            <line x1="3" y1="12" x2="3.01" y2="12"></line>
            <line x1="3" y1="18" x2="3.01" y2="18"></line>
          </svg>
          <span>Listing Management</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('seller-apps', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="8" r="7"></circle>
            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
          </svg>
          <span>Seller Applications</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('moderation', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
          <span>Content Moderation</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('feedback', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg>
          <span>Feedback</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('transactions', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
            <line x1="1" y1="10" x2="23" y2="10"></line>
          </svg>
          <span>Transaction Management</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('payouts', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Payout Oversight</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('categories', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
          </svg>
          <span>Category Management</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('reports', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg>
          <span>Reports</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('settings', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
          <span>Settings</span>
        </button>
      </nav>

      <!-- Sidebar Footer -->
      <div class="sidebar-divider"></div>
      <div class="sidebar-footer"></div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="admin-main">

      <!-- TOP DASHBOARD HEADER -->
      <header class="dashboard-header">
        <div class="header-title-group">
          <h1>Dashboard Overview</h1>
          <p>A snapshot of key marketplace metrics.</p>
        </div>

        <div class="header-actions">
          <!-- Admin Avatar Pill Button -->
          <button class="header-avatar-btn" title="Admin Account" onclick="window.location.href='home.php'">
            <?php echo htmlspecialchars($adminInitials); ?>
          </button>
        </div>
      </header>

      <!-- 3 METRIC STAT CARDS -->
      <section class="stats-grid">
        <!-- Card 1: Total Users -->
        <div class="stat-card">
          <div class="stat-card-top">
            <span class="stat-label">Total users</span>
            <div class="stat-icon-wrapper">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="statTotalUsers">2,481</div>
        </div>

        <!-- Card 2: Total Listings -->
        <div class="stat-card">
          <div class="stat-card-top">
            <span class="stat-label">Total listings</span>
            <div class="stat-icon-wrapper">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="statTotalListings">1,204</div>
        </div>

        <!-- Card 3: Revenue This Month -->
        <div class="stat-card">
          <div class="stat-card-top">
            <span class="stat-label">Revenue this month</span>
            <div class="stat-icon-wrapper">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="statRevenue">₱248,900</div>
        </div>

      </section>

    </main>
  </div>

  <!-- Toast Element -->
  <div class="admin-toast" id="adminToast"></div>

  <!-- ==================== JAVASCRIPT ==================== -->
  <script>
    // ── Toast notification ─────────────────────────────────────────
    function showToast(message, type = 'success') {
      const toast = document.getElementById('adminToast');
      toast.textContent = message;
      toast.className = 'admin-toast ' + type;
      toast.style.display = 'block';
      setTimeout(() => {
        toast.style.display = 'none';
      }, 4000);
    }

    // ── Load Dashboard Data from backend ────────────────────────────
    async function loadDashboardData() {
      try {
        const res = await fetch('auth_handler.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'admin_get_dashboard_data' })
        });
        const data = await res.json();

        if (data.success) {
          document.getElementById('statTotalUsers').textContent = data.stats.total_users || '2,481';
          document.getElementById('statTotalListings').textContent = data.stats.total_listings || '1,204';
          document.getElementById('statRevenue').textContent = data.stats.revenue_month || '₱248,900';

        }
      } catch (err) {
        console.error('Error fetching dashboard data:', err);
      }
    }

    // ── Sidebar Navigation switching ──────────────────────────────
    function switchNavSection(secName, btn) {
      document.querySelectorAll('.sidebar-nav .nav-item').forEach(b => b.classList.remove('active'));
      if (btn) btn.classList.add('active');

      if (secName === 'overview') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else if (btn) {
        showToast('Viewing ' + btn.querySelector('span').textContent, 'success');
      }
    }

    // Initial load + periodic poll every 20 seconds
    loadDashboardData();
    setInterval(loadDashboardData, 20000);
  </script>
</body>
</html>
