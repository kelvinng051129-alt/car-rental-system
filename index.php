<?php 
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Portal | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Hero Section Styling */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 80vh; 
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        .hero-title { font-size: 3.5rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }
        .hero-subtitle { font-size: 1.2rem; margin-bottom: 30px; color: #f1f1f1; }
        .btn-hero { background-color: #f1c40f; color: #2c3e50; padding: 12px 30px; font-weight: bold; border-radius: 50px; text-decoration: none; transition: 0.3s; border: none;}
        .btn-hero:hover { background-color: #d4ac0d; color: white; transform: scale(1.05); }

        /* Car Card Styling */
        .car-card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: 0.3s; overflow: hidden; }
        .car-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .car-img-top { height: 220px; object-fit: cover; width: 100%; }
        .card-body { padding: 20px; }
        .car-price { color: #e74c3c; font-weight: 700; font-size: 1.2rem; }
        .car-meta { font-size: 0.85rem; color: #7f8c8d; margin-top: 10px; }
        .car-meta i { color: #f1c40f; width: 20px; text-align: center; }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Find Your Best Drive</h1>
            <p class="hero-subtitle">Premium Cars. Unlimited Miles. Unforgettable Moments.</p>
            <a href="car-listing.php" class="btn-hero">Browse Cars <i class="fa fa-arrow-right"></i></a>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: #2c3e50;">Recent Vehicles</h2>
                <p class="text-muted">Check out the latest additions to our fleet.</p>
                <hr style="width: 80px; height: 3px; background: #f1c40f; margin: 10px auto; border:none;">
            </div>

            <div class="row g-4">
                
                <?php 
                $sql = "SELECT tblvehicles.VehiclesTitle,tblbrands.BrandName,tblvehicles.PricePerDay,tblvehicles.FuelType,tblvehicles.ModelYear,tblvehicles.id,tblvehicles.SeatingCapacity,tblvehicles.Vimage1 from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand order by tblvehicles.id desc limit 3";
                $query = $dbh -> prepare($sql);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);

                if($query->rowCount() > 0)
                {
                    foreach($results as $result)
                    {  
                ?>
                    <div class="col-md-4">
                        <div class="card car-card h-100">
                            <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="car-img-top" alt="Car Image" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                            
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h5>
                                <p class="car-price">RM <?php echo htmlentities($result->PricePerDay);?> / Day</p>
                                
                                <div class="car-meta d-flex justify-content-between">
                                    <span><i class="fa fa-gas-pump"></i> <?php echo htmlentities($result->FuelType);?></span>
                                    <span><i class="fa fa-calendar"></i> <?php echo htmlentities($result->ModelYear);?></span>
                                    <span><i class="fa fa-chair"></i> <?php echo htmlentities($result->SeatingCapacity);?> Seats</span>
                                </div>
                                
                                <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>" class="btn btn-dark w-100 mt-3">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php }} else { ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No vehicles available at the moment.</p>
                    </div>
                <?php } ?>

            </div>
        </div>
    </section>

    <?php include('includes/footer.php');?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>