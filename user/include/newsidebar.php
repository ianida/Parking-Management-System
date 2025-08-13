<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<style>
    .nav-link:hover {
      background-image: linear-gradient(310deg, rgba(121, 40, 202, 0.6) 0%, rgba(255, 0, 128, 0.6) 100%);
      border-radius: 18px;
    }

    .submenu {
      display: none;
      list-style: none;
      padding-left: 80px;
    }

    .submenu .nav-link {
      padding: 5px 10px;
      background-color: #d1d1d6;
      border-radius: 10px;
    }

    .submenu .nav-link {
      padding: 5px;
    }
    #submenu-item{
        height: 35px;
        padding-left: 5px;
        margin-top:5px;
    }
  </style>
<body>
  
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
    <div class="sidenav-header">
      <a class="navbar-brand m-0" href="index.php">
        <h4>EasyPark</h4>
      </a>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" >
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-home text-white text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Services</h6>
        </li>

        <!-- Add Space submenu -->
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0);" onclick="toggleSubmenu('add-space-submenu')">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-plus text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Add Space</span>
          </a>
          <ul class="submenu" id="add-space-submenu">
            <li><a class="nav-link" href="addspace2.php" id="submenu-item">Add Space</a></li>
            <li><a class="nav-link" href="myspace.php" id="submenu-item">My Space</a></li>
          </ul>
        </li>

        <!-- Add Vehicle submenu -->
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0);" onclick="toggleSubmenu('add-vehicle-submenu')">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-car text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Add Vehicle</span>
          </a>
          <ul class="submenu" id="add-vehicle-submenu">
            <li><a class="nav-link" href="add_vehicle.php" id="submenu-item">Add Vehicle</a></li>
            <li><a class="nav-link" href="manage_vehicles.php" id="submenu-item">Manage Vehicles</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0);" onclick="toggleSubmenu('search-space-submenu')">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-search text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Search</span>
          </a>
          <ul class="submenu" id="search-space-submenu">
            <li><a class="nav-link" href="searchspace.php" id="submenu-item">Search Space</a></li>
            <li><a class="nav-link" href="bookedspace.php" id="submenu-item">Booked Space</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="setting.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-cogs text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="aboutus.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-file text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">About Us</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3">
      <a class="btn bg-gradient-primary mt-3 w-100" href="logout.php">
        Logout
      </a>
    </div>
  </aside>

  <script>
    function toggleSubmenu(id) {
      var submenu = document.getElementById(id);
      if (submenu.style.display === "none" || submenu.style.display === "") {
        submenu.style.display = "block";
      } else {
        submenu.style.display = "none";
      }
    }
  </script>
</body>
