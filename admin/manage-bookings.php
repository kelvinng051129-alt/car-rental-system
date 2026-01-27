<?php
session_start();
error_reporting(0);
include('../includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{
    // ---(Cancel)---
    if(isset($_REQUEST['eid']))
    {
        $eid=intval($_GET['eid']);
        $status="2";
        $sql = "UPDATE tblbooking SET Status=:status WHERE  id=:eid";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':status',$status, PDO::PARAM_STR);
        $query-> bindParam(':eid',$eid, PDO::PARAM_STR);
        $query -> execute();
        $msg="Booking Successfully Cancelled";
    }

    // ---(Confirm)---
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">

    <style>
        body { background-color: #f5f7fa; font-family: 'Segoe UI', sans-serif; padding-top: 80px; }
        .page-header { border-left: 5px solid #2c3e50; padding-left: 15px; margin-bottom: 30px; }
        .page-header h2 { font-weight: 800; color: #2c3e50; margin: 0; }
        
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
        .card-header-custom { background-color: #2c3e50; color: white; padding: 15px 20px; font-weight: 600; }
        
        .table-custom th { background-color: #f8f9fa; border-bottom: 2px solid #e9ecef; color: #7f8c8d; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; }
        .table-custom td { vertical-align: middle; color: #2c3e50; font-size: 0.95rem; }
        
        .badge-pending { background-color: #ffeeba; color: #856404; padding: 8px 12px; border-radius: 6px; font-weight: 600; }
        .badge-confirmed { background-color: #d4edda; color: #155724; padding: 8px 12px; border-radius: 6px; font-weight: 600; }
        .badge-cancelled { background-color: #f8d7da; color: #721c24; padding: 8px 12px; border-radius: 6px; font-weight: 600; }

        .btn-action { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; transition: 0.3s; margin: 0 2px; border: none; }
        .btn-confirm { background-color: #e3f9e5; color: #27ae60; }
        .btn-confirm:hover { background-color: #27ae60; color: white; transform: scale(1.1); }
        .btn-cancel { background-color: #ffeaea; color: #e74c3c; }
        .btn-cancel:hover { background-color: #e74c3c; color: white; transform: scale(1.1); }
        
        /* Vehicle details in table */
        .vehicle-link { text-decoration: none; color: #2980b9; font-weight: 600; }
        .vehicle-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container-fluid px-4">
        
        <div class="page-header">
            <h2>Manage Bookings</h2>
        </div>

        <?php if($msg){ ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-check-circle me-2"></i> <?php echo htmlentities($msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="fa fa-list-alt me-2"></i> Bookings List
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">User Details</th>
                                <th width="25%">Vehicle</th>
                                <th width="15%">Dates</th>
                                <th width="20%">Message</th>
                                <th width="10%">Status</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sql = "SELECT tblusers.FullName,tblusers.EmailId,tblusers.ContactNo,tblvehicles.VehiclesTitle,tblbrands.BrandName,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id order by tblbooking.id desc";
                            $query = $dbh -> prepare($sql);
                            $query->execute();
                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                            $cnt=1;
                            
                            if($query->rowCount() > 0) {
                                foreach($results as $result) { ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt);?></td>
                                    <td>
                                        <strong><?php echo htmlentities($result->FullName);?></strong><br>
                                        <small class="text-muted"><i class="fa fa-envelope"></i> <?php echo htmlentities($result->EmailId);?></small><br>
                                        <small class="text-muted"><i class="fa fa-phone"></i> <?php echo htmlentities($result->ContactNo);?></small>
                                    </td>
                                    <td>
                                        <a href="../vehical-details.php?vhid=<?php echo htmlentities($result->vid);?>" target="_blank" class="vehicle-link">
                                            <?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?>
                                        </a>
                                    </td>
                                    <td>
                                        <small>From: <?php echo htmlentities($result->FromDate);?></small><br>
                                        <small>To: <?php echo htmlentities($result->ToDate);?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo htmlentities($result->message);?></small>
                                    </td>
                                    <td>
                                        <?php if($result->Status==0){ ?>
                                            <span class="badge-pending">Not Confirmed</span>
                                        <?php } else if($result->Status==1) { ?>
                                            <span class="badge-confirmed">Confirmed</span>
                                        <?php } else { ?>
                                            <span class="badge-cancelled">Cancelled</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($result->Status==0){ ?>
                                            <a href="manage-bookings.php?aeid=<?php echo htmlentities($result->id);?>" 
                                               onclick="return confirmAction(event, this.href, 'confirm')" 
                                               class="btn-action btn-confirm" title="Confirm Booking">
                                               <i class="fa fa-check"></i>
                                            </a>

                                            <a href="manage-bookings.php?eid=<?php echo htmlentities($result->id);?>" 
                                               onclick="return confirmAction(event, this.href, 'cancel')" 
                                               class="btn-action btn-cancel" title="Cancel Booking">
                                               <i class="fa fa-times"></i>
                                            </a>
                                        <?php } else { ?>
                                            <span class="text-muted small">Completed</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php $cnt=$cnt+1; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert2 Confirmation Logic
        function confirmAction(e, url, actionType) {
            e.preventDefault(); // Stop the link from redirecting immediately

            // Define text/colors based on action
            let titleText = actionType === 'confirm' ? 'Confirm Booking?' : 'Cancel Booking?';
            let contentText = actionType === 'confirm' ? 'Are you sure you want to approve this booking?' : 'This action cannot be undone.';
            let iconType = actionType === 'confirm' ? 'question' : 'warning';
            let confirmBtnColor = actionType === 'confirm' ? '#27ae60' : '#d33';
            let btnText = actionType === 'confirm' ? 'Yes, Confirm!' : 'Yes, Cancel it!';

            Swal.fire({
                title: titleText,
                text: contentText,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: btnText
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect if confirmed
                    window.location.href = url;
                }
            });
        }
    </script>
</body>
</html>
<?php } ?>