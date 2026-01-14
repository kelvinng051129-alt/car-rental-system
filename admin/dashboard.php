<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// Security Check
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 100px; /* Space for fixed header */
        }

        /* Welcome Card */
        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            text-align: center;
            border-bottom: 5px solid #f1c40f;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #eee;
        }
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .stat-content h3 { font-size: 3rem; font-weight: 800; margin-bottom: 0; color: #2c3e50; }
        .stat-content p { color: #95a5a6; margin: 0; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;}
        
        .icon-box {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        .bg-light-blue { background-color: #e3f2fd; color: #2196f3; }
        .bg-light-green { background-color: #e8f5e9; color: #4caf50; }
        .bg-light-orange { background-color: #fff3e0; color: #ff9800; }

        /* Quick Actions */
        .section-title { font-weight: 700; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; }
        .section-title i { margin-right: 10px; color: #f1c40f; }
        
        .action-btn {
            display: block;
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-decoration: none;
            color: #555;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            transition: all 0.3s;
            text-align: center;
        }
        .action-btn i { display: block; font-size: 2rem; margin-bottom: 10px; color: #2c3e50; transition: 0.3s;}
        .action-btn:hover { background: #2c3e50; color: white; transform: translateY(-5px); }
        .action-btn:hover i { color: #f1c40f; }

    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container">
        
        <div class="welcome-card">
            <h1 style="font-weight: 800; color: #2c3e50;">Welcome back, Admin!</h1>
            <p class="text-muted" style="font-size: 1.1rem;">Manage your vehicles, bookings, and users from one place.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <?php 
                        $sql ="SELECT id from tblusers";
                        $query = $dbh -> prepare($sql); $query->execute(); $regusers=$query->rowCount();
                        ?>
                        <h3><?php echo htmlentities($regusers);?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon-box bg-light-blue"><i class="fa fa-users"></i></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <?php 
                        $sql1 ="SELECT id from tblvehicles";
                        $query1 = $dbh -> prepare($sql1); $query1->execute(); $totalvehicle=$query1->rowCount();
                        ?>
                        <h3><?php echo htmlentities($totalvehicle);?></h3>
                        <p>Total Vehicles</p>
                    </div>
                    <div class="icon-box bg-light-orange"><i class="fa fa-car"></i></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <?php 
                        $sql2 ="SELECT id from tblbooking";
                        $query2 = $dbh -> prepare($sql2); $query2->execute(); $bookings=$query2->rowCount();
                        ?>
                        <h3><?php echo htmlentities($bookings);?></h3>
                        <p>Total Bookings</p>
                    </div>
                    <div class="icon-box bg-light-green"><i class="fa fa-calendar-check"></i></div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h4 class="section-title"><i class="fa fa-bolt"></i> Quick Actions</h4>
            </div>
            
            <div class="col-md-3 col-6">
                <a href="post-avehicle.php" class="action-btn">
                    <i class="fa fa-plus-circle"></i> Post Vehicle
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="manage-bookings.php" class="action-btn">
                    <i class="fa fa-check-square"></i> Manage Bookings
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="create-brand.php" class="action-btn">
                    <i class="fa fa-tags"></i> Add Brand
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="reg-users.php" class="action-btn">
                    <i class="fa fa-users"></i> View Users
                </a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>