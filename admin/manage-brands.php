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
    // --- Delete Brand Logic ---
    if(isset($_GET['del']))
    {
        $id=$_GET['del'];
        $sql = "delete from tblbrands WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->execute();
        $msg="Brand Deleted Successfully";
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Brands</title>
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
        .page-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header h2 { font-weight: 700; color: #2c3e50; margin: 0; }
        
        /* Table Styling */
        .table thead { background-color: #f8f9fa; }
        .table th { font-weight: 600; color: #555; border-bottom: 2px solid #eee; text-transform: uppercase; font-size: 0.85rem; }
        .table td { vertical-align: middle; color: #333; }
        
        /* Action Buttons */
        .action-btn {
            width: 35px; height: 35px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%; text-decoration: none; margin: 0 3px; transition: 0.3s;
        }
        .btn-edit { background-color: #e3f2fd; color: #2196f3; }
        .btn-edit:hover { background-color: #2196f3; color: white; }
        .btn-delete { background-color: #ffebee; color: #f44336; }
        .btn-delete:hover { background-color: #f44336; color: white; }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container">
        
        <div class="page-header">
            <h2>Manage Brands</h2>
            <a href="create-brand.php" class="btn btn-dark btn-sm"><i class="fa fa-plus"></i> Create New</a>
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
                            <th>Brand Name</th>
                            <th>Creation Date</th>
                            <th>Last Update</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php 
                    $sql = "SELECT * from tblbrands";
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
                                <td><strong><?php echo htmlentities($result->BrandName);?></strong></td>
                                <td><?php echo htmlentities($result->CreationDate);?></td>
                                <td><?php echo htmlentities($result->UpdationDate);?></td>
                                <td class="text-center">
                                    <a href="edit-brand.php?id=<?php echo $result->id;?>" class="action-btn btn-edit" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="manage-brands.php?del=<?php echo $result->id;?>" class="action-btn btn-delete" onclick="return confirm('Do you really want to delete this brand?');" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php $cnt=$cnt+1; }} else { ?>
                            <tr><td colspan="5" class="text-center text-muted">No brands listed yet.</td></tr>
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