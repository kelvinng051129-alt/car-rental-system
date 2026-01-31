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

$sql = "SELECT 
          b.id AS bookingId,
          b.userEmail,
          b.VehicleId,
          b.FromDate,
          b.ToDate,
          b.message,
          b.Status,
          b.PostingDate,
          b.payment_status,
          v.VehiclesTitle,
          v.PricePerDay,
          v.SecurityDeposit,
          v.FuelType,
          v.ModelYear,
          v.SeatingCapacity,
          v.Transmission,
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
$from = new DateTime($bk->FromDate);
$to   = new DateTime($bk->ToDate);
$days = (int)$from->diff($to)->days;
if ($days < 1) $days = 1;

$total = $days * (float)$bk->PricePerDay;

function bookingStatusText($s) {
  $s=(int)$s;
  if ($s===1) return "Confirmed";
  if ($s===2) return "Cancelled";
  return "Pending";
}
function bookingStatusClass($s) {
  $s=(int)$s;
  if ($s===1) return "badge-confirmed";
  if ($s===2) return "badge-cancelled";
  return "badge-pending";
}
function payText($p) { return ((int)$p===1) ? "Paid" : "Unpaid"; }
function payClass($p) { return ((int)$p===1) ? "badge-paid" : "badge-unpaid"; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Details - Buat Kerja Betul2 Car Rental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">

  <style>
    body {
       font-family:'Poppins',sans-serif; background:#0f0f0f; color:#fff; 
      }

    .page-hero{
      background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.9)),
      url('https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?q=80&w=2070&auto=format&fit=crop');
      background-size:cover; background-position:center;
      height:38vh; display:flex; align-items:center; justify-content:center;
      text-align:center;
    }
    .hero-title{
      font-family:'Playfair Display',serif; font-size:2.8rem; font-weight:700; letter-spacing:2px; text-transform:uppercase; margin:0 0 8px; text-shadow:0 10px 30px rgba(0,0,0,.8); 
      }

    .hero-subtitle{ 
      font-size:.95rem; color:#d4af37; letter-spacing:3px; text-transform:uppercase; margin:0; 
    }

    .section-divider{ 
      width:60px; height:2px; background:#d4af37; margin:16px auto 0; border:none; 
    }

    .dark-card{
      background:#181818; border:1px solid #2a2a2a; border-radius:0;
      box-shadow:0 10px 30px rgba(0,0,0,.3);
    }

    .img-wrap{ 
      height:260px; background:#111; overflow:hidden; border:1px solid #2a2a2a; 
    }

    .img-wrap img{ 
      width:100%; height:100%; object-fit:cover; filter:brightness(.92); 
    }

    .label{ 
      color:#aaa; text-transform:uppercase; letter-spacing:1px; font-size:.82rem; 
    }

    .value{ 
      font-weight:600; 
    }

    .badge-pending, .badge-confirmed, .badge-cancelled, .badge-paid, .badge-unpaid{
      padding:6px 10px; font-size:.78rem; border-radius:0;
      text-transform:uppercase; letter-spacing:1px; white-space:nowrap;
      border:1px solid transparent;
      display:inline-block;
    }

    .badge-pending{ 
      color:#d4af37; background:rgba(212,175,55,.12); border-color:rgba(212,175,55,.35); 
    }

    .badge-confirmed{
      color:#27ae60; background:rgba(39,174,96,.12); border-color:rgba(39,174,96,.35); 
      }

    .badge-cancelled{
      color:#e74c3c; background:rgba(231,76,60,.12); border-color:rgba(231,76,60,.35); 
      }

    .badge-paid{ 
      color:#27ae60; background:rgba(39,174,96,.12); border-color:rgba(39,174,96,.35); 
    }

    .badge-unpaid{ 
      color:#d4af37; background:rgba(212,175,55,.10); border-color:rgba(212,175,55,.30); 
    }

    .btn-outline-gold{
      border:1px solid #555; color:#fff;
      padding:10px 14px;
      text-transform:uppercase; font-size:.8rem; letter-spacing:1px;
      transition:.3s; text-decoration:none; display:inline-block; border-radius:0;
    }

    .btn-outline-gold:hover{ 
      background:#d4af37; border-color:#d4af37; color:#000; 
    }

    .btn-gold{
      background: linear-gradient(45deg, #d4af37, #c5a028);
      color:#000;
      padding:10px 14px;
      text-transform:uppercase;
      font-size:.8rem;
      letter-spacing:1px;
      font-weight:700;
      border:none;
      border-radius:0;
      transition:.3s;
      text-decoration:none;
      display:inline-block;
    }

    .btn-gold:hover{
      background:#fff;
      color:#000;
      box-shadow:0 10px 20px rgba(212,175,55,.3);
      transform:translateY(-2px);
    }

    .line{ 
      border-top:1px solid #2a2a2a; margin:18px 0; 
    }
  </style>
</head>
<body>
<?php include(__DIR__ . '/includes/header.php'); ?>
<section class="page-hero">
  <div class="container">
    <h1 class="hero-title">Booking Details</h1>
    <p class="hero-subtitle">Booking ID #<?php echo htmlentities($bk->bookingId); ?></p>
    <hr class="section-divider">
  </div>
</section>

<section class="py-5">
  <div class="container">

    <a class="btn-outline-gold mb-4" href="my-booking.php">
      <i class="fa fa-arrow-left"></i> Back to My Bookings
    </a>

    <div class="row g-4">

      <!-- Left card -->
      <div class="col-lg-5">
        <div class="dark-card p-3">
          <div class="img-wrap mb-3">
            <img src="admin/img/vehicleimages/<?php echo htmlentities($bk->Vimage1); ?>"
                 onerror="this.src='https://placehold.co/900x600/222/fff?text=No+Image';">
          </div>

          <div class="px-2 pb-2">
            <div class="label">Vehicle</div>
            <div class="value" style="font-family:'Playfair Display'; font-size:1.5rem;">
              <?php echo htmlentities($bk->BrandName.' '.$bk->VehiclesTitle); ?>
            </div>

            <div class="line"></div>

            <div class="d-flex justify-content-between">
              <div>
                <div class="label">Daily Rate</div>
                <div class="value">RM <?php echo htmlentities($bk->PricePerDay); ?></div>
              </div>

              <div class="text-end">
                <div class="label">Deposit</div>
                <div class="value">RM <?php echo htmlentities($bk->SecurityDeposit); ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right card -->
      <div class="col-lg-7">
        <div class="dark-card p-4 p-md-5">

          <div class="d-flex justify-content-between mb-3">

            <div>
              <div class="label">Status</div>
              <span class="<?php echo bookingStatusClass($bk->Status); ?>">
                <?php echo bookingStatusText($bk->Status); ?>
              </span>
            </div>

            <div class="text-md-end">
              <div class="label">Payment</div>
              <span class="<?php echo payClass($bk->payment_status); ?>">
                <?php echo payText($bk->payment_status); ?>
              </span>

              <?php if ((int)$bk->payment_status === 0 && (int)$bk->Status !== 2) { ?>
                <div class="mt-2">
                  <a class="btn-gold" href="payment.php?bkid=<?php echo urlencode($bk->bookingId); ?>">
                    <i class="fa fa-credit-card"></i> Make Payment
                  </a>
                </div>
              <?php } ?>
            </div>

          </div>

          <div class="line"></div>

          <div class="row g-3">
            <div class="col-md-6"><div class="label">From</div><div class="value"><?php echo $bk->FromDate; ?></div></div>
            <div class="col-md-6"><div class="label">To</div><div class="value"><?php echo $bk->ToDate; ?></div></div>
            <div class="col-md-6"><div class="label">Days</div><div class="value"><?php echo $days; ?></div></div>
            <div class="col-md-6"><div class="label">Booked On</div><div class="value"><?php echo $bk->PostingDate; ?></div></div>
          </div>

          <div class="line"></div>

          <div class="row g-3">
            <div class="col-md-4"><div class="label">Fuel</div><div class="value"><?php echo $bk->FuelType; ?></div></div>
            <div class="col-md-4"><div class="label">Seats</div><div class="value"><?php echo $bk->SeatingCapacity; ?></div></div>
            <div class="col-md-4"><div class="label">Year</div><div class="value"><?php echo $bk->ModelYear; ?></div></div>
          </div>

          <div class="line"></div>

          <div class="p-3" style="background:#121212; border:1px solid #2a2a2a;">
            <div class="d-flex justify-content-between">
              <div class="label">Estimated Total</div>
              <div class="value">RM <?php echo number_format($total, 2); ?></div>
            </div>
          </div>

          <div class="line"></div>

          <div class="label mb-2">Message</div>
          <div style="background:#121212; border:1px solid #2a2a2a; padding:14px; color:#ddd;">
            <?php echo !empty($bk->message) ? nl2br(htmlentities($bk->message)) : '<span style="color:#888;">No message.</span>'; ?>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<?php include(__DIR__ . '/includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
