<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>

<style>
  html, body {
    height: 100%;
    margin: 0;
  }

  #wrapper {
    display: flex;
    min-height: 100vh;
  }

  #sidebar {
    width: 250px;
    background-color: #343a40;
    color: white;
    flex-shrink: 0;
    padding-top: 20px;
  }

  #sidebar .nav-link {
    color: #adb5bd;
    padding: 12px 20px;
    display: block;
  }

  #sidebar .nav-link.active,
  #sidebar .nav-link:hover {
    background-color: #495057;
    color: white;
  }

  #main-content {
    flex-grow: 1;
    padding: 20px;
    background-color: #f8f9fa;
  }

  @media (max-width: 768px) {
    #sidebar {
      position: absolute;
      left: -250px;
      top: 0;
      height: 100vh;
      transition: left 0.3s ease;
      z-index: 1000;
    }

    #sidebar.active {
      left: 0;
    }

    #sidebarToggle {
      display: block;
    }
  }

  #sidebarToggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    background-color: #343a40;
    border: none;
    color: white;
    padding: 10px 12px;
    border-radius: 4px;
    z-index: 1100;
  }
</style>

<!-- Toggle Button -->
<button id="sidebarToggle" class="btn">
  <i class="fas fa-bars"></i>
</button>

<div id="wrapper">
  <nav id="sidebar">
    <div class="text-center mb-4">
      <a href="/QaviEcommerce/admin/dashboard" class="text-white text-decoration-none fs-4 fw-bold d-block">
        <i class="fas fa-shop me-2"></i> Admin Panel
      </a>
    </div>
    <hr class="text-secondary mx-3">
    <ul class="nav flex-column px-2">
      <li><a href="/QaviEcommerce/admin/dashboard" class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/QaviEcommerce/admin/dashboard') ? 'active' : '' ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
      <li><a href="/QaviEcommerce/admin/manageProducts" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/manageProducts') !== false) ? 'active' : '' ?>"><i class="fas fa-box-open me-2"></i> Products</a></li>
      <li><a href="/QaviEcommerce/admin/manageCategories" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/manageCategories') !== false) ? 'active' : '' ?>"><i class="fas fa-tags me-2"></i> Categories</a></li>
      <li><a href="/QaviEcommerce/admin/manageOrders" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/manageOrders') !== false) ? 'active' : '' ?>"><i class="fas fa-shopping-cart me-2"></i> Orders</a></li>
      <li><a href="/QaviEcommerce/admin/manageUsers" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/admin/manageUsers') !== false) ? 'active' : '' ?>"><i class="fas fa-users me-2"></i> Users</a></li>
    </ul>
    <hr class="text-secondary mx-3">
    <div class="px-3 mt-auto">
      <a href="/QaviEcommerce/admin/logout" class="btn btn-danger w-100"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>
  </nav>
