<?php 
session_start();
include('includes/config.php');
error_reporting(0);

if(isset($_POST['submit']))
{
    $fromdate=$_POST['fromdate'];
    $todate=$_POST['todate']; 
    $message=$_POST['message'];
    $useremail=$_SESSION['login'];
    $status=0;
    $vhid=$_GET['vhid'];
    
    // Check if user is logged in
    if(strlen($_SESSION['login'])==0)
    {   
        echo "<script>alert('Please login to book a car.');</script>";
        echo "<script>window.location.href='index.php';</script>";
    }
    else
    {
        $sql="INSERT INTO tblbooking(userEmail,VehicleId,FromDate,ToDate,message,Status) VALUES(:useremail,:vhid,:fromdate,:todate,:message,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
        $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
        $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
        $query->bindParam(':todate',$todate,PDO::PARAM_STR);
        $query->bindParam(':message',$message,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId)
        {
            echo "<script>alert('Booking successful! We will contact you shortly.');</script>";
        }
        else 
        {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Details | Premium Fleet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000000; /* Pure Black Background */
            color: #fff;
        }

        /* --- 1. HERO CAROUSEL SECTION --- */
        .detail-hero {
            height: 600px;
            position: relative;
            background-color: #000;
        }
        
        /* Carousel Styling */
        .carousel, .carousel-inner, .carousel-item {
            height: 100%;
        }
        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }

        /* Custom Gold Arrows */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(78%) sepia(26%) saturate(1008%) hue-rotate(359deg) brightness(92%) contrast(88%); /* Gold Color */
            width: 3rem;
            height: 3rem;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 8%; /* Wider click area */
            opacity: 0.7;
            z-index: 10;
        }
        .carousel-control-prev:hover, .carousel-control-next:hover { opacity: 1; }

        /* Overlay Text */
        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to top, #000000, transparent);
            padding: 150px 0 50px 0;
            pointer-events: none; /* Allows clicking through to carousel controls */
            z-index: 5;
        }
        .car-title-large {
            font-family: 'Playfair Display', serif;
            font-size: 3.8rem;
            color: #fff;
            text-shadow: 0 5px 20px rgba(0,0,0,0.9);
            margin-bottom: 20px;
        }
        .car-brand-sub {
            color: #d4af37; /* Champagne Gold */
            font-size: 1.2rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 600;
            text-shadow: 0 2px 10px rgba(0,0,0,0.9);
        }

        /* --- 2. MAIN CONTENT LAYOUT --- */
        .content-wrapper {
            margin-top: -60px; 
            position: relative;
            z-index: 10;
        }

        /* Detail Card */
        .detail-card {
            background: #111; /* Dark Grey */
            border: 1px solid #222;
            padding: 35px;
            border-radius: 0; 
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        
        .section-heading {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            border-bottom: 1px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
            color: #fff;
        }

        /* Specs Boxes */
        .specs-box {
            background: #1a1a1a;
            padding: 25px 15px;
            text-align: center;
            border: 1px solid #333;
            transition: 0.3s;
            height: 100%;
        }
        .specs-box:hover { border-color: #d4af37; background: #222; }
        .specs-box i { font-size: 1.8rem; color: #d4af37; margin-bottom: 15px; display: block; }
        .specs-box span { display: block; font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .specs-box strong { font-size: 1.1rem; color: #fff; font-weight: 600; }

        .desc-text { color: #ccc; line-height: 1.9; font-weight: 300; font-size: 1rem; }

        /* Accessories */
        .accessories-list { list-style: none; padding: 0; display: flex; flex-wrap: wrap; margin-top: 20px; }
        .accessories-list li { width: 50%; margin-bottom: 15px; color: #bbb; display: flex; align-items: center; font-size: 0.95rem; }
        .accessories-list li i { margin-right: 12px; font-size: 1.1rem; }
        .check-icon { color: #27ae60 !important; }
        .times-icon { color: #555 !important; opacity: 0.4; }

        /* Thumbnail Grid (Click to Zoom) */
        .thumb-grid { display: flex; gap: 15px; margin-top: 30px; }
        .thumb-img { 
            width: 120px; 
            height: 80px; 
            object-fit: cover; 
            border: 1px solid #333; 
            cursor: zoom-in; /* Magnifier Icon */
            opacity: 0.6; 
            transition: 0.3s; 
        }
        .thumb-img:hover { 
            opacity: 1; 
            border-color: #d4af37; 
            transform: scale(1.05); 
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        }

        /* Booking Sidebar */
        .booking-sidebar {
            background: #111;
            border: 1px solid #d4af37;
            padding: 35px;
            border-radius: 0;
            position: sticky;
            top: 100px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8);
        }
        
        .price-display {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #333;
            padding-bottom: 25px;
        }
        .main-price { font-size: 2.8rem; color: #d4af37; font-family: 'Playfair Display', serif; font-weight: 700; line-height: 1; }
        .price-label { color: #888; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px; margin-top: 10px; display: block; }

        .deposit-badge {
            background: rgba(212, 175, 55, 0.05);
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: #d4af37;
            padding: 12px;
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .form-control-dark {
            background: #222;
            border: 1px solid #333;
            color: #fff;
            padding: 15px;
            border-radius: 0;
        }
        .form-control-dark:focus { background: #000; color: #fff; border-color: #d4af37; box-shadow: none; }
        label { color: #888; font-size: 0.75rem; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; }

        .btn-book-now {
            background: linear-gradient(45deg, #d4af37, #c5a028);
            color: #000;
            width: 100%;
            padding: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            margin-top: 20px;
            transition: 0.3s;
            border-radius: 0;
        }
        .btn-book-now:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); background: #fff; }
        
        .login-link-btn {
            display: block;
            text-align: center;
            background: #333;
            color: #fff;
            padding: 15px;
            text-decoration: none;
            margin-top: 15px;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .login-link-btn:hover { background: #d4af37; color: #000; }

        /* --- 3. LIGHTBOX MODAL --- */
        .image-modal {
            display: none;
            position: fixed; 
            z-index: 9999; 
            padding-top: 50px; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.95);
            backdrop-filter: blur(5px);
        }
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 1200px;
            max-height: 85vh;
            object-fit: contain;
            border: 1px solid #333;
            box-shadow: 0 0 50px rgba(0,0,0,1);
            animation: zoomIn 0.3s;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #d4af37;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }
        .close-btn:hover { color: #fff; }
        @keyframes zoomIn { from {transform:scale(0.8); opacity:0} to {transform:scale(1); opacity:1} }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <?php 
    $vhid=intval($_GET['vhid']);
    $sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.id=:vhid";
    $query = $dbh -> prepare($sql);
    $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0)
    {
        foreach($results as $result)
        {  
            $_SESSION['brndid']=$result->bid;  
    ?>  

    <div class="detail-hero">
        <div id="carCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                
                <div class="carousel-item active">
                    <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="Main View">
                </div>

                <?php if($result->Vimage2!="") { ?>
                <div class="carousel-item">
                    <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" alt="Side View">
                </div>
                <?php } ?>

                <?php if($result->Vimage3!="") { ?>
                <div class="carousel-item">
                    <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage3);?>" alt="Interior View">
                </div>
                <?php } ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="hero-overlay">
            <div class="container">
                <div class="car-brand-sub"><?php echo htmlentities($result->BrandName);?></div>
                <h1 class="car-title-large"><?php echo htmlentities($result->VehiclesTitle);?></h1>
            </div>
        </div>
    </div>

    <div class="container content-wrapper">
        <div class="row">
            
            <div class="col-lg-8">
                
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="specs-box">
                            <i class="fa fa-calendar"></i>
                            <span>Year Model</span>
                            <strong><?php echo htmlentities($result->ModelYear);?></strong>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="specs-box">
                            <i class="fa fa-gas-pump"></i>
                            <span>Fuel Type</span>
                            <strong><?php echo htmlentities($result->FuelType);?></strong>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="specs-box">
                            <i class="fa fa-chair"></i>
                            <span>Seats</span>
                            <strong><?php echo htmlentities($result->SeatingCapacity);?></strong>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="specs-box">
                            <i class="fa fa-cogs"></i>
                            <span>Transmission</span>
                            <strong><?php echo htmlentities($result->Transmission);?></strong>
                        </div>
                    </div>
                </div> <div class="detail-card">
                    <h3 class="section-heading">Vehicle Overview</h3>
                    <p class="desc-text"><?php echo htmlentities($result->VehiclesOverview);?></p>
                    
                    <div class="thumb-grid">
                        <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="thumb-img" onclick="openLightbox(this.src)">
                        <?php if($result->Vimage2!="") { ?>
                             <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" class="thumb-img" onclick="openLightbox(this.src)">
                        <?php } ?>
                        <?php if($result->Vimage3!="") { ?>
                             <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage3);?>" class="thumb-img" onclick="openLightbox(this.src)">
                        <?php } ?>
                    </div>
                    <p class="small text-muted mt-3"><i class="fa fa-search-plus"></i> Click thumbnails to zoom in.</p>
                </div>

                <div class="detail-card">
                    <h3 class="section-heading">Features & Accessories</h3>
                    <ul class="accessories-list">
                        <li><i class="<?php echo ($result->AirConditioner==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Air Conditioner</li>
                        <li><i class="<?php echo ($result->PowerDoorLocks==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Power Door Locks</li>
                        <li><i class="<?php echo ($result->AntiLockBrakingSystem==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> ABS System</li>
                        <li><i class="<?php echo ($result->PowerSteering==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Power Steering</li>
                        <li><i class="<?php echo ($result->PowerWindows==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Power Windows</li>
                        <li><i class="<?php echo ($result->CDPlayer==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Bluetooth/Audio</li>
                        <li><i class="<?php echo ($result->LeatherSeats==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Leather Seats</li>
                        <li><i class="<?php echo ($result->CentralLocking==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Central Locking</li>
                        <li><i class="<?php echo ($result->CrashSensor==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Crash Sensor</li>
                        <li><i class="<?php echo ($result->DriverAirbag==1) ? 'fa fa-check-circle check-icon' : 'fa fa-times-circle times-icon';?>"></i> Driver Airbag</li>
                    </ul>
                </div>

            </div> <div class="col-lg-4">
                <div class="booking-sidebar">
                    
                    <div class="price-display">
                        <div class="main-price">RM <?php echo htmlentities($result->PricePerDay);?></div>
                        <span class="price-label">Daily Rate</span>
                    </div>

                    <div class="deposit-badge">
                        <i class="fa fa-shield-alt"></i> Security Deposit: 
                        <strong>RM <?php echo htmlentities($result->SecurityDeposit);?></strong>
                        <div style="font-size: 0.75rem; margin-top: 5px; opacity: 0.8;">(Refundable upon return)</div>
                    </div>

                    <?php if($result->PricePerWeek > 0) { ?>
                        <div class="d-flex justify-content-between text-secondary mb-2 small" style="border-bottom: 1px solid #333; padding-bottom: 8px;">
                            <span>Weekly Rate (7 Days):</span>
                            <span class="text-white">RM <?php echo htmlentities($result->PricePerWeek);?></span>
                        </div>
                    <?php } ?>
                    <?php if($result->PricePerMonth > 0) { ?>
                        <div class="d-flex justify-content-between text-secondary mb-4 small">
                            <span>Monthly Rate (30 Days):</span>
                            <span class="text-white">RM <?php echo htmlentities($result->PricePerMonth);?></span>
                        </div>
                    <?php } ?>

                    <h4 class="text-white mb-4 mt-4" style="font-family: 'Playfair Display'; text-align: center;">Book This Vehicle</h4>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label>From Date</label>
                            <input type="date" class="form-control form-control-dark" name="fromdate" required>
                        </div>
                        <div class="mb-3">
                            <label>To Date</label>
                            <input type="date" class="form-control form-control-dark" name="todate" required>
                        </div>
                        <div class="mb-3">
                            <label>Message (Optional)</label>
                            <textarea rows="3" class="form-control form-control-dark" name="message" placeholder="Any special requests?"></textarea>
                        </div>
                        
                        <?php if($_SESSION['login']) { ?>
                            <button type="submit" class="btn btn-book-now" name="submit">Confirm Booking</button>
                        <?php } else { ?>
                            <a href="login.php" class="login-link-btn">Login to Book</a>
                        <?php } ?>
                    </form>

                </div>
            </div> </div>
    </div>

    <div id="imgModal" class="image-modal" onclick="closeModal()">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="fullImage">
    </div>

    <?php }} ?>

    <?php include('includes/footer.php');?>
    <?php include('includes/login.php');?>
    <?php include('includes/registration.php');?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 1. Function to Open Lightbox (Zoom)
        function openLightbox(src) {
            var modal = document.getElementById("imgModal");
            var modalImg = document.getElementById("fullImage");
            
            modal.style.display = "block";
            modalImg.src = src; 
        }

        // 2. Function to Close Modal
        function closeModal() {
            var modal = document.getElementById("imgModal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>