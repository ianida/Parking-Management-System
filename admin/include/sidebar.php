<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />


<!-- root/admin/include/sidebar.php -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand m-0" href="index.php">
      <h4>EasyPark</h4>
    </a>
  </div>

  <hr class="horizontal dark mt-0">

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <!-- Dashboard -->
      <li class="nav-item">
        <a class="nav-link active" href="index.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-home text-white text-lg"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <!-- Reports header -->
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Reports</h6>
      </li>

      <!-- Parking reports -->
      <li class="nav-item">
        <a class="nav-link" href="bwdates-report-ds.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-bullhorn text-dark text-lg"></i>
          </div>
          <span class="nav-link-text ms-1">Parking reports</span>
        </a>
      </li>

      <!-- User management header -->
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">User management</h6>
      </li>

      <!-- Admin/Users -->
      <li class="nav-item">
        <a class="nav-link" href="users.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-user-plus text-dark text-lg"></i>
          </div>
          <span class="nav-link-text ms-1">Admin/Users</span>
        </a>
      </li>

      <!-- Vehicle status header -->
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Vehicle status</h6>
      </li>

      <!-- Manage Category (click-to-open submenu) -->
      <li class="nav-item">
        <a class="nav-link" href="javascript:void(0);" onclick="toggleSubmenu('manage-category-submenu')" aria-expanded="false">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-bars text-dark text-lg"></i>
          </div>
          <span class="nav-link-text ms-1">Manage Category</span>
        </a>
        <ul class="pcoded-submenu" id="manage-category-submenu">
          <li class="nav-item">
            <a class="nav-link" href="addcategory.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-plus text-dark text-lg"></i>
              </div>
              <span class="nav-link-text ms-1">Add Category</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="manage-category.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-eye text-dark text-lg"></i>
              </div>
              <span class="nav-link-text ms-1">View Categories</span>
            </a>
          </li>
        </ul>
      </li>

      <!-- Add Vehicle (no submenu) -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="add-vehicle.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-plus text-dark text-lg"></i>
          </div>
          <span class="nav-link-text ms-1">Add Vehicle</span>
        </a>
      </li> -->

      <!-- Manage Vehicle In/Out (click-to-open submenu) -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="javascript:void(0);" onclick="toggleSubmenu('manage-vehicle-submenu')" aria-expanded="false">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-list-ul text-dark text-lg"></i>
          </div>
          <span class="nav-link-text ms-1">Manage Vehicle In/Out</span>
        </a>
        <ul class="pcoded-submenu" id="manage-vehicle-submenu">
          <li class="nav-item">
            <a class="nav-link" href="manage-incomingvehicle.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-car text-dark text-lg"></i>
              </div>
              <span class="nav-link-text ms-1">Incoming vehicle</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="manage-outgoingvehicle.php">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-car text-dark text-lg"></i>
              </div>
              <span class="nav-link-text ms-1">Outgoing vehicle</span>
            </a>
          </li>
        </ul>
      </li> -->

      <!-- Search Vehicle -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="search-vehicle.php">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-search text-dark text-lg"></i>
          </div>
          <span class="nav-link-text ms-1">Search Vehicle</span>
        </a>
      </li> -->
    </ul>
  </div>

  <div class="sidenav-footer mx-3">
    <a class="btn bg-gradient-primary mt-3 w-100" href="logout.php">Logout</a>
  </div>
</aside>

<!-- Styles: ensure submenu hidden by default and visible when .open is added -->
<style>
  .nav-link:hover {
    background-image: linear-gradient(310deg, rgba(121, 40, 202, 0.6) 0%, rgba(255, 0, 128, 0.6) 100%);
    border-radius: 18px;
  }

  /* Hide submenus by default */
  .pcoded-submenu {
    display: none;
    list-style: none;
    padding-left: 20px;
    margin: 6px 0 6px 0;
  }

  /* Show when open */
  .pcoded-submenu.open {
    display: block !important;
  }

  /* Small style tweaks for submenu links */
  .pcoded-submenu .nav-link {
    padding: 6px 10px;
    background-color: transparent;
    border-radius: 8px;
    margin: 4px 0;
  }
  .pcoded-submenu .nav-link:hover {
    background-color: rgba(0,0,0,0.03);
  }
</style>

<!-- Toggle JS -->
<script>
  function toggleSubmenu(id) {
    var submenu = document.getElementById(id);
    if (!submenu) return;

    // Close other submenus (optional)
    document.querySelectorAll('.pcoded-submenu').forEach(function(menu) {
      if (menu.id !== id) {
        menu.classList.remove('open');
      }
    });

    // Toggle the clicked one
    submenu.classList.toggle('open');
  }
</script>
