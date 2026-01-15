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
    // --- Delete User Logic (Optional) ---
    if(isset($_GET['del']))
    {
        $id=$_GET['del'];
        $sql = "delete from tblusers WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->execute();
        $msg="User Record Deleted Successfully";
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Registered Users</title>
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
        
        .user-avatar {
            width: 40px; height: 40px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #495057; margin-right: 10px;
        }
    </style>
</head>
<body>

    <?php include('includes/header.php');?>

    <div class="container">
        
        <div class="page-header">
            <h2>Registered Users</h2>
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
                            <th>Name</th>
                            <th>Email / Contact</th>
                            <th>Address</th>
                            <th>Reg Date</th>
                            <th class="text-center">Action</th> </tr>
                    </thead>
                    <tbody>

                    <?php 
                    $sql = "SELECT * from tblusers";
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
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar"><i class="fa fa-user"></i></div>
                                        <div><strong><?php echo htmlentities($result->FullName);?></strong></div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="text-primary"><i class="fa fa-envelope"></i> <?php echo htmlentities($result->EmailId);?></div>
                                    <div class="text-muted small"><i class="fa fa-phone"></i> <?php echo htmlentities($result->ContactNo);?></div>
                                </td>

                                <td>
                                    <?php echo htmlentities($result->Address);?><br>
                                    <small class="text-muted"><?php echo htmlentities($result->City);?></small>
                                </td>

                                <td><?php echo htmlentities($result->RegDate);?></td>
                                
                                <td class="text-center">
                                    <a href="reg-users.php?del=<?php echo $result->id;?>" onclick="return confirm('Do you really want to delete this user?');" class="btn btn-danger btn-sm" title="Delete User">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php $cnt=$cnt+1; }} else { ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No users registered yet.</td>
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