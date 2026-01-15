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

    // --- Action: Mark as Paid ---
    if(isset($_GET['pay_id']))
    {
        $pid = intval($_GET['pay_id']);
        $sql = "UPDATE tblbooking SET payment_status=1 WHERE id=:pid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pid',$pid, PDO::PARAM_STR);
        $query->execute();
        $msg = "Payment Marked Successfully!";
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f5f7fa; font-family: 'Segoe UI', sans-serif; padding-top: 100px; }
        .table-card {
            background: white; border-radius: 12px; padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-top: 4px solid #27ae60; /* Green for Money */
        }
        .page-header h2 { font-weight: 700; color: #2c3e50; }
        .text-price { color: #27ae60; font-weight: bold; font-size: 1.1rem; }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container">
        
        <div class="page-header mb-4">
            <h2><i class="fa fa-file-invoice-dollar"></i> Manage Payments</h2>
        </div>

        <?php if(isset($msg)){ ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle"></i> <?php echo $msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Dates</th>
                            <th>Total Days</th>
                            <th>Price/Day</th>
                            <th>Est. Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php 
                    // SQL: Calculate total days and display total price
                    // DATEDIFF(ToDate, FromDate) is used to calculate the rental duration
                    $sql = "SELECT tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblvehicles.PricePerDay,tblbooking.FromDate,tblbooking.ToDate,tblbooking.id,tblbooking.payment_status, DATEDIFF(tblbooking.ToDate, tblbooking.FromDate) as totaldays 
                            FROM tblbooking 
                            JOIN tblusers ON tblusers.EmailId=tblbooking.userEmail 
                            JOIN tblvehicles ON tblvehicles.id=tblbooking.VehicleId 
                            JOIN tblbrands ON tblvehicles.VehiclesBrand=tblbrands.id
                            ORDER BY tblbooking.id DESC";
                    
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    
                    if($query->rowCount() > 0)
                    {
                        foreach($results as $result)
                        { 
                            // Prevent days from being 0 (e.g., same day pickup and return counts as 1 day)
                            $days = ($result->totaldays == 0) ? 1 : $result->totaldays;
                            $total_price = $days * $result->PricePerDay;
                        ?>    
                            <tr>
                                <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result->FullName);?></td>
                                <td><?php echo htmlentities($result->BrandName);?> <?php echo htmlentities($result->VehiclesTitle);?></td>
                                <td>
                                    <small><?php echo htmlentities($result->FromDate);?></small><br>
                                    <small><?php echo htmlentities($result->ToDate);?></small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?php echo htmlentities($days);?> Days</span></td>
                                <td>RM <?php echo htmlentities($result->PricePerDay);?></td>
                                <td class="text-price">RM <?php echo number_format($total_price, 2);?></td>
                                
                                <td>
                                    <?php if($result->payment_status == 1) { ?>
                                        <span class="badge bg-success"><i class="fa fa-check"></i> Paid</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Unpaid</span>
                                    <?php } ?>
                                </td>
                                
                                <td>
                                    <?php if($result->payment_status == 0) { ?>
                                        <a href="manage-payments.php?pay_id=<?php echo $result->id;?>" onclick="return confirm('Confirm payment received?')" class="btn btn-outline-success btn-sm">
                                            <i class="fa fa-dollar-sign"></i> Mark Paid
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-muted"><i class="fa fa-lock"></i> Closed</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php $cnt=$cnt+1; }} else { ?>
                            <tr><td colspan="9" class="text-center text-muted">No records found.</td></tr>
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