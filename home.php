<?php
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'] ?? 'User';
$email    = $_SESSION['email'] ?? '';
// User initials for the top right avatar (e.g. "YO" or "MC")
$initials = !empty($username) ? strtoupper(substr($username, 0, 2)) : 'MC';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Station Finds — Home</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/home.css" />
</head>
<body>

  <!-- ==================== HEADER / NAVBAR ==================== -->
  <header class="navbar">
    <div class="nav-container">
      <!-- Left: Logo & Navigation Links -->
      <div class="nav-left">
        <a href="home.php" class="nav-brand">
          <img src="logo2.png" alt="Station Finds" class="nav-logo-img" />
          <span class="nav-brand-text">
            <span class="brand-station">STATION</span>
            <span class="brand-finds">FINDS</span>
          </span>
        </a>

        <nav class="nav-links">
          <a href="home.php" class="nav-link active">Home</a>
          <a href="marketplace.php" class="nav-link">Marketplace</a>
          <a href="#" class="nav-link">Messages</a>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <a href="admin_dashboard.php" class="nav-link" style="color: #f7a827; font-weight: 700;">Admin Dashboard</a>
          <?php endif; ?>
        </nav>
      </div>

      <!-- Middle: Search Bar -->
      <div class="nav-center">
        <div class="search-bar">
          <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input 
            type="text" 
            class="search-input" 
            placeholder="Search books, gadgets, dorm find, bike rentals, tutors..." 
          />
        </div>
      </div>

      <!-- Right: Action Icons & User Avatar -->
      <div class="nav-right">
        <!-- Notification Bell -->
        <button type="button" class="icon-btn" title="Notifications" aria-label="Notifications">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
        </button>

        <!-- Wishlist Heart -->
        <button type="button" class="icon-btn" title="Saved Items" aria-label="Saved Items">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
        </button>

        <!-- Shopping Cart with Badge -->
        <button type="button" class="icon-btn cart-btn" title="Shopping Cart" aria-label="Shopping Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
        </button>

        <!-- User Profile Avatar & Dropdown -->
        <div class="user-menu-wrapper">
          <button type="button" class="user-avatar-btn" id="userMenuBtn" onclick="toggleUserDropdown()" aria-label="User profile">
            <?php echo htmlspecialchars($initials); ?>
          </button>

          <div class="user-dropdown" id="userDropdown">
            <div class="user-dropdown-header">
              <div class="dropdown-avatar"><?php echo htmlspecialchars($initials); ?></div>
              <div class="dropdown-user-details">
                <span class="dropdown-username"><?php echo htmlspecialchars($username); ?></span>
                <span class="dropdown-email"><?php echo htmlspecialchars($email); ?></span>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin_dashboard.php" class="dropdown-item" style="color: #f7a827; font-weight: 700;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
              Admin Dashboard
            </a>
            <?php endif; ?>
            <a href="#" class="dropdown-item">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              My Profile
            </a>
            <a href="#" class="dropdown-item">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
              </svg>
              My Listings
            </a>
            <div class="dropdown-divider"></div>
            <a href="logout.php" class="dropdown-item logout-link">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              Log out
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- ==================== MAIN CONTENT ==================== -->
  <main class="main-wrapper">
    <div class="main-container">

      <!-- Location Badge Pill -->
      <div class="location-pill">
        <svg class="location-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
          <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <span class="location-text"><strong>Quezon City, PH</strong> <span class="location-sep">–</span> <a href="#" class="location-change">change</a></span>
      </div>

      <!-- ==================== SECTION: TODAY'S PICKS ==================== -->
      <section class="picks-section">
        <h2 class="section-title">Today's picks for you</h2>

        <div class="picks-grid">
          <!-- Card 1: Vintage film camera -->
          <article class="product-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-favorite-btn" aria-label="Save item" onclick="toggleFavorite(this)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
              </button>
            </div>
            <div class="card-content">
              <div class="card-badges">
                <span class="badge badge-product">PRODUCT</span>
                <span class="badge badge-condition">LIKE NEW</span>
              </div>
              <h3 class="card-product-title">Vintage film camera</h3>
              <div class="card-footer-info">
                <span class="card-price">&#8369;4,500</span>
                <div class="card-rating">
                  <svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                  </svg>
                  <span class="rating-value">4.8</span>
                </div>
              </div>
            </div>
          </article>

          <!-- Card 2: City bike rental -->
          <article class="product-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-favorite-btn" aria-label="Save item" onclick="toggleFavorite(this)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
              </button>
            </div>
            <div class="card-content">
              <div class="card-badges">
                <span class="badge badge-rental">RENTAL</span>
                <span class="badge badge-condition">LIKE NEW</span>
              </div>
              <h3 class="card-product-title">City bike rental</h3>
              <div class="card-footer-info">
                <span class="card-price">&#8369;350<span class="price-unit">/day</span></span>
                <div class="card-rating">
                  <svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                  </svg>
                  <span class="rating-value">4.6</span>
                </div>
              </div>
            </div>
          </article>
        </div>
      </section>

      <!-- ==================== SECTION: BROWSE BY CATEGORY ==================== -->
      <section class="category-section">
        <h2 class="section-title">Browse by category</h2>

        <div class="category-grid">
          <!-- Category 1: Products -->
          <div class="category-card">
            <div class="category-card-top">
              <h3 class="category-name">Products</h3>
              <span class="category-count">1.2k listings</span>
            </div>
            <p class="category-desc">Buy &amp; sell textbooks, dorm supplies, electronics, clothes.</p>
            <a href="#" class="category-explore-link">
              Explore category
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </a>
          </div>

          <!-- Category 2: Rental -->
          <div class="category-card">
            <div class="category-card-top">
              <h3 class="category-name">Rental</h3>
              <span class="category-count">450 listings</span>
            </div>
            <p class="category-desc">Rent bikes, scientific calculators, gowns, photography gear.</p>
            <a href="#" class="category-explore-link">
              Explore category
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </a>
          </div>

          <!-- Category 3: Services -->
          <div class="category-card">
            <div class="category-card-top">
              <h3 class="category-name">Services</h3>
              <span class="category-count">520 listings</span>
            </div>
            <p class="category-desc">Hire tutors, laundry services, room cleaners, moving help.</p>
            <a href="#" class="category-explore-link">
              Explore category
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </a>
          </div>
        </div>
      </section>

    </div>
  </main>

  <!-- ==================== FOOTER ==================== -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-top">
        <!-- Logo Emblem -->
        <div class="footer-brand-col">
          <div class="footer-emblem-badge">
            <img src="logo2.png" alt="Station Finds Emblem" class="footer-emblem-img" />
          </div>
        </div>

        <!-- Links Column 1: About Us -->
        <div class="footer-col">
          <h4 class="footer-heading">ABOUT US</h4>
          <ul class="footer-links-list">
            <li><a href="#" class="footer-link">Get to know us</a></li>
            <li><a href="#" class="footer-link">Our commitment</a></li>
          </ul>
        </div>

        <!-- Links Column 2: Contact Us -->
        <div class="footer-col">
          <h4 class="footer-heading">CONTACT US</h4>
          <ul class="footer-links-list">
            <li class="footer-text-item">Katipunan Ave, Quezon City, PH</li>
            <li><a href="mailto:support@stationfinds.edu.ph" class="footer-link">support@stationfinds.edu.ph</a></li>
            <li class="footer-text-item">+63 (2) 8123&ndash;4567</li>
          </ul>
        </div>

        <!-- Links Column 3: Policies -->
        <div class="footer-col">
          <h4 class="footer-heading">POLICIES</h4>
          <ul class="footer-links-list">
            <li><a href="#" class="footer-link">Privacy policy</a></li>
            <li><a href="#" class="footer-link">Terms of service</a></li>
            <li><a href="#" class="footer-link">Refund policy</a></li>
          </ul>
        </div>
      </div>

      <!-- Divider line -->
      <div class="footer-divider"></div>

      <!-- Bottom Copyright -->
      <div class="footer-bottom">
        <p>&copy; 2026 STATIONFINDS. Developed for student safety, affordability, and convenience.</p>
      </div>
    </div>
  </footer>

  <script>
    function toggleUserDropdown() {
      const dropdown = document.getElementById('userDropdown');
      dropdown.classList.toggle('show');
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
      const menuWrapper = document.querySelector('.user-menu-wrapper');
      if (menuWrapper && !menuWrapper.contains(e.target)) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) dropdown.classList.remove('show');
      }
    });

    // Toggle favorite heart button
    function toggleFavorite(btn) {
      const svg = btn.querySelector('svg');
      const isFav = btn.classList.toggle('active');
      if (isFav) {
        svg.setAttribute('fill', '#ef4444');
        svg.setAttribute('stroke', '#ef4444');
      } else {
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', 'currentColor');
      }
    }

  </script>
</body>
</html>
