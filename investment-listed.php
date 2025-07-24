<?php
require 'db.php';
$sector = isset($_GET['sector']) ? strtolower($_GET['sector']) : '';
if (!in_array($sector, ['technology', 'agriculture', 'construction'])) {
  $sector = 'technology'; // Default/fallback
}
$cursor = $db->investments->find(['status' => 'approved', 'sector' => $sector]);
$investments = iterator_to_array($cursor, false); // Convert cursor to array ONCE
$sectorLabel = ucfirst($sector);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title><?= $sectorLabel ?> Investments | InvestHub</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/responsive.css" rel="stylesheet">
  <!-- ... styles and meta as per your design ... -->
</head>

<body>
  <div class="page-wrapper">
    <header class="main-header header-style-two">

      <!-- Header Upper -->
      <div class="header-upper">
        <div class="auto-container">
          <div class="inner-container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div class="nav-outer d-flex align-items-center flex-wrap">
                <!-- Logo Box -->
                <div class="logo-box">
                  <div class="logo"><a href="index.php">
                      <h6>InvestHub</h6>
                    </a></div>
                </div>

                <!-- Main Menu -->
                <nav class="main-menu navbar-expand-md">
                  <div class="navbar-header">
                    <!-- Toggle Button -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                  </div>

                  <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                    <ul class="navigation clearfix">
                      <li><a href="index.php">Home</a> </li>
                      <li><a href="about.php">About us</a></li>
                      <li><a href="investment.php">Investment</a></li>
                      <li><a href="blog.php">Blog</a></li>
                      <li><a href="contact.php">Contact</a></li>
                    </ul>
                  </div>
                </nav>
              </div>

              <!-- Main Menu End-->
              <div class="outer-box d-flex align-items-center flex-wrap">

                <!-- Button Box with Dropdown -->
                <!-- Make sure Bootstrap 5 CSS & JS are loaded! -->
                <div class="header_button-box">
                  <div class="dropdown">
                    <button class="btn theme-btn dropdown-toggle px-4 py-2" type="button" id="signinDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight:600; border-radius:30px;">
                      <i class="bi bi-person-circle me-2"></i>Sign In
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow rounded-3 mt-2" aria-labelledby="signinDropdown" style="min-width:170px;">
                      <li>
                        <a class="dropdown-item py-2" href="auth/login.php">
                          <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item py-2" href="auth/register.php">
                          <i class="bi bi-person-plus me-2"></i>Register
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>


                <!-- Mobile Navigation Toggler -->
                <div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <!--End Header Upper-->

      <!-- Mobile Menu  -->
      <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="icon flaticon-close-1"></span></div>

        <nav class="menu-box">
          <div class="nav-logo"><a href="index-2.php"><img src="assets/images/mobile-logo.png" alt="" title=""></a></div>
          <div class="menu-outer"></div>
        </nav>
      </div>
      <!-- End Mobile Menu -->

    </header>
    <!-- Main Header (copy from your design) -->
    <!-- ... -->

    <!-- Services Three -->
    <section class="services-three">
      <div class="services-three_icon-one" style="background-image:url(assets/images/icons/icon-2.png)"></div>
      <div class="services-three_icon-two" style="background-image:url(assets/images/icons/icon-3.png)"></div>
      <div class="auto-container">
        <div class="sec-title centered">
          <h2 class="sec-title_heading"><?= $sectorLabel ?></h2>
        </div>
        <div class="row clearfix">
          <?php foreach ($investments as $inv): ?>
            <div class="service-block_three col-lg-4 col-md-6 col-sm-12">
              <div class="service-block_three-inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                <div class="service-block_three-upper-box d-flex align-items-center">
                  <div class="service-block_three-icon">
                    <img src="assets/images/icons/service-<?= $sector === 'Technology' ? '11' : ($sector === 'Agriculture' ? '12' : '13') ?>.png" alt="" />
                  </div>
                  <h4 class="service-block_three-heading">
                    <a href="investment-detail.php?id=<?= $inv['_id'] ?>">
                      <?= htmlspecialchars($inv['business_name']) ?>
                    </a>
                  </h4>
                </div>
                <div class="service-block_three-text"><?= htmlspecialchars($inv['description']) ?></div>
                <a class="service-block_three-more" href="investor/dashboard.php">
                  <span>Invest Now!</span><i class="flaticon-arrow"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if (empty($investments)): ?>
            <div class="col-12">
              <div class="alert alert-warning">No investments found in this sector yet.</div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <!-- End Services Three -->
    <?php include 'footer.php'; ?>
    <!-- (Counters, CTA, Footer — copy as in your UI) -->

  </div>
  <!-- Scripts (unchanged) -->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- ... -->
</body>

</html>