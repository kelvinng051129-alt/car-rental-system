<?php 
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find a Car | Car Rental Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
        }

        /* --- HERO BANNER (NEW IMAGE) --- */
        .page-header {
            /* New Image: A premium dark car on a road (High Quality Unsplash) 
               Vibe: Moody, Luxurious, Travel
            */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1503376763036-066120622c74?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 400px; /* Made it slightly taller for better visual impact */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }
        .page-header h1 { 
            font-weight: 700; 
            font-size: 3.5rem; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5); 
        }
        .page-header p { 
            font-size: 1.2rem; 
            opacity: 0.9; 
            font-weight: 300; 
            text-shadow: 0 1px 5px rgba(0,0,0,0.5);
        }

        /* --- FLOATING SEARCH BAR --- */
        .search-wrapper {
            margin-top: -80px; /* Pulls the box up */
            margin-bottom: 40px;
            position: relative;
            z-index: 10;
        }
        .search-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        /* --- CAR CARD DESIGN --- */
        .car-card {
            background: white;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
        }
        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        /* Image Container */
        .img-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        /* Points to admin/img/vehicleimages/ */
        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }
        .car-card:hover .img-container img { transform: scale(1.08); }

        /* Price Tag Badge */
        .price-tag {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: #f1c40f; /* AMG Gold */
            color: #2c3e50;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        /* Card Content */
        .card-body { padding: 25px; }
        .car-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 5px; color: #2c3e50; }
        .brand-name { color: #7f8c8d; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-bottom: 15px; display: block; }

        /* Icons Grid */
        .spec-grid {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .spec-item {
            text-align: center;
            font-size: 0.8rem;
            color: #555;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .spec-item i { color: #2c3e50; font-size: 1rem; }

        /* Button */
        .btn-detail {
            width: 100%;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-detail:hover { background: #f1c40f; color: #2c3e50; }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="page-header">
        <div class="container">
            <h1>Find Your Car</h1>
            <p>Browse our extensive fleet available for rent</p>
        </div>
    </div>

    <div class="container search-wrapper">
        <div class="search-box">
            <form action="search-car-result.php" method="post">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted text-uppercase small">Select Brand</label>
                        <select class="form-select" name="brand">
                            <option selected>All Brands</option>
                            <?php 
                            // Fetch Brands from database
                            $sql = "SELECT id, BrandName FROM tblbrands";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            if($query->rowCount() > 0) {
                                foreach($results as $result) { ?>  
                                <option value="<?php echo htmlentities($result->id);?>">
                                    <?php echo htmlentities($result->BrandName);?>
                                </option>
                            <?php }} ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted text-uppercase small">Fuel Type</label>
                        <select class="form-select" name="fueltype">
                            <option selected>All Fuel Types</option>
                            <?php 
                            // Fetch only existing fuel types
                            $sql_fuel = "SELECT DISTINCT FuelType FROM tblvehicles WHERE FuelType IS NOT NULL";
                            $query_fuel = $dbh -> prepare($sql_fuel);
                            $query_fuel->execute();
                            $results_fuel=$query_fuel->fetchAll(PDO::FETCH_OBJ);
                            if($query_fuel->rowCount() > 0) {
                                foreach($results_fuel as $row) { ?>  
                                <option value="<?php echo htmlentities($row->FuelType);?>">
                                    <?php echo htmlentities($row->FuelType);?>
                                </option>
                            <?php }} ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-detail mt-0">
                            <i class="fa fa-search"></i> SEARCH NOW
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="pb-5">
        <div class="container">
            <div class="row g-4">
                
                <?php 
                // SQL Query: Fetch Vehicles + Brand Name
                $sql = "SELECT tblvehicles.*, tblbrands.BrandName, tblbrands.id as bid 
                        FROM tblvehicles 
                        JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
                        ORDER BY tblvehicles.id DESC";
                        
                $query = $dbh -> prepare($sql);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);

                if($query->rowCount() > 0)
                {
                    foreach($results as $result)
                    {  
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card car-card">
                        <div class="img-container">
                            <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="Car Image" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                            <div class="price-tag">
                                RM <?php echo htmlentities($result->PricePerDay);?> / Day
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="car-title">
                                <?php echo htmlentities($result->VehiclesTitle);?>
                            </h5>
                            <span class="brand-name">
                                <i class="fa fa-tag"></i> <?php echo htmlentities($result->BrandName);?>
                            </span>

                            <div class="spec-grid">
                                <div class="spec-item">
                                    <i class="fa fa-calendar-alt"></i>
                                    <span><?php echo htmlentities($result->ModelYear);?></span>
                                </div>
                                <div class="spec-item">
                                    <i class="fa fa-gas-pump"></i>
                                    <span><?php echo htmlentities($result->FuelType);?></span>
                                </div>
                                <div class="spec-item">
                                    <i class="fa fa-chair"></i>
                                    <span><?php echo htmlentities($result->SeatingCapacity);?> Seats</span>
                                </div>
                            </div>

                            <p class="text-muted small mb-4">
                                <?php echo substr($result->VehiclesOverview,0,70);?>...
                            </p>

                            <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>" class="btn btn-detail">
                                View Details <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    } 
                } else { 
                ?>
                    <div class="col-12 text-center my-5">
                        <div class="p-5 bg-white rounded shadow-sm border">
                            <h3 class="text-muted"><i class="fa fa-car-crash display-4 mb-3"></i><br>No Vehicles Found</h3>
                            <p>We couldn't find any cars listed at the moment.</p>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </section>

    <?php include('includes/footer.php');?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>