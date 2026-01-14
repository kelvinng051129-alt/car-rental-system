<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <i class="fa fa-car"></i> Car Rental Admin
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Brands</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="create-brand.php">Create Brand</a></li>
                        <li><a class="dropdown-item" href="manage-brands.php">Manage Brands</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Vehicles</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="post-avehicle.php">Post a Vehicle</a></li>
                        <li><a class="dropdown-item" href="manage-vehicles.php">Manage Vehicles</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="manage-bookings.php">Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="reg-users.php">Users</a></li>

                <li class="nav-item ms-3">
                    <div class="d-flex align-items-center">
                        <span class="text-light me-3 small">Hello, <?php echo $_SESSION['alogin']; ?></span>
                        <a href="logout.php" class="btn btn-sm btn-danger rounded-pill px-3">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Header Styles */
    .navbar-custom {
        background-color: #2c3e50; /* Dark Blue-Grey Background */
        border-bottom: 4px solid #f1c40f; /* Yellow Accent Line */
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 10px 0;
    }
    .navbar-brand { font-weight: 700; color: #fff !important; font-size: 1.4rem; }
    .nav-link { color: #ecf0f1 !important; font-weight: 500; margin: 0 5px; transition: 0.3s; }
    .nav-link:hover { color: #f1c40f !important; }
    .dropdown-menu { border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 3px solid #f1c40f; }
</style>