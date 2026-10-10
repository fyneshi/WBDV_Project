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
    <aside class="admin-sidebar" id="adminSidebar">
      <!-- Logo Header -->
      <div class="sidebar-logo-area">
        <a href="admin_dashboard.php" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <span class="brand-mark">S</span>
          <span class="sidebar-logo-text">STATION FINDS</span>
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
      <p class="menu-label">Main menu</p>
      <nav class="sidebar-nav" aria-label="Admin navigation">
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

<button class="nav-item" onclick="switchNavSection('activity', this)"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h5l3-9 4 18 3-9h5"/></svg><span>Activity / Audit Logs</span></button><button class="nav-item" onclick="switchNavSection('notifications', this)"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span>Notifications</span></button><div class="sidebar-divider"></div>
        <button class="nav-item" onclick="switchNavSection('settings', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
          <span>Settings</span>
        </button>
      </nav>


    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="admin-main">

      <div class="topbar">
        <button class="mobile-menu icon-button" aria-label="Toggle navigation" aria-controls="adminSidebar" aria-expanded="false" id="menuToggle"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg></button>
        <label class="search-box"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="10" r="6"/><path d="m15 15 5 5"/></svg><input id="dashboardSearch" type="search" aria-label="Search dashboard activity and listings" placeholder="Search users, listings, orders…" /></label>
        <div class="topbar-actions">
          <button class="icon-button" aria-label="Notifications" onclick="showToast('No new notifications')"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg></button>
          <a class="icon-button" href="marketplace.php" aria-label="Browse marketplace favorites"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/></svg></a>
          <a class="icon-button" href="marketplace.php" aria-label="Open marketplace"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h3l3 12h11l3-9H6"/><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/></svg></a>
          <a class="header-avatar-btn" href="home.php" aria-label="Admin account"><?php echo htmlspecialchars($adminInitials); ?></a>
        </div>
      </div>
      <header class="dashboard-header">
        <div class="header-title-group">
          <h1>Dashboard Overview</h1>
          <p>A snapshot of activity across the marketplace.</p>
        </div>
        <div class="header-actions">
          <button class="filter-button" id="filterToggle" aria-expanded="false" aria-controls="activityFilters"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h18l-7 8v8l-4-2v-6Z"/></svg>Filters</button>
          <button class="export-button" id="exportReport">Export Report</button>
        </div>
      </header>
      <div class="dashboard-content">
      <div class="filter-panel" id="activityFilters" hidden>
        <label for="statusFilter">Activity status</label>
        <select id="statusFilter"><option value="all">All activity</option><option value="pending">Pending</option><option value="flagged">Flagged</option><option value="done">Done</option><option value="new">New</option><option value="dispute">Dispute</option></select>
        <button id="resetFilters" class="filter-button">Reset</button>
      </div>
      <!-- Marketplace metrics -->
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
          <p class="stat-trend" title="Sample monthly comparison">↗ +12% this month</p>
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
          <p class="stat-trend" title="Sample monthly comparison">↗ +8% this month</p>
        </div>

        <!-- Card 3: Revenue This Month -->
        <div class="stat-card revenue-card">
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
          <p class="stat-trend" title="Sample monthly comparison">↗ +22% vs last month</p>
        </div>
        <div class="stat-card">
          <div class="stat-card-top"><span class="stat-label">Pending approvals</span><div class="stat-icon-wrapper pending-icon"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 6v6l4 2"/></svg></div></div>
          <div class="stat-value">5</div>
          <p class="stat-trend attention" title="Sample pending approvals">⊙ Needs attention</p>
        </div>

      </section>

      <div class="overview-grid">
        <section class="activity-card" aria-labelledby="activityTitle">
          <div class="section-heading"><h2 id="activityTitle"><span class="heading-icon"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h5l3-9 4 18 3-9h5"/></svg></span>Recent Activity</h2><span class="time-badge">Last 24 Hours</span></div>
          <div class="table-scroll"><table class="activity-table"><thead><tr><th>Activity</th><th>User / Reference</th><th>When</th><th>Status</th></tr></thead><tbody><tr data-status="pending"><td><div class="activity-description"><span class="activity-icon"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="m9 13-1 8 4-2 4 2-1-8"/></svg></span><span>New seller application submitted</span></div></td><td>Jordan Davis</td><td>15 min ago</td><td><span class="status-pill pending">Pending</span></td></tr><tr data-status="flagged"><td><div class="activity-description"><span class="activity-icon"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 8 3v6c0 5-8 9-8 9s-8-4-8-9V6Z"/></svg></span><span>Listing flagged for review</span></div></td><td>Vintage record player</td><td>40 min ago</td><td><span class="status-pill flagged">Flagged</span></td></tr><tr data-status="done"><td><div class="activity-description"><span class="activity-icon"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9a3.5 3.5 0 0 0 0 7h6a3.5 3.5 0 0 1 0 7H6"/></svg></span><span>Payout processed</span></div></td><td>Sarah Chen, ₱8,200</td><td>1 hr ago</td><td><span class="status-pill done">Done</span></td></tr><tr data-status="new"><td><div class="activity-description"><span class="activity-icon"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="3"/><path d="M3 21v-4a5 5 0 0 1 10 0v4M16 4a3 3 0 0 1 0 6M17 14a4 4 0 0 1 4 4v3"/></svg></span><span>New user registered</span></div></td><td>marius@email.com</td><td>2 hr ago</td><td><span class="status-pill new">New</span></td></tr><tr data-status="dispute"><td><div class="activity-description"><span class="activity-icon"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg></span><span>Transaction disputed</span></div></td><td>Order #10432</td><td>3 hr ago</td><td><span class="status-pill dispute">Dispute</span></td></tr></tbody></table></div>
          <p class="empty-state" id="emptyActivity" hidden>No activity matches your search.</p>
        </section>
        <aside class="listing-column" aria-label="Marketplace overview">
          <div class="listing-heading"><h2>Recent Listings</h2><a href="marketplace.php" class="view-all">View All</a></div>
          <article class="listing-card" id="recentListing">
            <div class="listing-photo"><img src="assets/admin-camera.jpg" alt="Vintage silver and black film camera on a wooden table" /><button class="favorite-button" id="favoriteListing" aria-label="Save vintage film camera" aria-pressed="false"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/></svg></button></div>
            <div class="listing-details"><div class="listing-badges"><span>Product</span><span>Like new</span></div><h3>Vintage film camera</h3><div class="listing-price"><strong>₱4,500</strong><span><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1Z"/></svg>4.8</span></div></div>
          </article>
          <p class="empty-state" id="emptyListings" hidden>No listings match your search.</p>
          <section class="health-card" aria-labelledby="healthTitle"><h2 id="healthTitle">Platform Health</h2><dl><div><dt>Active sellers</dt><dd>348</dd></div><div><dt>Completed txns</dt><dd>1,892</dd></div><div><dt>Avg. listing price</dt><dd>₱3,210</dd></div></dl></section>
        </aside>
      </div>
      <p class="sample-note">Activity, listing, health, approval and trend figures are sample data.</p>
      </div>
    </main>
  </div>

  <!-- Toast Element -->
  <div class="admin-toast" id="adminToast" role="status" aria-live="polite"></div>

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
          document.getElementById('statTotalUsers').textContent = data.stats.total_users ?? '2,481';
          document.getElementById('statTotalListings').textContent = data.stats.total_listings ?? '1,204';
          document.getElementById('statRevenue').textContent = data.stats.revenue_month ?? '₱248,900';

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

    const search = document.getElementById('dashboardSearch');
    const statusFilter = document.getElementById('statusFilter');
    const activityRows = [...document.querySelectorAll('.activity-table tbody tr')];
    function filterDashboard() {
      const query = search.value.trim().toLowerCase();
      activityRows.forEach(row => {
        row.hidden = !row.textContent.toLowerCase().includes(query) ||
          (statusFilter.value !== 'all' && row.dataset.status !== statusFilter.value);
      });
      document.getElementById('emptyActivity').hidden = activityRows.some(row => !row.hidden);
      const listing = document.getElementById('recentListing');
      listing.hidden = !listing.textContent.toLowerCase().includes(query);
      document.getElementById('emptyListings').hidden = !listing.hidden;
    }
    search.addEventListener('input', filterDashboard);
    statusFilter.addEventListener('change', filterDashboard);
    document.getElementById('filterToggle').addEventListener('click', event => {
      const panel = document.getElementById('activityFilters');
      panel.hidden = !panel.hidden;
      event.currentTarget.setAttribute('aria-expanded', String(!panel.hidden));
    });
    document.getElementById('resetFilters').addEventListener('click', () => {
      search.value = ''; statusFilter.value = 'all'; filterDashboard();
    });
    document.getElementById('menuToggle').addEventListener('click', event => {
      const open = document.getElementById('adminSidebar').classList.toggle('is-open');
      event.currentTarget.setAttribute('aria-expanded', String(open));
    });
    document.getElementById('favoriteListing').addEventListener('click', event => {
      const button = event.currentTarget;
      const saved = button.getAttribute('aria-pressed') !== 'true';
      button.setAttribute('aria-pressed', String(saved));
      button.setAttribute('aria-label', saved ? 'Unsave vintage film camera' : 'Save vintage film camera');
      showToast(saved ? 'Sample listing saved for this visit' : 'Sample listing removed from saved items');
    });
    document.getElementById('exportReport').addEventListener('click', () => {
      const report = [['Metric', 'Value'],
        ['Total users', document.getElementById('statTotalUsers').textContent],
        ['Total listings', document.getElementById('statTotalListings').textContent],
        ['Revenue this month', document.getElementById('statRevenue').textContent],
        [], ['Sample activity (current filters)', 'User / Reference', 'When', 'Status'],
        ...activityRows.filter(row => !row.hidden).map(row => [...row.cells].map(cell => cell.textContent.trim()))];
      const csv = report.map(row => row.map(value => '"' + String(value).replaceAll('"', '""') + '"').join(',')).join('\r\n');
      const url = URL.createObjectURL(new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' }));
      const link = document.createElement('a');
      link.href = url; link.download = 'station-finds-dashboard.csv'; link.click();
      setTimeout(() => URL.revokeObjectURL(url), 1000);
    });

    // Initial load + periodic poll every 20 seconds
    loadDashboardData();
    setInterval(loadDashboardData, 20000);
  </script>
</body>
</html>
