<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php" style="font-size: 1.5rem; color: #f1c40f;">
      <i class="fa fa-car"></i> CarRental Portal
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="userNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="car-listing.php">Find a Car</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        
        <?php if(strlen($_SESSION['login'])==0) { ?>
            <li class="nav-item ms-3">
                <a class="btn btn-outline-warning btn-sm" href="login.php">Login / Register</a>
            </li>
        <?php } else { ?>
            <li class="nav-item ms-3 dropdown">
                <a class="nav-link dropdown-toggle text-warning" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fa fa-user-circle"></i> <?php echo htmlentities($_SESSION['fname']);?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="my-booking.php">My Bookings</a></li>
                    <li><a class="dropdown-item" href="profile.php">Profile Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<style> body { padding-top: 70px; } </style>