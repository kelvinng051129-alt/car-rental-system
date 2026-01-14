<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// Security Check: Redirect to login if not logged in
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else
{ 
    if(isset($_POST['submit']))
    {
        $vehicletitle = $_POST['vehicletitle'];
        $brand = $_POST['brandname'];
        $vehicleoverview = $_POST['vehicalorcview'];
        $priceperday = $_POST['priceperday'];
        
        // --- Pricing Logic: Receive Weekly and Monthly Prices ---
        // If empty, default to 0 to prevent SQL errors
        $priceperweek = !empty($_POST['priceperweek']) ? $_POST['priceperweek'] : 0;
        $pricepermonth = !empty($_POST['pricepermonth']) ? $_POST['pricepermonth'] : 0;

        $fueltype = $_POST['fueltype'];
        $modelyear = $_POST['modelyear'];
        $seatingcapacity = $_POST['seatingcapacity'];
        
        // Image Processing
        $vimage1 = $_FILES["img1"]["name"];
        $vimage2 = $_FILES["img2"]["name"];
        $vimage3 = $_FILES["img3"]["name"];

        // Accessories Processing (Checkbox Logic: 1 if checked, 0 if not)
        $airconditioner = isset($_POST['airconditioner']) ? 1 : 0;
        $powerdoorlocks = isset($_POST['powerdoorlocks']) ? 1 : 0;
        $antilockbrakingsys = isset($_POST['antilockbrakingsys']) ? 1 : 0;
        $brakeassist = isset($_POST['brakeassist']) ? 1 : 0;
        $powersteering = isset($_POST['powersteering']) ? 1 : 0;
        $driverairbag = isset($_POST['driverairbag']) ? 1 : 0;
        $passengerairbag = isset($_POST['passengerairbag']) ? 1 : 0;
        $powerwindow = isset($_POST['powerwindow']) ? 1 : 0;
        $cdplayer = isset($_POST['cdplayer']) ? 1 : 0;
        $centrallocking = isset($_POST['centrallocking']) ? 1 : 0;
        $crashcensor = isset($_POST['crashcensor']) ? 1 : 0;
        $leatherseats = isset($_POST['leatherseats']) ? 1 : 0;

        // Upload Images to Server Folder
        move_uploaded_file($_FILES["img1"]["tmp_name"], "img/vehicleimages/".$_FILES["img1"]["name"]);
        move_uploaded_file($_FILES["img2"]["tmp_name"], "img/vehicleimages/".$_FILES["img2"]["name"]);
        move_uploaded_file($_FILES["img3"]["tmp_name"], "img/vehicleimages/".$_FILES["img3"]["name"]);

        // --- SQL Query: Insert all data including new Price columns ---
        $sql = "INSERT INTO tblvehicles(VehiclesTitle,VehiclesBrand,VehiclesOverview,PricePerDay,PricePerWeek,PricePerMonth,FuelType,ModelYear,SeatingCapacity,Vimage1,Vimage2,Vimage3,AirConditioner,PowerDoorLocks,AntiLockBrakingSystem,BrakeAssist,PowerSteering,DriverAirbag,PassengerAirbag,PowerWindows,CDPlayer,CentralLocking,CrashSensor,LeatherSeats) VALUES(:vehicletitle,:brand,:vehicleoverview,:priceperday,:priceperweek,:pricepermonth,:fueltype,:modelyear,:seatingcapacity,:vimage1,:vimage2,:vimage3,:airconditioner,:powerdoorlocks,:antilockbrakingsys,:brakeassist,:powersteering,:driverairbag,:passengerairbag,:powerwindow,:cdplayer,:centrallocking,:crashcensor,:leatherseats)";
        
        $query = $dbh->prepare($sql);
        
        // Bind Basic Parameters
        $query->bindParam(':vehicletitle',$vehicletitle,PDO::PARAM_STR);
        $query->bindParam(':brand',$brand,PDO::PARAM_STR);
        $query->bindParam(':vehicleoverview',$vehicleoverview,PDO::PARAM_STR);
        $query->bindParam(':priceperday',$priceperday,PDO::PARAM_STR);
        
        // Bind New Pricing Parameters
        $query->bindParam(':priceperweek',$priceperweek,PDO::PARAM_STR);
        $query->bindParam(':pricepermonth',$pricepermonth,PDO::PARAM_STR);

        $query->bindParam(':fueltype',$fueltype,PDO::PARAM_STR);
        $query->bindParam(':modelyear',$modelyear,PDO::PARAM_STR);
        $query->bindParam(':seatingcapacity',$seatingcapacity,PDO::PARAM_STR);
        $query->bindParam(':vimage1',$vimage1,PDO::PARAM_STR);
        $query->bindParam(':vimage2',$vimage2,PDO::PARAM_STR);
        $query->bindParam(':vimage3',$vimage3,PDO::PARAM_STR);
        $query->bindParam(':airconditioner',$airconditioner,PDO::PARAM_STR);
        $query->bindParam(':powerdoorlocks',$powerdoorlocks,PDO::PARAM_STR);
        $query->bindParam(':antilockbrakingsys',$antilockbrakingsys,PDO::PARAM_STR);
        $query->bindParam(':brakeassist',$brakeassist,PDO::PARAM_STR);
        $query->bindParam(':powersteering',$powersteering,PDO::PARAM_STR);
        $query->bindParam(':driverairbag',$driverairbag,PDO::PARAM_STR);
        $query->bindParam(':passengerairbag',$passengerairbag,PDO::PARAM_STR);
        $query->bindParam(':powerwindow',$powerwindow,PDO::PARAM_STR);
        $query->bindParam(':cdplayer',$cdplayer,PDO::PARAM_STR);
        $query->bindParam(':centrallocking',$centrallocking,PDO::PARAM_STR);
        $query->bindParam(':crashcensor',$crashcensor,PDO::PARAM_STR);
        $query->bindParam(':leatherseats',$leatherseats,PDO::PARAM_STR);
        
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if($lastInsertId) {
            $msg="Vehicle posted successfully";
        } else {
            $error="Something went wrong. Please try again";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Post Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f5f7fa; font-family: 'Segoe UI', sans-serif; padding-top: 100px; }
        
        /* Header Styles */
        .page-header { border-left: 5px solid #f1c40f; padding-left: 15px; margin-bottom: 30px; }
        .page-header h2 { font-weight: 800; color: #2c3e50; margin: 0; }
        .page-header p { color: #7f8c8d; margin: 5px 0 0; }
        
        /* Card Styles */
        .card-custom { background: white; border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; overflow: hidden; }
        .card-header-custom { background-color: #2c3e50; color: white; padding: 15px 20px; font-weight: 600; display: flex; align-items: center; }
        .card-header-custom i { margin-right: 10px; color: #f1c40f; }
        .card-body-custom { padding: 30px; }

        /* Form Inputs */
        .form-label { font-weight: 600; color: #34495e; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control, .form-select { border-radius: 8px; padding: 12px; border: 1px solid #e0e0e0; background-color: #fcfcfc; }
        .form-control:focus, .form-select:focus { border-color: #f1c40f; box-shadow: 0 0 0 0.2rem rgba(241, 196, 15, 0.2); background-color: #fff; }
        
        /* Pricing Input Colors */
        .price-input { border: 2px solid #27ae60 !important; color: #27ae60; font-weight: bold; font-size: 1.1rem; }
        .price-input-week { border: 1px solid #2980b9 !important; color: #2980b9; }
        .price-input-month { border: 1px solid #8e44ad !important; color: #8e44ad; }

        /* Image Upload */
        .upload-zone { border: 2px dashed #bdc3c7; border-radius: 10px; padding: 20px; text-align: center; background: #f9f9f9; transition: 0.3s; }
        .upload-zone:hover { border-color: #f1c40f; background: #fffdf5; }

        /* Accessories */
        .accessory-item { background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 15px; transition: 0.2s; display: flex; align-items: center; }
        .accessory-item:hover { transform: translateY(-3px); box-shadow: 0 5px 10px rgba(0,0,0,0.05); border-color: #f1c40f; }
        .accessory-icon { width: 40px; height: 40px; background: #f4f6f7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: #2c3e50; font-size: 1.2rem; }
        .form-check-input:checked + label .accessory-icon { background: #f1c40f; color: white; }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container">
        
        <div class="page-header">
            <h2>Post A New Vehicle</h2>
            <p>Fill in the details below to add a car to your fleet.</p>
        </div>

        <?php if(isset($msg)){ ?>
            <div class="alert alert-success shadow-sm"><i class="fa fa-check-circle"></i> <?php echo $msg; ?></div>
        <?php } ?>
        <?php if(isset($error)){ ?>
            <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle"></i> <?php echo $error; ?></div>
        <?php } ?>

        <form method="post" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-lg-8">
                    
                    <div class="card-custom">
                        <div class="card-header-custom">
                            <i class="fa fa-info-circle"></i> Basic Information
                        </div>
                        <div class="card-body-custom">
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label">Vehicle Title</label>
                                    <input type="text" name="vehicletitle" class="form-control" placeholder="e.g. Toyota Camry 2.5 Hybrid" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Brand</label>
                                    <select name="brandname" class="form-select" required>
                                        <option value="">Select Brand</option>
                                        <?php 
                                        $sql = "SELECT id,BrandName from tblbrands";
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

                                <div class="col-md-6">
                                    <label class="form-label">Fuel Type</label>
                                    <select name="fueltype" class="form-select" required>
                                        <option value="Petrol">Petrol</option>
                                        <option value="Diesel">Diesel</option>
                                        <option value="Hybrid">Hybrid</option>
                                        <option value="Electric">Electric</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Model Year</label>
                                    <input type="number" min="2000" max="<?php echo date('Y')+1; ?>" name="modelyear" class="form-control" placeholder="e.g. <?php echo date('Y'); ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Seating Capacity</label>
                                    <input type="number" min="1" max="7" name="seatingcapacity" class="form-control" placeholder="e.g. 5" required>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Vehicle Overview / Description</label>
                                    <textarea name="vehicalorcview" class="form-control" rows="4" placeholder="Describe the car features..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-custom">
                        <div class="card-header-custom"><i class="fa fa-cogs"></i> Accessories & Features</div>
                        <div class="card-body-custom">
                            <div class="row g-3">
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="airconditioner" id="ac"><label class="form-check-label d-flex align-items-center w-100" for="ac"><span class="accessory-icon"><i class="fa fa-snowflake"></i></span> Air Conditioner</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="powerdoorlocks" id="pdl"><label class="form-check-label d-flex align-items-center w-100" for="pdl"><span class="accessory-icon"><i class="fa fa-lock"></i></span> Door Locks</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="antilockbrakingsys" id="abs"><label class="form-check-label d-flex align-items-center w-100" for="abs"><span class="accessory-icon"><i class="fa fa-shield-alt"></i></span> ABS System</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="brakeassist" id="ba"><label class="form-check-label d-flex align-items-center w-100" for="ba"><span class="accessory-icon"><i class="fa fa-stop-circle"></i></span> Brake Assist</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="powersteering" id="ps"><label class="form-check-label d-flex align-items-center w-100" for="ps"><span class="accessory-icon"><i class="fa fa-life-ring"></i></span> Power Steering</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="driverairbag" id="da"><label class="form-check-label d-flex align-items-center w-100" for="da"><span class="accessory-icon"><i class="fa fa-user-shield"></i></span> Driver Airbag</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="passengerairbag" id="pa"><label class="form-check-label d-flex align-items-center w-100" for="pa"><span class="accessory-icon"><i class="fa fa-user-friends"></i></span> Pass. Airbag</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="powerwindow" id="pw"><label class="form-check-label d-flex align-items-center w-100" for="pw"><span class="accessory-icon"><i class="fa fa-window-maximize"></i></span> Auto Windows</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="cdplayer" id="cd"><label class="form-check-label d-flex align-items-center w-100" for="cd"><span class="accessory-icon"><i class="fa fa-music"></i></span> Bluetooth/Audio</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="centrallocking" id="cl"><label class="form-check-label d-flex align-items-center w-100" for="cl"><span class="accessory-icon"><i class="fa fa-key"></i></span> Central Locking</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="crashcensor" id="cc"><label class="form-check-label d-flex align-items-center w-100" for="cc"><span class="accessory-icon"><i class="fa fa-video"></i></span> Sensors/Cam</label></div></div></div>
                                <div class="col-md-4"><div class="accessory-item"><div class="form-check w-100 d-flex align-items-center"><input class="form-check-input me-3" type="checkbox" name="leatherseats" id="ls"><label class="form-check-label d-flex align-items-center w-100" for="ls"><span class="accessory-icon"><i class="fa fa-couch"></i></span> Leather Seats</label></div></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    
                    <div class="card-custom">
                        <div class="card-header-custom" style="background: #27ae60;">
                            <i class="fa fa-money-bill-wave text-white"></i> Pricing Packages
                        </div>
                        <div class="card-body-custom">
                            <div class="mb-3">
                                <label class="form-label text-success">Price Per Day (RM)</label>
                                <input type="number" name="priceperday" id="price_day" class="form-control price-input" placeholder="e.g. 100" required>
                                <div class="form-text">Standard daily rate.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-primary">Price Per Week (7 Days)</label>
                                <input type="number" name="priceperweek" id="price_week" class="form-control price-input-week" placeholder="e.g. 600">
                                <div class="form-text">Auto-calculated (15% Discount).</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="color: #8e44ad;">Price Per Month (30 Days)</label>
                                <input type="number" name="pricepermonth" id="price_month" class="form-control price-input-month" placeholder="e.g. 2000">
                                <div class="form-text">Auto-calculated (35% Discount).</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-custom">
                        <div class="card-header-custom" style="background: #e67e22;">
                            <i class="fa fa-images text-white"></i> Vehicle Images
                        </div>
                        <div class="card-body-custom">
                            <div class="mb-3">
                                <label class="form-label">Main Image (Required)</label>
                                <div class="upload-zone"><input type="file" name="img1" class="form-control" required></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Side/Interior 1</label>
                                <div class="upload-zone"><input type="file" name="img2" class="form-control"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Side/Interior 2</label>
                                <div class="upload-zone"><input type="file" name="img3" class="form-control"></div>
                            </div>
                            <div class="alert alert-info small">
                                <i class="fa fa-info-circle"></i> Use landscape images (e.g., 800x600).
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="submit" class="btn btn-warning btn-lg text-white fw-bold shadow">
                            <i class="fa fa-check-circle"></i> Publish Vehicle
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Get elements by ID
        const dailyInput = document.getElementById('price_day');
        const weeklyInput = document.getElementById('price_week');
        const monthlyInput = document.getElementById('price_month');

        // Listen for input changes on Daily Price
        dailyInput.addEventListener('input', function() {
            const dailyPrice = parseFloat(this.value);

            if (!isNaN(dailyPrice)) {
                // Calculation: Daily * 7 * 0.85 (15% Off)
                const weeklyPrice = Math.round(dailyPrice * 7 * 0.85);
                weeklyInput.value = weeklyPrice;

                // Calculation: Daily * 30 * 0.65 (35% Off)
                const monthlyPrice = Math.round(dailyPrice * 30 * 0.65);
                monthlyInput.value = monthlyPrice;
            } else {
                // Clear fields if input is empty
                weeklyInput.value = '';
                monthlyInput.value = '';
            }
        });
    </script>
</body>
</html>
<?php } ?>