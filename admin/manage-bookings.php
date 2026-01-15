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

    // --- Action: Confirm Booking (Status = 1) ---
    if(isset($_REQUEST['aeid']))
    {
        $aeid=intval($_GET['aeid']);
        $status=1;
        $sql = "UPDATE tblbooking SET Status=:status WHERE  id=:aeid";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':status',$status, PDO::PARAM_STR);
        $query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
        $query -> execute();
        $msg="Booking Successfully Confirmed";
    }

    // --- Action: Cancel Booking (Status = 2) ---
    if(isset($_REQUEST['eid']))
    {
        $eid=intval($_GET['eid']);
        $status=2;
        $sql = "UPDATE tblbooking SET Status=:status WHERE  id=:eid";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':status',$status, PDO::PARAM_STR);
        $query-> bindParam(':eid',$eid, PDO::PARAM_STR);
        $query -> execute();
        $msg="Booking Successfully Cancelled";
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
            padding-top: 100px;
        }
        .table-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-top: 4px solid #f1c40f;
        }
        .page-header { margin-bottom: 20px; }
        .page-header h2 { font-weight: 700; color: #2c3e50; margin: 0; }
        
        /* Table Styles */
        .table thead { background-color: #f8f9fa; }
        .table th { font-weight: 600; color: #555; border-bottom: 2px solid #eee; font-size: 0.85rem; text-transform: uppercase; }
        .table td { vertical-align: middle; color: #333; font-size: 0.9rem; }
        
        /* Badges */
        .badge-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-confirmed { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-cancelled { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Action Links */
        .action-link { text-decoration: none; margin: 0 5px; font-weight: bold; transition: 0.3s; }
        .link-confirm { color: #27ae60; }
        .link-confirm:hover { color: #2ecc71; text-shadow: 0 0 5px rgba(46, 204, 113, 0.3); }
        .link-cancel { color: #e74c3c; }
        .link-cancel:hover { color: #c0392b; text-shadow: 0 0 5px rgba(231, 76, 60, 0.3); }
        
        .vehicle-info { font-weight: bold; color: #2c3e50; }
        .user-info { font-size: 0.85rem; color: #7f8c8d; }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container">
        
        <div class="page-header">
            <h2>Manage Bookings</h2>
        </div>

        <?php if(isset($msg)){ ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle"></i> <?php echo $msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Vehicle</th>
                            <th>From / To Date</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php 
                    // SQL: Join Bookings + Users + Vehicles + Brands
                    $sql = "SELECT tblusers.FullName,tblusers.EmailId,tblusers.ContactNo,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  
                            FROM tblbooking 
                            JOIN tblvehicles ON tblvehicles.id=tblbooking.VehicleId 
                            JOIN tblusers ON tblusers.EmailId=tblbooking.userEmail 
                            JOIN tblbrands ON tblvehicles.VehiclesBrand=tblbrands.id";
                    
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    
                    if($query->rowCount() > 0)
                    {
                        foreach($results as $result)
                        { ?>    
                            <tr>
                                <td><?php echo htmlentities($cnt);?></td>
                                
                                <td>
                                    <div style="font-weight: 600;"><?php echo htmlentities($result->FullName);?></div>
                                    <div class="user-info"><i class="fa fa-envelope"></i> <?php echo htmlentities($result->EmailId);?></div>
                                    <div class="user-info"><i class="fa fa-phone"></i> <?php echo htmlentities($result->ContactNo);?></div>
                                </td>
                                
                                <td>
                                    <div class="vehicle-info"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></div>
                                </td>

                                <td>
                                    <span class="d-block text-muted small">From: <?php echo htmlentities($result->FromDate);?></span>
                                    <span class="d-block text-muted small">To: <?php echo htmlentities($result->ToDate);?></span>
                                </td>

                                <td><?php echo htmlentities($result->message);?></td>
                                
                                <td>
                                    <?php 
                                    if($result->Status==0) { ?>
                                        <span class="badge badge-pending">Not Confirmed yet</span>
                                    <?php } else if($result->Status==1) { ?>
                                        <span class="badge badge-confirmed">Confirmed</span>
                                    <?php } else { ?>
                                        <span class="badge badge-cancelled">Cancelled</span>
                                    <?php } ?>
                                </td>
                                
                                <td class="text-center">
                                    <?php if($result->Status==0) { ?>
                                        <a href="manage-bookings.php?aeid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Confirm this booking?')" class="action-link link-confirm" title="Confirm">
                                            <i class="fa fa-check-circle fa-lg"></i>
                                        </a>
                                        <a href="manage-bookings.php?eid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you really want to Cancel this booking?')" class="action-link link-cancel" title="Cancel">
                                            <i class="fa fa-times-circle fa-lg"></i>
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-muted small">Completed</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php $cnt=$cnt+1; }} else { ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No bookings found.</td>
                            </tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>