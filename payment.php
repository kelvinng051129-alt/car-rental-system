<?php
session_start();
require_once __DIR__ . '/includes/config.php';
error_reporting(0);

if (empty($_SESSION['login'])) {
  header("Location: index.php");
  exit();
}

$useremail = $_SESSION['login'];
$bkid = isset($_GET['bkid']) ? (int)$_GET['bkid'] : 0;

if ($bkid <= 0) {
  header("Location: my-booking.php");
  exit();
}

// 只允许查看/支付自己的 booking
$sql = "SELECT 
          b.id AS bookingId,
          b.userEmail,
          b.VehicleId,
          b.FromDate,
          b.ToDate,
          b.Status,
          b.PostingDate,
          b.payment_status,
          v.VehiclesTitle,
          v.PricePerDay,
          v.Vimage1,
          br.BrandName
        FROM tblbooking b
        JOIN tblvehicles v ON v.id = b.VehicleId
        JOIN tblbrands br ON br.id = v.VehiclesBrand
        WHERE b.id = :bkid AND b.userEmail = :useremail
        LIMIT 1";
$q = $dbh->prepare($sql);
$q->bindParam(':bkid', $bkid, PDO::PARAM_INT);
$q->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$q->execute();
$bk = $q->fetch(PDO::FETCH_OBJ);

if (!$bk) {
  header("Location: my-booking.php");
  exit();
}

// Cancelled 不给付
if ((int)$bk->Status === 2) {
  header("Location: booking-details.php?bkid=".$bkid);
  exit();
}

// 计算天数（至少 1 天）
$from = new DateTime($bk->FromDate);
$to   = new DateTime($bk->ToDate);
$days = (int)$from->diff($to)->days;
if ($days < 1) $days = 1;
$total = $days * (float)$bk->PricePerDay;

$err = "";
$paid_now = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pay_now'])) {
  if ((int)$bk->payment_status === 1) {
    header("Location: booking-details.php?bkid=".$bkid);
    exit();
  }

  $method = isset($_POST['method']) ? trim($_POST['method']) : "";

  if (!in_array($method, ['card','tng'], true)) {
    $err = "Please choose a payment method.";
  } else {
    // demo validation
    if ($method === 'card') {
      $card_no = preg_replace('/\s+/', '', $_POST['card_no'] ?? '');
      $card_name = trim($_POST['card_name'] ?? '');
      $exp = trim($_POST['exp'] ?? '');
      $cvv = preg_replace('/\s+/', '', $_POST['cvv'] ?? '');

      if ($card_no === "" || $card_name === "" || $exp === "" || $cvv === "") {
        $err = "Please fill in all card details.";
      } elseif (!preg_match('/^\d{13,19}$/', $card_no)) {
        $err = "Invalid card number.";
      } elseif (!preg_match('/^\d{2}\/\d{2}$/', $exp)) {
        $err = "Expiry format must be MM/YY.";
      } elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
        $err = "Invalid CVV.";
      }
    }

    if ($method === 'tng') {
      $tng_phone = preg_replace('/\s+/', '', $_POST['tng_phone'] ?? '');
      if ($tng_phone === "") {
        $err = "Please enter your TNG phone number.";
      } elseif (!preg_match('/^\d{9,11}$/', $tng_phone)) {
        $err = "Invalid phone number (digits only).";
      }
    }

    // success -> update paid
    if ($err === "") {
      $upd = "UPDATE tblbooking SET payment_status = 1 WHERE id = :bkid AND userEmail = :useremail";
      $u = $dbh->prepare($upd);
      $u->bindParam(':bkid', $bkid, PDO::PARAM_INT);
      $u->bindParam(':useremail', $useremail, PDO::PARAM_STR);
      $u->execute();

      $paid_now = true;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - Buat Kerja Betul2 Car Rental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body { font-family:'Poppins',sans-serif; background:#0f0f0f; color:#fff; }
    .dark-card{ background:#181818; border:1px solid #2a2a2a; border-radius:0; box-shadow:0 10px 30px rgba(0,0,0,.3); }
    .label{ color:#aaa; text-transform:uppercase; letter-spacing:1px; font-size:.82rem; }
    .value{ font-weight:600; }
    .line{ border-top:1px solid #2a2a2a; margin:18px 0; }

    .btn-outline-gold{
      border:1px solid #555; color:#fff; padding:10px 14px;
      text-transform:uppercase; font-size:.8rem; letter-spacing:1px;
      transition:.3s; text-decoration:none; display:inline-block; border-radius:0;
    }
    .btn-outline-gold:hover{ background:#d4af37; border-color:#d4af37; color:#000; }

    .btn-gold{
      background: linear-gradient(45deg, #d4af37, #c5a028);
      color:#000; font-weight:700; padding:12px 16px;
      border:none; border-radius:0; width:100%;
      text-transform:uppercase; letter-spacing:1px; transition:.3s;
    }
    .btn-gold:hover{ background:#fff; transform: translateY(-2px); box-shadow:0 10px 20px rgba(212,175,55,.3); }

    .form-control{
      background:#121212; border:1px solid #2a2a2a; color:#fff; border-radius:0;
      padding:12px 14px;
    }
    .form-control:focus{
      background:#121212; color:#fff; border-color:#d4af37;
      box-shadow:0 0 0 .2rem rgba(212,175,55,.15);
    }
    .pay-option{
      border:1px solid #2a2a2a; padding:14px; cursor:pointer;
      display:flex; gap:12px; align-items:flex-start;
    }
    .pay-option:hover{ border-color:#444; }
    .pay-option.active{ border-color:#d4af37; background:rgba(212,175,55,.06); }
    .pay-icon{ color:#d4af37; font-size:1.2rem; margin-top:2px; }
  </style>
</head>
<body>
<?php include(__DIR__ . '/includes/header.php'); ?>

<div class="container py-5" style="max-width:980px;">
  <div class="d-flex gap-2 flex-wrap mb-4">
    <a class="btn-outline-gold" href="booking-details.php?bkid=<?php echo urlencode($bkid); ?>">
      <i class="fa fa-arrow-left"></i> Back to Details
    </a>
    <a class="btn-outline-gold" href="my-booking.php">My Bookings</a>
  </div>

  <div class="row g-4">
    <div class="col-lg-5">
      <div class="dark-card p-3">
        <img src="admin/img/vehicleimages/<?php echo htmlentities($bk->Vimage1); ?>"
             onerror="this.src='https://placehold.co/900x600/222/fff?text=No+Image';"
             style="width:100%; height:240px; object-fit:cover; border:1px solid #2a2a2a; filter:brightness(.92);">
        <div class="pt-3">
          <div class="label">Vehicle</div>
          <div class="value" style="font-family:'Playfair Display'; font-size:1.4rem;">
            <?php echo htmlentities($bk->BrandName.' '.$bk->VehiclesTitle); ?>
          </div>
          <div class="line"></div>
          <div class="d-flex justify-content-between">
            <div>
              <div class="label">Days</div>
              <div class="value"><?php echo (int)$days; ?></div>
            </div>
            <div class="text-end">
              <div class="label">Total</div>
              <div class="value">RM <?php echo number_format($total, 2); ?></div>
            </div>
          </div>
          <div class="mt-2" style="color:#888; font-size:.9rem;">
            (<?php echo (int)$days; ?> day(s) × RM <?php echo htmlentities($bk->PricePerDay); ?>)
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="dark-card p-4 p-md-5">
        <h3 style="font-family:'Playfair Display'; margin-bottom:6px;">Choose Payment Method</h3>
        <div style="color:#888; font-size:.95rem;">Demo only (sets payment_status to Paid).</div>

        <?php if ($err !== "") { ?>
          <div class="alert alert-danger mt-3" style="border-radius:0; background:#2a0f0f; border:1px solid #7a1f1f; color:#ffdede;">
            <?php echo htmlentities($err); ?>
          </div>
        <?php } ?>

        <form method="post" class="mt-4" id="payForm">
          <input type="hidden" name="method" id="method" value="">

          <div class="row g-3">
            <div class="col-md-6">
              <div class="pay-option" id="optCard" onclick="selectMethod('card')">
                <i class="fa fa-credit-card pay-icon"></i>
                <div>
                  <div class="value">Bank Card</div>
                  <div style="color:#888; font-size:.9rem;">Visa / MasterCard (demo)</div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="pay-option" id="optTng" onclick="selectMethod('tng')">
                <i class="fa fa-mobile-screen-button pay-icon"></i>
                <div>
                  <div class="value">Touch ’n Go eWallet</div>
                  <div style="color:#888; font-size:.9rem;">Phone number (demo)</div>
                </div>
              </div>
            </div>
          </div>

          <div class="line"></div>

          <!-- Card Fields -->
          <div id="cardFields" style="display:none;">
            <div class="row g-3">
              <div class="col-md-8">
                <div class="label">Card Number</div>
                <input class="form-control" name="card_no" placeholder="4111111111111111">
              </div>
              <div class="col-md-4">
                <div class="label">Expiry (MM/YY)</div>
                <input class="form-control" name="exp" placeholder="08/30">
              </div>
              <div class="col-md-8">
                <div class="label">Cardholder Name</div>
                <input class="form-control" name="card_name" placeholder="TAN LE YONG">
              </div>
              <div class="col-md-4">
                <div class="label">CVV</div>
                <input class="form-control" name="cvv" placeholder="123">
              </div>
            </div>
          </div>

          <!-- TNG Fields -->
          <div id="tngFields" style="display:none;">
            <div class="mt-2">
              <div class="label">TNG Phone Number</div>
              <input class="form-control" name="tng_phone" placeholder="0123456789">
            </div>
          </div>

          <div class="line"></div>

          <button class="btn-gold" type="submit" name="pay_now">
            <i class="fa fa-lock"></i> Confirm Payment (Demo)
          </button>

          <div class="mt-2" style="color:#666; font-size:.85rem;">
            *No real payment. This is only for demonstration.
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<?php if ($paid_now) { ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Payment Successful!',
  text: 'Payment status updated to PAID.',
  confirmButtonColor: '#d4af37',
  background: '#181818',
  color: '#fff'
}).then(() => {
  window.location.href = "booking-details.php?bkid=<?php echo (int)$bkid; ?>";
});
</script>
<?php } ?>

<script>
function selectMethod(m){
  document.getElementById('method').value = m;

  document.getElementById('optCard').classList.remove('active');
  document.getElementById('optTng').classList.remove('active');

  document.getElementById('cardFields').style.display = 'none';
  document.getElementById('tngFields').style.display = 'none';

  if(m === 'card'){
    document.getElementById('optCard').classList.add('active');
    document.getElementById('cardFields').style.display = 'block';
  } else {
    document.getElementById('optTng').classList.add('active');
    document.getElementById('tngFields').style.display = 'block';
  }
}

// 默认选银行卡（你要默认不选也行）
selectMethod('card');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
