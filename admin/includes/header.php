<style>
    body { padding-top: 0 !important; }
    .container { margin-top: 30px; }
</style>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2c3e50; padding: 15px 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php" style="font-size: 1.5rem; color: #fff;">
      <i class="fa fa-car"></i> Car Rental Admin
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="brandsDrop" role="button" data-bs-toggle="dropdown">
            Brands
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="create-brand.php">Create Brand</a></li>
            <li><a class="dropdown-item" href="manage-brands.php">Manage Brands</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="vehiclesDrop" role="button" data-bs-toggle="dropdown">
            Vehicles
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="post-avehicle.php">Post a Vehicle</a></li>
            <li><a class="dropdown-item" href="manage-vehicles.php">Manage Vehicles</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="manage-bookings.php">Bookings</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-warning fw-bold" href="manage-payments.php">
            <i class="fa fa-dollar-sign"></i> Payments
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="reg-users.php">Users</a>
        </li>

        <li class="nav-item ms-3">
          <div class="dropdown">
            <a class="btn btn-outline-light btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa fa-user-circle"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="change-password.php">Change Password</a></li>
              <li><hr class="dropdown-divider"></li>
              
              <li>
                  <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event)">
                      Logout
                  </a>
              </li>

            </ul>
          </div>
        </li>

      </ul>
    </div>
  </div>
</nav>

<style>
  .navbar-nav .nav-link { font-size: 0.95rem; margin-left: 10px; transition: 0.3s; color: rgba(255,255,255,0.8); }
  .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #f1c40f !important; transform: translateY(-2px); }
  .dropdown-menu { border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 8px; }
  .dropdown-item:hover { background-color: #f8f9fa; color: #2c3e50; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmLogout(e) {
        // Prevent the default link behavior (immediate redirection)
        e.preventDefault();

        // Show SweetAlert Confirmation Dialog
        Swal.fire({
            title: 'Ready to Leave?',
            text: "You will be logged out of the system.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', // Red for Logout
            cancelButtonColor: '#2c3e50',  // Dark for Cancel
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel',
            heightAuto: false // Prevents page jumping
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout.php if user confirms
                window.location.href = 'logout.php';
            }
        });
    }
</script>