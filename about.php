<?php 
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Buat Kerja Betul2 Car Rental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* Keep same premium vibe as index.php */
    .page-hero {
      background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.75)),
        url('https://images.unsplash.com/photo-1553440569-bcc63803a83d?q=80&w=2070&auto=format&fit=crop');
      background-size: cover;
      background-position: center;
      padding: 90px 0;
      color: #fff;
      text-align: center;
    }
    .hero-title {
      font-size: 3rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
    }
    .hero-subtitle {
      font-size: 1.1rem;
      color: #f1f1f1;
      margin-top: 10px;
    }
    .accent-line {
      width: 90px;
      height: 3px;
      background: #f1c40f;
      margin: 18px auto 0;
      border: none;
    }

    .section-title {
      color: #2c3e50;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .text-soft { color: #7f8c8d; }

    .info-card {
      border: none;
      border-radius: 14px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.10);
      transition: 0.3s;
      height: 100%;
    }
    .info-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.14);
    }
    .icon-badge {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: rgba(241,196,15,0.18);
      color: #f1c40f;
      font-size: 1.1rem;
    }

    .stat-card {
      border: none;
      border-radius: 14px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      padding: 22px;
      background: #fff;
      height: 100%;
    }
    .stat-number {
      font-size: 1.8rem;
      font-weight: 900;
      color: #2c3e50;
    }
    .stat-label { color: #7f8c8d; font-size: 0.9rem; }

    .team-card {
      border: none;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 6px 18px rgba(0,0,0,0.10);
    }
    .team-img {
      height: 500px;
      object-fit: cover;
      width: 100%;
    }

    .btn-cta {
      background-color: #f1c40f;
      color: #2c3e50;
      padding: 12px 28px;
      font-weight: 800;
      border-radius: 50px;
      text-decoration: none;
      transition: 0.3s;
      border: none;
      display: inline-block;
    }
    .btn-cta:hover {
      background-color: #d4ac0d;
      color: #fff;
      transform: scale(1.04);
    }
    .btn-cta-outline {
      border: 2px solid #f1c40f;
      color: #f1c40f;
      padding: 10px 24px;
      font-weight: 800;
      border-radius: 50px;
      text-decoration: none;
      transition: 0.3s;
      display: inline-block;
      margin-left: 10px;
    }
    .btn-cta-outline:hover {
      background: #f1c40f;
      color: #2c3e50;
      transform: scale(1.03);
    }
  </style>
</head>

<body>

<?php include('includes/header.php');?>

<!-- HERO -->
<section class="page-hero">
  <div class="container">
    <h1 class="hero-title">About Us</h1>
    <p class="hero-subtitle">Buat Kerja Betul2 Car Rental — <span style="color:#f1c40f; font-weight:800;">More Than a Rental. It’s an Experience.</span></p>
    <hr class="accent-line">
  </div>
</section>

<!-- ABOUT INTRO -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-7">
        <h2 class="section-title mb-3">Who We Are</h2>
        <p class="text-soft mb-3">
          Buat Kerja Betul2 Car Rental is a simple and friendly car rental service based in Melaka.
          We focus on providing affordable prices, newer cars, and fast paperwork, so customers can rent without stress.
          Our goal is to make the rental process easy, safe, and smooth for everyone.
        </p>

        <div class="d-flex gap-3 flex-wrap mt-4">
          <div class="d-flex align-items-center gap-2">
            <span class="icon-badge"><i class="fa fa-tags"></i></span>
            <div>
              <div class="fw-bold" style="color:#2c3e50;">Cheap Price</div>
              <div class="text-soft" style="font-size:0.9rem;">Budget-friendly rates</div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <span class="icon-badge"><i class="fa fa-car-side"></i></span>
            <div>
              <div class="fw-bold" style="color:#2c3e50;">Newer Cars</div>
              <div class="text-soft" style="font-size:0.9rem;">Clean & comfortable</div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <span class="icon-badge"><i class="fa fa-bolt"></i></span>
            <div>
              <div class="fw-bold" style="color:#2c3e50;">Fast Process</div>
              <div class="text-soft" style="font-size:0.9rem;">Quick booking & paperwork</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="info-card p-4">
          <h5 class="fw-bold mb-3" style="color:#2c3e50;">What We Offer</h5>
          <ul class="list-unstyled mb-0">
            <li class="mb-3 d-flex gap-2">
              <i class="fa fa-check-circle" style="color:#f1c40f; margin-top:3px;"></i>
              <div>
                <div class="fw-bold">Daily Rental</div>
                <div class="text-soft" style="font-size:0.9rem;">Flexible daily plans for short trips</div>
              </div>
            </li>

            <li class="mb-3 d-flex gap-2">
              <i class="fa fa-check-circle" style="color:#f1c40f; margin-top:3px;"></i>
              <div>
                <div class="fw-bold">Weekly / Monthly Rental</div>
                <div class="text-soft" style="font-size:0.9rem;">Better value for longer use</div>
              </div>
            </li>

            <li class="d-flex gap-2">
              <i class="fa fa-check-circle" style="color:#f1c40f; margin-top:3px;"></i>
              <div>
                <div class="fw-bold">Corporate Rental</div>
                <div class="text-soft" style="font-size:0.9rem;">Simple support for business needs</div>
              </div>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Why Choose Us</h2>
      <p class="text-soft">Trusted service with an easy booking experience.</p>
      <hr class="accent-line">
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="info-card p-4">
          <div class="icon-badge mb-3"><i class="fa fa-shield-halved"></i></div>
          <h5 class="fw-bold">Trusted</h5>
          <p class="text-soft mb-0">We aim to build trust with clear rental terms and responsible service.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="info-card p-4">
          <div class="icon-badge mb-3"><i class="fa fa-sack-dollar"></i></div>
          <h5 class="fw-bold">Affordable</h5>
          <p class="text-soft mb-0">Fair pricing and good value, suitable for students and working adults.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="info-card p-4">
          <div class="icon-badge mb-3"><i class="fa fa-calendar-check"></i></div>
          <h5 class="fw-bold">Easy Booking</h5>
          <p class="text-soft mb-0">Simple steps to book your car. No complicated process.</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-card p-4">
          <div class="icon-badge mb-3"><i class="fa fa-screwdriver-wrench"></i></div>
          <h5 class="fw-bold">Well-maintained Cars</h5>
          <p class="text-soft mb-0">We keep our cars clean and in good condition for a safer ride.</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-card p-4">
          <div class="icon-badge mb-3"><i class="fa fa-headset"></i></div>
          <h5 class="fw-bold">Friendly Support</h5>
          <p class="text-soft mb-0">If you need help, we reply and assist as fast as we can.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Quick Facts</h2>
      <p class="text-soft">A small start — but we are improving step by step.</p>
      <hr class="accent-line">
    </div>

    <div class="row g-4">
      <div class="col-md-3">
        <div class="stat-card text-center">
          <div class="stat-number">10+</div>
          <div class="stat-label">Customers Served</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card text-center">
          <div class="stat-number">8</div>
          <div class="stat-label">Cars Available</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card text-center">
          <div class="stat-number">0.1</div>
          <div class="stat-label">Years of Service</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card text-center">
          <div class="stat-number">Melaka</div>
          <div class="stat-label">Location</div>
        </div>
      </div>
    </div>

    <p class="text-center text-soft mt-4 mb-0" style="font-size:0.95rem;">
      *These numbers are for project / learning purpose.
    </p>
  </div>
</section>

<!-- TEAM -->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Our Team</h2>
      <p class="text-soft">We work together to deliver a better rental experience.</p>
      <hr class="accent-line">
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="team-card">
          <img 
            src="image/interviewpicture.png"
            class="team-img"
            onerror="this.src='https://placehold.co/1200x600?text=Team+Photo+Not+Found';"
          >
          <div class="p-4">
            <h5 class="fw-bold mb-2" style="color:#2c3e50;">Meet the People Behind the Service</h5>
            <p class="text-soft mb-0">
              We are a small team from Melaka. We focus on making the system easy to use,
              and we try our best to serve customers in a friendly and responsible way.
            </p>
          </div>
        </div>
        <p class="text-center text-soft mt-3 mb-0" style="font-size:0.9rem;">
          (Team image: <code>image/interviewpicture.png</code>)
        </p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-5 bg-dark text-white">
  <div class="container text-center">
    <h2 class="fw-bold mb-2" style="text-transform:uppercase; letter-spacing:1px;">
      Ready to book your ride?
    </h2>
    <p class="mb-4" style="color:#ddd;">
      Browse our cars and choose the best one for your trip.
    </p>

    <a href="car-listing.php" class="btn-cta">
      Browse Cars <i class="fa fa-arrow-right"></i>
    </a>
    <a href="contact.php" class="btn-cta-outline">
      Contact Us <i class="fa fa-envelope"></i>
    </a>
  </div>
</section>

<?php include('includes/footer.php');?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
