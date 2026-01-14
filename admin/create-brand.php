<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// Security Check: Redirect to login if user is not logged in
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{
    // --- 1. Handle Form Submission (Create Brand) ---
    if(isset($_POST['submit']))
    {
        $brand = $_POST['brand'];
        
        // SQL: Insert new brand into database
        $sql="INSERT INTO tblbrands(BrandName) VALUES(:brand)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':brand',$brand,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        
        if($lastInsertId)
        {
            $msg="Brand Created Successfully";
        }
        else 
        {
            $error="Something went wrong. Please try again";
        }
    }

    // --- 2. Handle Delete Operation (Delete Brand) ---
    if(isset($_GET['del']))
    {
        $id=$_GET['del'];
        
        // SQL: Delete brand based on ID
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
            padding-top: 100px; /* Space for fixed Header */
        }
        
        /* Left Side: Form Card Styles */
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-top: 4px solid #f1c40f; /* Yellow Top Border */
        }

        /* Right Side: Table Card Styles */
        .table-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        h3 { color: #2c3e50; font-weight: 700; margin-bottom: 20px; font-size: 1.5rem; }
        
        /* Button Styles */
        .btn-custom { background-color: #2c3e50; color: white; padding: 10px 25px; font-weight: 600; border-radius: 8px; transition: 0.3s; }
        .btn-custom:hover { background-color: #f1c40f; color: #fff; transform: translateY(-2px); }
        
        /* Table Styles */
        .table thead { background-color: #f8f9fa; }
        .table th { font-weight: 600; color: #555; border-bottom: 2px solid #eee; }
        .table td { vertical-align: middle; color: #333; }
        
        /* Action Icons */
        .action-icon { margin: 0 5px; text-decoration: none; transition: 0.2s; }
        .action-icon:hover { transform: scale(1.2); }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container">
        <div class="row">
            
            <div class="col-md-4">
                <div class="form-card">
                    <h3><i class="fa fa-plus-circle text-warning"></i> Create Brand</h3>
                    <hr>
                    
                    <?php if(isset($msg)){ ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fa fa-check-circle"></i> <?php echo $msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>
                    <?php if(isset($error)){ ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php } ?>

                    <form method="post">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Brand Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa fa-tag"></i></span>
                                <input type="text" placeholder="e.g. Tesla" name="brand" class="form-control form-control-lg" required>
                            </div>
                            <div class="form-text text-muted">Enter the name of the car manufacturer.</div>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-custom" name="submit" type="submit">Create Brand</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><i class="fa fa-list-ul text-primary"></i> Listed Brands</h3>
                        <span class="badge bg-light text-dark border">Manage your brands</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Brand Name</th>
                                    <th>Creation Date</th>
                                    <th>Updation Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php 
                            // SQL: Fetch all brands from database
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
                                            <a href="manage-brands.php?id=<?php echo $result->id;?>" class="action-icon text-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="create-brand.php?del=<?php echo $result->id;?>" class="action-icon text-danger" onclick="return confirm('Do you want to delete this brand?');" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php $cnt=$cnt+1; }} else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No brands created yet.</td>
                                    </tr>
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>