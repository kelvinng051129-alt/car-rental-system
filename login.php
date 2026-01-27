<?php
session_start();
include('includes/config.php');
error_reporting(0);

// If already logged in, go home
if (isset($_SESSION['login']) && strlen($_SESSION['login']) > 0) {
    header('Location: index.php');
    exit;
}

$errMsg = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === '' || $password === '') {
        $errMsg = "Please enter your email and password.";
    } else {
        // Fetch user by email
        $sql = "SELECT FullName, EmailId, Password FROM tblusers WHERE EmailId = :email LIMIT 1";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() == 1) {
            $user = $query->fetch(PDO::FETCH_OBJ);

            // Verify hashed password (bcrypt / password_hash)
            if (password_verify($password, $user->Password)) {
                // set sessions used by your header + booking checks
                $_SESSION['login'] = $user->EmailId;
                $_SESSION['fname'] = $user->FullName;

                echo "<script>
                    alert('Login successful!');
                    window.location.href='index.php';
                </script>";
                exit;
            } else {
                $errMsg = "Invalid email or password.";
            }
        } else {
            $errMsg = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Buat Kerja Betul2 Car Rental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Fonts (match index/about/contact) -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

  <style>
    body{
      font-family:'Poppins',sans-serif;
      background:#0f0f0f;
      color:#fff;
    }

    .page-wrap{
      min-height: 70vh;
      display:flex;
      align-items:center;
      padding: 40px 0;
    }

    .login-card{
      background:#181818;
      border:1px solid #2a2a2a;
      border-radius:0;
      box-shadow:0 10px 30px rgba(0,0,0,0.3);
      overflow:hidden;
    }

    .login-header{
      padding: 28px 28px 0 28px;
      text-align:center;
    }

    .login-title{
      font-family:'Playfair Display',serif;
      font-size:2.2rem;
      margin-bottom:8px;
    }

    .login-subtitle{
      color:#888;
      font-size:0.9rem;
      letter-spacing:1px;
      text-transform:uppercase;
      margin-bottom: 18px;
    }

    .section-divider{
      width:60px;
      height:2px;
      background:#d4af37;
      margin: 0 auto 22px auto;
      border:none;
    }

    .login-body{
      padding: 0 28px 28px 28px;
    }

    .form-label{
      color:#aaa;
      text-transform:uppercase;
      letter-spacing:1px;
      font-size:0.85rem;
      margin-bottom:8px;
    }

    .form-control{
      background:#121212;
      border:1px solid #2a2a2a;
      color:#fff;
      border-radius:0;
      padding:12px 14px;
    }

    .form-control:focus{
      background:#121212;
      color:#fff;
      border-color:#d4af37;
      box-shadow:0 0 0 0.2rem rgba(212,175,55,0.15);
    }

    .btn-gold{
      background: linear-gradient(45deg, #d4af37, #c5a028);
      color:#000;
      padding:12px 26px;
      font-weight:bold;
      border-radius:2px;
      border:none;
      text-transform:uppercase;
      letter-spacing:1px;
      transition:0.3s;
      width:100%;
    }
    .btn-gold:hover{
      background:#fff;
      color:#000;
      transform: translateY(-2px);
      box-shadow:0 10px 20px rgba(212,175,55,0.3);
    }

    .link-gold{
      color:#d4af37;
      text-decoration:none;
    }
    .link-gold:hover{
      color:#fff;
      text-decoration:underline;
    }

    .error-box{
      background: rgba(220,53,69,0.12);
      border:1px solid rgba(220,53,69,0.35);
      color:#ffb3bc;
      padding:12px 14px;
      margin-bottom:16px;
      font-size:0.95rem;
    }

    .input-group-text{
      background:#121212;
      border:1px solid #2a2a2a;
      color:#d4af37;
      border-radius:0;
    }
  </style>
</head>

<body>
<?php include('includes/header.php'); ?>

<div class="page-wrap">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-6 col-xl-5">
        <div class="login-card">
          <div class="login-header">
            <h1 class="login-title">Login</h1>
            <p class="login-subtitle">Login to manage your booking</p>
            <hr class="section-divider">
          </div>

          <div class="login-body">
            <?php if($errMsg !== '') { ?>
              <div class="error-box">
                <i class="fa fa-triangle-exclamation"></i> <?php echo htmlentities($errMsg); ?>
              </div>
            <?php } ?>

            <form method="post" autocomplete="off">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                  <input type="email" class="form-control" name="email" placeholder="you@example.com" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa fa-lock"></i></span>
                  <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                </div>
              </div>

              <button type="submit" name="login" class="btn-gold">Login</button>

              <div class="text-center mt-3" style="color:#aaa;">
                Don't have an account?
                <a class="link-gold" href="register.php">Register a new account</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
