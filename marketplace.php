<?php
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'] ?? 'User';
$email    = $_SESSION['email'] ?? '';
$initials = !empty($username) ? strtoupper(substr($username, 0, 2)) : 'MC';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Station Finds — Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/marketplace.css" />
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
          <a href="home.php" class="nav-link">Home</a>
          <a href="marketplace.php" class="nav-link active">Marketplace</a>
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
          <input type="text" class="search-input" placeholder="Search books, gadgets, dorm find, bike rentals, tutors..." />
        </div>
      </div>

      <!-- Right: Action Icons & User Avatar -->
      <div class="nav-right">
        <button type="button" class="icon-btn" title="Notifications" aria-label="Notifications">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
        </button>
        <button type="button" class="icon-btn" title="Saved Items" aria-label="Saved Items">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
        </button>
        <button type="button" class="icon-btn cart-btn" title="Shopping Cart" aria-label="Shopping Cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
          <span class="cart-badge">4</span>
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
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              My Profile
            </a>
            <a href="#" class="dropdown-item">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line></svg>
              My Listings
            </a>
            <div class="dropdown-divider"></div>
            <a href="logout.php" class="dropdown-item logout-link">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
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

      <!-- Page Title Row -->
      <div class="marketplace-header">
        <div>
          <h1 class="marketplace-title">Marketplace</h1>
          <p class="marketplace-subtitle" id="marketplaceSubtitle"></p>
        </div>
        <span class="results-count" id="resultsCount"></span>
      </div>

      <!-- ========== OVERVIEW SECTION (default landing) ========== -->
      <div id="view-overview" class="mp-view active">

        <!-- Category Summary Cards -->
        <div class="overview-categories">
          <!-- Products -->
          <div class="overview-cat-card" onclick="switchTab('products')">
            <div class="overview-cat-top">
              <div class="overview-cat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                  <line x1="3" y1="6" x2="21" y2="6"></line>
                  <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
              </div>
              <div>
                <h3 class="overview-cat-name">Products</h3>
                <span class="overview-cat-count">1,245 listings</span>
              </div>
            </div>
            <p class="overview-cat-desc">Find student essentials: cheap books, study lamps, second-hand calculators, and dorm gear.</p>
            <button class="overview-cat-btn" onclick="event.stopPropagation(); switchTab('products')">Browse all products</button>
          </div>

          <!-- Rentals -->
          <div class="overview-cat-card" onclick="switchTab('rentals')">
            <div class="overview-cat-top">
              <div class="overview-cat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="7.5" cy="15.5" r="5.5"></circle>
                  <path d="m21 2-9.6 9.6"></path>
                  <path d="m15.5 7.5 3 3L22 7l-3-3"></path>
                </svg>
              </div>
              <div>
                <h3 class="overview-cat-name">Rentals</h3>
                <span class="overview-cat-count">1,245 listings</span>
              </div>
            </div>
            <p class="overview-cat-desc">Borrow tools, appliances, gear, and transit options. Keep campus costs down.</p>
            <button class="overview-cat-btn" onclick="event.stopPropagation(); switchTab('rentals')">Browse all rentals</button>
          </div>

          <!-- Services -->
          <div class="overview-cat-card" onclick="switchTab('services')">
            <div class="overview-cat-top">
              <div class="overview-cat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
              </div>
              <div>
                <h3 class="overview-cat-name">Services</h3>
                <span class="overview-cat-count">1,245 listings</span>
              </div>
            </div>
            <p class="overview-cat-desc">On-demand student labor: moving support, thesis proofreading, custom code debugging, peer tutorials.</p>
            <button class="overview-cat-btn" onclick="event.stopPropagation(); switchTab('services')">Browse all services</button>
          </div>
        </div>

        <!-- Popular Listings -->
        <h2 class="section-title">Popular Listings</h2>
        <div class="listing-grid">
          <!-- Card 1 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save item" onclick="toggleFavorite(this)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="card-body">
              <div class="card-badges">
                <span class="badge badge-product">PRODUCT</span>
                <span class="badge badge-condition">LIKE NEW</span>
              </div>
              <h3 class="card-title">Vintage film camera</h3>
              <div class="card-footer">
                <span class="card-price">&#8369;4,500</span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div>
              </div>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save item" onclick="toggleFavorite(this)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="card-body">
              <div class="card-badges">
                <span class="badge badge-product">PRODUCT</span>
                <span class="badge badge-condition">EXELLENCE</span>
              </div>
              <h3 class="card-title">Lab gown size M</h3>
              <div class="card-footer">
                <span class="card-price">&#8369;4,500</span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div>
              </div>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save item" onclick="toggleFavorite(this)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="card-body">
              <div class="card-badges">
                <span class="badge badge-rental">RENTAL</span>
                <span class="badge badge-condition">PREMIUM</span>
              </div>
              <h3 class="card-title">DSLR camera rental</h3>
              <div class="card-footer">
                <span class="card-price">&#8369;1,200</span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.9</span></div>
              </div>
            </div>
          </article>

          <!-- Card 4 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save item" onclick="toggleFavorite(this)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
              </button>
            </div>
            <div class="card-body">
              <div class="card-badges">
                <span class="badge badge-service">SERVICE</span>
                <span class="badge badge-condition">BEST SELLER</span>
              </div>
              <h3 class="card-title">Calculus tutor</h3>
              <div class="card-footer">
                <span class="card-price">&#8369;300<span class="price-unit">/hr</span></span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">5.0</span></div>
              </div>
            </div>
          </article>
        </div>
      </div>

      <!-- ========== PRODUCTS TAB ========== -->
      <div id="view-products" class="mp-view">
        <!-- Tab Switcher + Controls -->
        <div class="tab-bar">
          <div class="tab-pills">
            <button class="tab-pill" onclick="switchTab('products')">Products</button>
            <button class="tab-pill" onclick="switchTab('rentals')">Rentals</button>
            <button class="tab-pill" onclick="switchTab('services')">Services</button>
          </div>
          <div class="tab-controls">
            <div class="sort-dropdown">
              <span>Sort: Popularity</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <button class="filter-btn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              Filters
            </button>
          </div>
        </div>

        <div class="listing-grid listing-grid-full">
          <!-- Row 1 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">LIKE NEW</span></div><h3 class="card-title">Vintage film camera</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">LIKE NEW</span></div><h3 class="card-title">Vintage film camera</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">EXELLENCE</span></div><h3 class="card-title">Lab gown size M</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">EXELLENCE</span></div><h3 class="card-title">Lab gown size M</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
          <!-- Row 2 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">EXELLENCE</span></div><h3 class="card-title">Lab gown size M</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">EXELLENCE</span></div><h3 class="card-title">Lab gown size M</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">EXELLENCE</span></div><h3 class="card-title">Lab gown size M</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body"><div class="card-badges"><span class="badge badge-product">PRODUCT</span><span class="badge badge-condition">EXELLENCE</span></div><h3 class="card-title">Lab gown size M</h3><div class="card-footer"><span class="card-price">&#8369;4,500</span><div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div></div></div>
          </article>
        </div>
      </div>

      <!-- ========== RENTALS TAB ========== -->
      <div id="view-rentals" class="mp-view">
        <div class="tab-bar">
          <div class="tab-pills">
            <button class="tab-pill" onclick="switchTab('products')">Products</button>
            <button class="tab-pill" onclick="switchTab('rentals')">Rentals</button>
            <button class="tab-pill" onclick="switchTab('services')">Services</button>
          </div>
          <div class="tab-controls">
            <div class="sort-dropdown">
              <span>Sort: Popularity</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <button class="filter-btn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              Filters
            </button>
          </div>
        </div>

        <div class="listing-grid listing-grid-full">
          <!-- Rental Card 1 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-available">AVAILABLE NOW</span><span class="badge badge-condition">WEEKLY</span></div>
              <h3 class="card-title">Campus commuter bike</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 2–9 · 7 days</span></div>
            </div>
          </article>
          <!-- Rental Card 2 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-date">OCT 3</span><span class="badge badge-condition">DAILY</span></div>
              <h3 class="card-title">Portable mini projector</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 3–5 · 3 days</span></div>
            </div>
          </article>
          <!-- Rental Card 3 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-available">AVAILABLE NOW</span><span class="badge badge-condition">MONTHLY</span></div>
              <h3 class="card-title">Scientific calculator</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 2–30 · 30 days</span></div>
            </div>
          </article>
          <!-- Rental Card 4 -->
          <article class="listing-card">
            <div class="card-media">
              <div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div>
              <button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button>
            </div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-date">OCT 4</span><span class="badge badge-condition">WEEKEND</span></div>
              <h3 class="card-title">Mirrorless camera kit</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 4–6 · 2 days</span></div>
            </div>
          </article>
          <!-- Row 2 -->
          <article class="listing-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-available">AVAILABLE NOW</span><span class="badge badge-condition">WEEKLY</span></div>
              <h3 class="card-title">iPad with pencil</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 2–9 · 7 days</span></div>
            </div>
          </article>
          <article class="listing-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-available">AVAILABLE NOW</span><span class="badge badge-condition">WEEKLY</span></div>
              <h3 class="card-title">Campus commuter bike</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 2–9 · 7 days</span></div>
            </div>
          </article>
          <article class="listing-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-available">AVAILABLE NOW</span><span class="badge badge-condition">WEEKLY</span></div>
              <h3 class="card-title">Campus commuter bike</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 2–9 · 7 days</span></div>
            </div>
          </article>
          <article class="listing-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-available">AVAILABLE NOW</span><span class="badge badge-condition">WEEKLY</span></div>
              <h3 class="card-title">Campus commuter bike</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Oct 2–9 · 7 days</span></div>
            </div>
          </article>
        </div>
      </div>

      <!-- ========== SERVICES TAB ========== -->
      <div id="view-services" class="mp-view">
        <div class="tab-bar">
          <div class="tab-pills">
            <button class="tab-pill" onclick="switchTab('products')">Products</button>
            <button class="tab-pill" onclick="switchTab('rentals')">Rentals</button>
            <button class="tab-pill" onclick="switchTab('services')">Services</button>
          </div>
          <div class="tab-controls">
            <div class="sort-dropdown">
              <span>Sort: Top rated</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <button class="filter-btn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              Filters
            </button>
          </div>
        </div>

        <div class="listing-grid listing-grid-full">
          <!-- Service Card 1 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Calculus tutoring</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">ACADEMIC</span><span class="badge badge-seller">MIKA R.</span></div>
              <h3 class="card-title">Calculus tutoring</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Weekdays · 4:00–7:00 PM</span></div>
              <div class="card-footer">
                <span class="card-price">&#8369;350<span class="price-unit"> / hour</span></span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.9</span></div>
              </div>
              <div class="card-detail-line">10 sessions completed</div>
            </div>
          </article>
          <!-- Service Card 2 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Thesis proofreading</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">WRITING</span><span class="badge badge-seller">PAOLO D.</span></div>
              <h3 class="card-title">Thesis proofreading</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Mon–Sat · 24-hour turnaround</span></div>
              <div class="card-footer">
                <span class="card-price">From &#8369;450</span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div>
              </div>
              <div class="card-detail-line">Per 18 pages</div>
            </div>
          </article>
          <!-- Service Card 3 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Graduation portraits</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">CREATIVE</span><span class="badge badge-seller">BEA S.</span></div>
              <h3 class="card-title">Graduation portraits</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Weekends · 8:00 AM–5:00 PM</span></div>
              <div class="card-footer">
                <span class="card-price">&#8369;1,200<span class="price-unit"> / shoot</span></span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">5.0</span></div>
              </div>
              <div class="card-detail-line">10 edited photos</div>
            </div>
          </article>
          <!-- Service Card 4 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Laptop tune-up</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">TECH</span><span class="badge badge-seller">JULES C.</span></div>
              <h3 class="card-title">Laptop tune-up</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Tue & Thu · 1:00–6:00 PM</span></div>
              <div class="card-footer">
                <span class="card-price">From &#8369;500</span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.7</span></div>
              </div>
              <div class="card-detail-line">Diagnostics included</div>
            </div>
          </article>
          <!-- Service Card 5 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Presentation design</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">CREATIVE</span><span class="badge badge-seller">NINA A.</span></div>
              <h3 class="card-title">Presentation design</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Evenings · 6:00–10:00 PM</span></div>
              <div class="card-footer">
                <span class="card-price">&#8369;180<span class="price-unit"> / slide</span></span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.9</span></div>
              </div>
            </div>
          </article>
          <!-- Service Card 6 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Conversational Filipino</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">ACADEMIC</span><span class="badge badge-seller">LUIS M.</span></div>
              <h3 class="card-title">Conversational Filipino</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Mon, Wed, Fri · 5:00 PM</span></div>
              <div class="card-footer">
                <span class="card-price">&#8369;280<span class="price-unit"> / hour</span></span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.8</span></div>
              </div>
            </div>
          </article>
          <!-- Service Card 7 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Beginner fitness coaching</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">WELLNESS</span><span class="badge badge-seller">SAM P.</span></div>
              <h3 class="card-title">Beginner fitness coaching</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Mornings · 6:00–9:00 AM</span></div>
              <div class="card-footer">
                <span class="card-price">&#8369;300<span class="price-unit"> / session</span></span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.9</span></div>
              </div>
            </div>
          </article>
          <!-- Service Card 8 -->
          <article class="listing-card service-card">
            <div class="card-media"><div class="mockup-img-placeholder">
                <svg class="mockup-placeholder-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span class="mockup-placeholder-text">Mockup Image</span>
              </div><div class="card-overlay-label">Print &amp; bind pickup</div><button type="button" class="card-fav-btn" aria-label="Save" onclick="toggleFavorite(this)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></button></div>
            <div class="card-body">
              <div class="card-badges"><span class="badge badge-service-cat">ERRANDS</span><span class="badge badge-seller">AYA T.</span></div>
              <h3 class="card-title">Print &amp; bind pickup</h3>
              <div class="card-meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg><span>Daily · Same-day before 3:00 PM</span></div>
              <div class="card-footer">
                <span class="card-price">From &#8369;120</span>
                <div class="card-rating"><svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><span class="rating-val">4.6</span></div>
              </div>
            </div>
          </article>
        </div>
      </div>

    </div>
  </main>

  <!-- ==================== FOOTER ==================== -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-top">
        <div class="footer-brand-col">
          <div class="footer-emblem-badge">
            <img src="logo2.png" alt="Station Finds Emblem" class="footer-emblem-img" />
          </div>
        </div>
        <div class="footer-col">
          <h4 class="footer-heading">ABOUT US</h4>
          <ul class="footer-links-list">
            <li><a href="#" class="footer-link">Get to know us</a></li>
            <li><a href="#" class="footer-link">Our commitment</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4 class="footer-heading">CONTACT US</h4>
          <ul class="footer-links-list">
            <li class="footer-text-item">Katipunan Ave, Quezon City, PH</li>
            <li><a href="mailto:support@stationfinds.edu.ph" class="footer-link">support@stationfinds.edu.ph</a></li>
            <li class="footer-text-item">+63 (2) 8123&ndash;4567</li>
          </ul>
        </div>
        <div class="footer-col">
          <h4 class="footer-heading">POLICIES</h4>
          <ul class="footer-links-list">
            <li><a href="#" class="footer-link">Privacy policy</a></li>
            <li><a href="#" class="footer-link">Terms of service</a></li>
            <li><a href="#" class="footer-link">Refund policy</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-divider"></div>
      <div class="footer-bottom">
        <p>&copy; 2026 STATIONFINDS. Developed for student safety, affordability, and convenience.</p>
      </div>
    </div>
  </footer>

  <script>
    /* ── Tab switching logic ──────────────────────────────────── */
    const tabConfig = {
      overview: { subtitle: '', count: '' },
      products: { subtitle: '', count: '128 results found' },
      rentals:  { subtitle: 'Borrow what you need, only for as long as you need it.', count: '64 rentals found' },
      services: { subtitle: 'Book student talent for study, creative work, and everyday help.', count: '92 services found' }
    };

    function switchTab(tab) {
      // Hide all views
      document.querySelectorAll('.mp-view').forEach(v => v.classList.remove('active'));
      // Show target
      const target = document.getElementById('view-' + tab);
      if (target) target.classList.add('active');

      // Update header info
      const cfg = tabConfig[tab] || tabConfig.overview;
      document.getElementById('marketplaceSubtitle').textContent = cfg.subtitle;
      document.getElementById('resultsCount').textContent = cfg.count;

      // Highlight active pills inside the view
      if (target) {
        target.querySelectorAll('.tab-pill').forEach(pill => {
          pill.classList.toggle('active', pill.textContent.trim().toLowerCase() === tab);
        });
      }
    }

    // Initialize on page load
    switchTab('overview');

    /* ── User dropdown toggle ─────────────────────────────────── */
    function toggleUserDropdown() {
      document.getElementById('userDropdown').classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
      const wrapper = document.querySelector('.user-menu-wrapper');
      if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('userDropdown').classList.remove('show');
      }
    });

    /* ── Favorite heart toggle ────────────────────────────────── */
    function toggleFavorite(btn) {
      const svg = btn.querySelector('svg');
      const isFav = btn.classList.toggle('active');
      svg.setAttribute('fill', isFav ? '#ef4444' : 'none');
      svg.setAttribute('stroke', isFav ? '#ef4444' : 'currentColor');
    }
  </script>
</body>
</html>