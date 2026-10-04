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
  <link rel="stylesheet" href="css/admin.css" />
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

        <button class="nav-item" onclick="switchNavSection('audit', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
          </svg>
          <span>Activity / Audit Logs</span>
        </button>

        <button class="nav-item" onclick="toggleNotifDropdown()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          <span>Notifications</span>
          <span class="nav-badge" id="sidebarNotifBadge">0</span>
        </button>

        <button class="nav-item" onclick="switchNavSection('settings', this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
          <span>Settings</span>
        </button>
      </nav>

      <!-- Sidebar Footer (Quick Links & Logout) -->
      <div class="sidebar-divider"></div>
      <div class="sidebar-footer">
        <a href="home.php" class="nav-item">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          <span>Go to Marketplace</span>
        </a>
        <a href="logout.php" class="nav-item" style="color: #dc2626;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
          <span>Log out</span>
        </a>
      </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="admin-main">

      <!-- TOP DASHBOARD HEADER -->
      <header class="dashboard-header">
        <div class="header-title-group">
          <h1>Dashboard Overview</h1>
          <p>A snapshot of activity across the marketplace.</p>
        </div>

        <div class="header-actions">
          <!-- Notification Bell Button -->
          <button class="header-icon-btn" id="notifBellBtn" onclick="toggleNotifDropdown()" title="Notifications">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notif-badge-dot" id="notifDot" style="display: none;"></span>
          </button>

          <!-- Notification Dropdown -->
          <div class="notif-dropdown" id="notifDropdown">
            <div class="notif-header">
              <h4>Notifications (<span id="notifCountText">0</span>)</h4>
              <button class="notif-clear-btn" onclick="markAllNotifsRead()">Mark all as read</button>
            </div>
            <ul class="notif-list" id="notifList">
              <li class="notif-item">No new notifications.</li>
            </ul>
          </div>

          <!-- Admin Avatar Pill Button -->
          <button class="header-avatar-btn" title="Admin Account" onclick="window.location.href='home.php'">
            <?php echo htmlspecialchars($adminInitials); ?>
          </button>
        </div>
      </header>

      <!-- 4 METRIC STAT CARDS -->
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

        <!-- Card 4: Pending Approvals (Jump to Approvals) -->
        <div class="stat-card highlight" onclick="scrollToApprovals()">
          <div class="stat-card-top">
            <span class="stat-label">Pending approvals</span>
            <div class="stat-icon-wrapper">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
          </div>
          <div class="stat-value" id="statPendingApprovals">5</div>
        </div>
      </section>

      <!-- ==================== PASSWORD APPROVALS & USER FEEDBACK ==================== -->
      <section class="dashboard-section" id="approvalsSection">
        <div class="section-header-row">
          <div class="section-title-wrap">
            <h2 class="section-main-title">Password Change Requests &amp; Approvals</h2>
            <span class="pending-count-badge" id="pendingBadgeText">5 Pending</span>
          </div>

          <!-- Status Filters -->
          <div class="approval-filter-tabs">
            <button class="approval-tab-btn active" onclick="filterApprovals('all', this)">All</button>
            <button class="approval-tab-btn" onclick="filterApprovals('pending', this)">Pending</button>
            <button class="approval-tab-btn" onclick="filterApprovals('approved', this)">Approved</button>
            <button class="approval-tab-btn" onclick="filterApprovals('rejected', this)">Rejected</button>
          </div>
        </div>

        <div class="data-table-wrap">
          <table class="custom-table" id="approvalsTable">
            <thead>
              <tr>
                <th>User</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Admin Feedback / Notes</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="approvalsTableBody">
              <tr>
                <td colspan="5" style="text-align: center; color: #94a3b8; padding: 30px;">Loading requests…</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- ==================== RECENT ACTIVITY ==================== -->
      <section class="activity-card">
        <div class="activity-header">
          <span>RECENT ACTIVITY</span>
          <span>USER</span>
          <span>WHEN</span>
        </div>

        <ul class="activity-list" id="activityList">
          <!-- Fallback initial items matching the screenshot -->
          <li class="activity-row">
            <span class="activity-action">New seller application submitted</span>
            <span class="activity-user">Jordan Davis</span>
            <span class="activity-when">15 min ago</span>
          </li>
          <li class="activity-row">
            <span class="activity-action">Listing flagged for review</span>
            <span class="activity-user">Vintage record player</span>
            <span class="activity-when">40 min ago</span>
          </li>
          <li class="activity-row">
            <span class="activity-action">Payout processed</span>
            <span class="activity-user">Sarah Chen, ₱8,200</span>
            <span class="activity-when">1 hr ago</span>
          </li>
          <li class="activity-row">
            <span class="activity-action">New user registered</span>
            <span class="activity-user">marius@email.com</span>
            <span class="activity-when">2 hr ago</span>
          </li>
          <li class="activity-row">
            <span class="activity-action">Transaction disputed</span>
            <span class="activity-user">Order #10432</span>
            <span class="activity-when">3 hr ago</span>
          </li>
        </ul>
      </section>

    </main>
  </div>

  <!-- ==================== APPROVE / REJECT WITH FEEDBACK MODAL ==================== -->
  <div class="modal-overlay" id="decisionModal">
    <div class="modal-box">
      <h3 class="modal-title" id="modalDecisionTitle">Review Password Request</h3>
      <p class="modal-desc" id="modalDecisionDesc">
        Provide feedback for <strong id="modalTargetUser">User</strong> before confirming.
      </p>

      <label style="font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
        Feedback / Reason for User:
      </label>
      <textarea id="modalFeedbackText" class="modal-textarea" placeholder="E.g. Approved: Password meets security requirements."></textarea>

      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeDecisionModal()">Cancel</button>
        <button type="button" id="modalConfirmBtn" class="btn-approve" onclick="submitDecisionModal()">
          Confirm Decision
        </button>
      </div>
    </div>
  </div>

  <!-- Toast Element -->
  <div class="admin-toast" id="adminToast"></div>

  <!-- ==================== JAVASCRIPT ==================== -->
  <script>
    let allRequests = [];
    let currentFilter = 'all';
    let activeDecision = { requestId: null, decision: null, username: '' };

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
          // Update Stats
          document.getElementById('statTotalUsers').textContent = data.stats.total_users || '2,481';
          document.getElementById('statTotalListings').textContent = data.stats.total_listings || '1,204';
          document.getElementById('statRevenue').textContent = data.stats.revenue_month || '₱248,900';
          document.getElementById('statPendingApprovals').textContent = data.stats.pending_approvals;
          document.getElementById('pendingBadgeText').textContent = data.stats.pending_approvals + ' Pending';

          // Update Requests
          allRequests = data.requests || [];
          renderApprovalsTable();

          // Update Activities
          if (data.activities && data.activities.length > 0) {
            renderActivities(data.activities);
          }

          // Update Notifications
          renderNotifications(data.notifications || [], data.unread_count || 0);
        }
      } catch (err) {
        console.error('Error fetching dashboard data:', err);
      }
    }

    // ── Render Approvals Table ─────────────────────────────────────
    function renderApprovalsTable() {
      const tbody = document.getElementById('approvalsTableBody');
      const filtered = allRequests.filter(r => {
        if (currentFilter === 'all') return true;
        return r.status === currentFilter;
      });

      if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 28px;">No ${currentFilter === 'all' ? '' : currentFilter} requests found.</td></tr>`;
        return;
      }

      tbody.innerHTML = filtered.map(req => {
        const isPending = req.status === 'pending';
        const isApproved = req.status === 'approved';
        const isRejected = req.status === 'rejected';

        let badgeClass = 'status-pending';
        if (isApproved) badgeClass = 'status-approved';
        if (isRejected) badgeClass = 'status-rejected';

        const feedbackDisplay = req.admin_feedback 
          ? `<div class="feedback-note-text">${escapeHtml(req.admin_feedback)}</div>`
          : `<span style="color: #94a3b8; font-size: 12px; font-style: italic;">No feedback recorded yet</span>`;

        let actionHtml = '';
        if (isPending) {
          actionHtml = `
            <div class="table-actions">
              <button class="btn-approve" onclick="openDecisionModal(${req.id}, 'approve', '${escapeHtml(req.username)}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Approve
              </button>
              <button class="btn-reject" onclick="openDecisionModal(${req.id}, 'reject', '${escapeHtml(req.username)}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Reject
              </button>
            </div>
          `;
        } else {
          actionHtml = `
            <div class="table-actions">
              <button class="btn-feedback" onclick="openDecisionModal(${req.id}, '${req.status}', '${escapeHtml(req.username)}', '${escapeHtml(req.admin_feedback || '')}')">
                Edit Feedback
              </button>
            </div>
          `;
        }

        return `
          <tr id="req-row-${req.id}">
            <td>
              <div class="table-user-cell">
                <div class="user-cell-avatar">${req.avatar_initials}</div>
                <div>
                  <span class="user-cell-name">${escapeHtml(req.username)}</span>
                  <span class="user-cell-email">${escapeHtml(req.email)}</span>
                </div>
              </div>
            </td>
            <td>
              <div style="font-weight: 500; color: #1e293b;">${req.time_ago}</div>
              <div style="font-size: 11px; color: #94a3b8;">${req.created_at}</div>
            </td>
            <td>
              <span class="status-pill ${badgeClass}">
                ${req.status.toUpperCase()}
              </span>
            </td>
            <td>${feedbackDisplay}</td>
            <td>${actionHtml}</td>
          </tr>
        `;
      }).join('');
    }

    // ── Filter tabs ────────────────────────────────────────────────
    function filterApprovals(filter, btn) {
      currentFilter = filter;
      document.querySelectorAll('.approval-tab-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      renderApprovalsTable();
    }

    // ── Render Recent Activities ───────────────────────────────────
    function renderActivities(acts) {
      const list = document.getElementById('activityList');
      list.innerHTML = acts.map(a => `
        <li class="activity-row">
          <span class="activity-action">${escapeHtml(a.activity)}</span>
          <span class="activity-user">${escapeHtml(a.user_identifier)}</span>
          <span class="activity-when">${a.time_ago}</span>
        </li>
      `).join('');
    }

    // ── Render Notifications ───────────────────────────────────────
    function renderNotifications(notifs, unreadCount) {
      const dot = document.getElementById('notifDot');
      const badge = document.getElementById('sidebarNotifBadge');
      const countText = document.getElementById('notifCountText');
      const list = document.getElementById('notifList');

      if (unreadCount > 0) {
        dot.style.display = 'block';
        badge.textContent = unreadCount;
        badge.style.display = 'inline-block';
        countText.textContent = unreadCount;
      } else {
        dot.style.display = 'none';
        badge.style.display = 'none';
        countText.textContent = '0';
      }

      if (notifs.length === 0) {
        list.innerHTML = `<li class="notif-item">No new notifications.</li>`;
        return;
      }

      list.innerHTML = notifs.map(n => `
        <li class="notif-item ${n.is_read == 0 ? 'unread' : ''}">
          <div class="notif-item-title">${escapeHtml(n.title)}</div>
          <div class="notif-item-msg">${escapeHtml(n.message)}</div>
          <div class="notif-item-time">${n.time_ago}</div>
        </li>
      `).join('');
    }

    // ── Decision Modal (Approve or Reject with custom feedback) ────
    function openDecisionModal(requestId, decision, username, currentFeedback = '') {
      activeDecision = { requestId, decision, username };

      const titleEl = document.getElementById('modalDecisionTitle');
      const descEl = document.getElementById('modalDecisionDesc');
      const textEl = document.getElementById('modalFeedbackText');
      const confirmBtn = document.getElementById('modalConfirmBtn');
      const targetUser = document.getElementById('modalTargetUser');

      targetUser.textContent = username;

      if (decision === 'approve') {
        titleEl.textContent = 'Approve Password Change';
        descEl.innerHTML = `Are you sure you want to approve the password change for <strong>${escapeHtml(username)}</strong>? This will activate their new password.`;
        textEl.value = currentFeedback || 'Password change approved by administrator. Meets security requirements.';
        confirmBtn.className = 'btn-approve';
        confirmBtn.textContent = 'Confirm Approval';
      } else {
        titleEl.textContent = 'Reject Password Change';
        descEl.innerHTML = `Are you sure you want to reject the password change for <strong>${escapeHtml(username)}</strong>? The user will be notified with your feedback.`;
        textEl.value = currentFeedback || 'Password change rejected: Does not meet required campus password policy.';
        confirmBtn.className = 'btn-reject';
        confirmBtn.textContent = 'Confirm Rejection';
      }

      document.getElementById('decisionModal').classList.add('active');
    }

    function closeDecisionModal() {
      document.getElementById('decisionModal').classList.remove('active');
    }

    async function submitDecisionModal() {
      const feedback = document.getElementById('modalFeedbackText').value.trim();
      const confirmBtn = document.getElementById('modalConfirmBtn');
      confirmBtn.disabled = true;
      confirmBtn.textContent = 'Processing…';

      try {
        const res = await fetch('auth_handler.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'admin_handle_password_request',
            request_id: activeDecision.requestId,
            decision: activeDecision.decision,
            feedback: feedback
          })
        });
        const result = await res.json();

        if (result.success) {
          showToast(result.message, activeDecision.decision === 'approve' ? 'success' : 'error');
          closeDecisionModal();
          // Reload fresh dashboard data
          await loadDashboardData();
        } else {
          showToast(result.message, 'error');
        }
      } catch (err) {
        showToast('Network error while processing request.', 'error');
      } finally {
        confirmBtn.disabled = false;
      }
    }

    // ── Notifications Toggle & Mark as Read ────────────────────────
    function toggleNotifDropdown() {
      const dd = document.getElementById('notifDropdown');
      dd.classList.toggle('show');
    }

    async function markAllNotifsRead() {
      await fetch('auth_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'admin_mark_notifications_read' })
      });
      document.getElementById('notifDot').style.display = 'none';
      document.getElementById('sidebarNotifBadge').style.display = 'none';
      document.getElementById('notifCountText').textContent = '0';
      document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
      showToast('Notifications marked as read.');
    }

    // Close notification dropdown when clicked outside
    document.addEventListener('click', function(e) {
      const btn = document.getElementById('notifBellBtn');
      const dd = document.getElementById('notifDropdown');
      if (dd && btn && !btn.contains(e.target) && !dd.contains(e.target)) {
        dd.classList.remove('show');
      }
    });

    // ── Sidebar Navigation switching ──────────────────────────────
    function switchNavSection(secName, btn) {
      document.querySelectorAll('.sidebar-nav .nav-item').forEach(b => b.classList.remove('active'));
      if (btn) btn.classList.add('active');

      if (secName === 'overview') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else if (secName === 'audit' || secName === 'users') {
        scrollToApprovals();
      } else {
        showToast('Viewing ' + btn.querySelector('span').textContent, 'success');
      }
    }

    function scrollToApprovals() {
      document.getElementById('approvalsSection').scrollIntoView({ behavior: 'smooth' });
    }

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    // Initial load + periodic poll every 20 seconds
    loadDashboardData();
    setInterval(loadDashboardData, 20000);
  </script>
</body>
</html>
