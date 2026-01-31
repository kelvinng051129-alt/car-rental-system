<?php
session_start();
require_once __DIR__ . '/includes/config.php';
error_reporting(0);

if (empty($_SESSION['login'])) {
  header("Location: index.php");
  exit();
}

$useremail = $_SESSION['login'];
$status = "";

// 抓用户资料
$sql = "SELECT FullName, EmailId, Password, ContactNo, Address, City, RegDate, UpdationDate
        FROM tblusers
        WHERE EmailId = :email
        LIMIT 1";
$q = $dbh->prepare($sql);
$q->bindParam(':email', $useremail, PDO::PARAM_STR);
$q->execute();
$u = $q->fetch(PDO::FETCH_OBJ);

if (!$u) {
  header("Location: logout.php");
  exit();
}

// 提交更新
if (isset($_POST['update_profile'])) {
  $fullname  = trim($_POST['fullname'] ?? '');
  $contactno = trim($_POST['contactno'] ?? '');
  $address   = trim($_POST['address'] ?? '');
  $city      = trim($_POST['city'] ?? '');

  // 密码（可选）
  $current_password = $_POST['current_password'] ?? '';
  $newpass = $_POST['newpass'] ?? '';
  $confpass = $_POST['confpass'] ?? '';

  if ($fullname === "" || $contactno === "" || $address === "" || $city === "") {
    $status = "invalid";
  }

  $wantsChangePassword = ($current_password !== "" ||
                          $newpass !== "" || 
                          $confpass !== "");

  if ($status === "" && $wantsChangePassword) {

    if ($current_password === "") {
      $status = "current_required";

    } else if (!password_verify($current_password, $u->Password)) {
      $status = "current_wrong";

    } else if ($newpass !== $confpass) {
      $status = "pw_mismatch";

    } else if (strlen($newpass) < 6) {
      $status = "pw_short";
    }
  }

  if ($status === "") {

    if ($wantsChangePassword && $newpass !== "") {
      $hash = password_hash($newpass, PASSWORD_BCRYPT);
      $sqlUpd = "UPDATE tblusers
                 SET FullName = :fullname,
                     ContactNo = :contactno,
                     Address   = :address,
                     City      = :city,
                     Password  = :pass,
                     UpdationDate = NOW()
                 WHERE EmailId = :email";
      $upd = $dbh->prepare($sqlUpd);
      $upd->bindParam(':fullname', $fullname);
      $upd->bindParam(':contactno', $contactno);
      $upd->bindParam(':address', $address);
      $upd->bindParam(':city', $city);
      $upd->bindParam(':pass', $hash);
      $upd->bindParam(':email', $useremail);

    } else {
      $sqlUpd = "UPDATE tblusers
                 SET FullName = :fullname,
                     ContactNo = :contactno,
                     Address   = :address,
                     City      = :city,
                     UpdationDate = NOW()
                 WHERE EmailId = :email";
      $upd = $dbh->prepare($sqlUpd);
      $upd->bindParam(':fullname', $fullname);
      $upd->bindParam(':contactno', $contactno);
      $upd->bindParam(':address', $address);
      $upd->bindParam(':city', $city);
      $upd->bindParam(':email', $useremail);
    }

    if ($upd->execute()) {
      $_SESSION['fname'] = $fullname;
      $status = "success";
    } else {
      $status = "error";
    }

    // 重新抓最新资料
    $q->bindParam(':email', $useremail);
    $q->execute();
    $u = $q->fetch(PDO::FETCH_OBJ);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile Settings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body { font-family:'Poppins',sans-serif; background:#0f0f0f; color:#fff; }

    .page-hero{
      background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.9)),
      url('https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?q=80&w=2070&auto=format&fit=crop');
      background-size:cover; background-position:center;
      height:35vh; display:flex; align-items:center; justify-content:center;
      text-align:center;
    }
    .hero-title{ font-family:'Playfair Display',serif; font-size:2.6rem; font-weight:700; text-transform:uppercase; }
    .hero-subtitle{ font-size:.9rem; color:#d4af37; letter-spacing:2px; }

    .dark-card{ background:#181818; border:1px solid #2a2a2a; border-radius:0; padding:25px; }

    .label{ color:#aaa; text-transform:uppercase; letter-spacing:1px; font-size:.82rem; margin-bottom:8px; }

    .form-control{
      background:#121212; border:1px solid #2a2a2a; color:#fff; border-radius:0;
      padding:12px 14px;
    }
    .form-control:focus{
      background:#121212; border-color:#d4af37;
      box-shadow:0 0 0 .2rem rgba(212,175,55,.15);
      color:#fff;
    }

    /* Email Readonly Style */
    .readonly-box {
      background:#121212 !important;
      color:#fff !important;
      opacity:1 !important;
      cursor:not-allowed;
    }

    .btn-gold{
      background: linear-gradient(45deg, #d4af37, #c5a028);
      border:none; border-radius:0;
      color:#000; font-weight:700; padding:12px 14px;
      width:100%; text-transform:uppercase; letter-spacing:1px;
    }
    .btn-gold:hover{
      background:#fff;
      transform: translateY(-2px);
      box-shadow:0 10px 20px rgba(212,175,55,.3);
    }

    .line{ border-top:1px solid #2a2a2a; margin:18px 0; }
  </style>
</head>
<body>

<?php include(__DIR__ . '/includes/header.php'); ?>

<section class="page-hero">
  <div>
    <div class="hero-title">Profile Settings</div>
    <div class="hero-subtitle">Manage your account</div>
  </div>
</section>

<section class="py-5">
  <div class="container" style="max-width:900px;">

    <div class="dark-card mb-4">
      <form method="post">

        <div class="row g-3">

          <div class="col-md-6">
            <div class="label">Full Name</div>
            <input class="form-control" name="fullname"
                   value="<?php echo htmlentities($u->FullName); ?>" required>
          </div>

          <div class="col-md-6">
            <div class="label">Contact No</div>
            <input class="form-control" name="contactno"
                   value="<?php echo htmlentities($u->ContactNo); ?>" required>
          </div>

          <div class="col-12">
            <div class="label">Address</div>
            <input class="form-control" name="address"
                   value="<?php echo htmlentities($u->Address); ?>" required>
          </div>

          <div class="col-md-6">
            <div class="label">City</div>
            <input class="form-control" name="city"
                   value="<?php echo htmlentities($u->City); ?>" required>
          </div>

          <div class="col-md-6">
            <div class="label">Email (cannot change)</div>
            <input class="form-control readonly-box" value="<?php echo htmlentities($u->EmailId); ?>" readonly>
          </div>

        </div>

        <div class="line"></div>

        <h5 style="font-family:'Playfair Display';">Change Password (Optional)</h5>

        <div class="row g-3 mt-2">

          <div class="col-12">
            <div class="label">Current Password</div>
            <input type="password" name="current_password" class="form-control"
                   placeholder="Enter your current password">
          </div>

          <div class="col-md-6">
            <div class="label">New Password</div>
            <input type="password" name="newpass" class="form-control"
                   placeholder="Min 6 characters">
          </div>

          <div class="col-md-6">
            <div class="label">Confirm Password</div>
            <input type="password" name="confpass" class="form-control"
                   placeholder="Re-enter new password">
          </div>

        </div>

        <button class="btn-gold mt-4" name="update_profile">
          <i class="fa fa-save"></i> Save Changes
        </button>

      </form>
    </div>

  </div>
</section>

<?php include(__DIR__ . '/includes/footer.php'); ?>

<script>
<?php if ($status === "success") { ?>
Swal.fire({ icon:'success', title:'Updated!', text:'Profile updated successfully.', confirmButtonColor:'#d4af37', background:'#181818', color:'#fff' });
<?php } elseif ($status === "invalid") { ?>
Swal.fire({ icon:'error', title:'Incomplete', text:'Please fill in all required fields.', confirmButtonColor:'#d4af37', background:'#181818', color:'#fff' });
<?php } elseif ($status === "current_required") { ?>
Swal.fire({ icon:'error', title:'Current password required', text:'Enter your current password to change password.', confirmButtonColor:'#d4af37', background:'#181818', color:'#fff' });
<?php } elseif ($status === "current_wrong") { ?>
Swal.fire({ icon:'error', title:'Wrong current password', text:'The current password you entered is incorrect.', confirmButtonColor:'#d4af37', background:'#181818', color:'#fff' });
<?php } elseif ($status === "pw_mismatch") { ?>
Swal.fire({ icon:'error', title:'Password mismatch', text:'New password and confirm password do not match.', confirmButtonColor:'#d4af37', background:'#181818', color:'#fff' });
<?php } elseif ($status === "pw_short") { ?>
Swal.fire({ icon:'error', title:'Password too short', text:'Password must be at least 6 characters.', confirmButtonColor:'#d4af37', background:'#181818', color:'#fff' });
<?php } elseif ($status === "error") { ?>
Swal.fire({ icon:'error', title:'Update failed', text:'Something went wrong. Try again.', confirmButtonColor:'#d4af37', background:'#181818', color:'#fff' });
<?php } ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
