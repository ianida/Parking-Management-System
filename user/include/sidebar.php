<style>
  .nav-link:hover{
    /* background-image: linear-gradient(10deg, #7928CA 0%, #FF0080 100%); */
    background-image: linear-gradient(310deg, rgba(121, 40, 202, 0.6) 0%, rgba(255, 0, 128, 0.6) 100%);
    border-radius: 18px;
  }
  
</style>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
    <a class="navbar-brand m-0" href="index.php">
    <h4>EasyPark</h4>  
    </a>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      
    <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link  active" href="index.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-home text-white text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Services</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link  " href="addspace2.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-plus text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Add Space</span>
          </a>
          <a class="nav-link  " href="searchspace.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-search text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Search Space</span>

          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="setting.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-cogs text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="aboutus.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fa fa-file text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">About Us</span>
          </a>
        </li>


    </div>
    <div class="sidenav-footer mx-3 ">
      <a class="btn bg-gradient-primary mt-3 w-100" href="logout.php">
        Logout
    </a>
    </div>
  </aside>