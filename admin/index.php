<?php
session_start();
// Include the database configuration file
// Note: We use '../' because we are inside the 'admin' folder and need to go up one level
include('../includes/config.php');

// Check if the login button was clicked
if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL Query: Check if username and password match a record in the 'admin' table
    $sql = "SELECT UserName, Password FROM admin WHERE UserName=:username and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // If a match is found
    if($query->rowCount() > 0)
    {
        // Store username in session variable
        $_SESSION['alogin'] = $_POST['username'];
        
        // Redirect to dashboard page
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        // Login failed
        echo "<script>alert('Invalid Username or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Portal | Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="login-card">
        <h3 class="text-center mb-4">Admin Login</h3>
        
        <form method="post">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="admin" required>
            </div>
            
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="password" required>
            </div>
            
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="../index.php" style="text-decoration: none;">Back to Home</a>
        </div>
    </div>

</body>
</html>