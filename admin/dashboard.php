<?php
session_start();
error_reporting(0);
// Include the database configuration file
// Note: using '../' to go up one level to find the includes folder
include('../includes/config.php');

// --- Security Check ---
// If the session variable 'alogin' is empty, it means the user is not logged in.
// Redirect them back to the login page.
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{
    // If logged in, show the dashboard
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Portal | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-box { padding: 20px; border-radius: 5px; color: white; margin-bottom: 20px; }
        .bg-primary { background-color: #007bff!important; }
        .bg-success { background-color: #28a745!important; }
        .bg-warning { background-color: #ffc107!important; }
        .stat-digit { font-size: 3rem; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark p-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Car Rental | Admin Panel</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Welcome, <?php echo $_SESSION['alogin']; ?>
                </span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Dashboard Overview</h2>
        
        <div class="row">
            
            <div class="col-md-4">
                <div class="card card-box bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php 
                                // SQL Query: Count total users in tblusers table
                                $sql ="SELECT id from tblusers";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $regusers=$query->rowCount();
                                ?>
                                <div class="stat-digit"><?php echo htmlentities($regusers);?></div>
                                <div class="card-text">Reg Users</div>
                            </div>
                            <i class="fa fa-users fa-3x"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="text-white text-decoration-none">View Details &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-box bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php 
                                // SQL Query: Count total vehicles in tblvehicles table
                                $sql1 ="SELECT id from tblvehicles";
                                $query1 = $dbh -> prepare($sql1);
                                $query1->execute();
                                $totalvehicle=$query1->rowCount();
                                ?>
                                <div class="stat-digit"><?php echo htmlentities($totalvehicle);?></div>
                                <div class="card-text">Vehicles Listed</div>
                            </div>
                            <i class="fa fa-car fa-3x"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="text-white text-decoration-none">Manage Vehicles &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-box bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <?php 
                                // SQL Query: Count total bookings in tblbooking table
                                $sql2 ="SELECT id from tblbooking";
                                $query2 = $dbh -> prepare($sql2);
                                $query2->execute();
                                $bookings=$query2->rowCount();
                                ?>
                                <div class="stat-digit"><?php echo htmlentities($bookings);?></div>
                                <div class="card-text">Total Bookings</div>
                            </div>
                            <i class="fa fa-calendar-check fa-3x"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="text-white text-decoration-none">View Bookings &rarr;</a>
                    </div>
                </div>
            </div>

        </div> </div>

</body>
</html>

<?php } ?>