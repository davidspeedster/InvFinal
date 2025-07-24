<?php $page_title = "Investment"; ?>
<?php
// investment.php
// No authentication required
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>InvestHub</title>
  <!-- Stylesheets -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/responsive.css" rel="stylesheet">
  <link href="assets/css/color-switcher-design.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
  <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
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
    <!-- Main Header (as per your UI) -->
    <!-- ... copy from your uploaded file ... -->

    <!-- Services Four (categories) -->
    <section class="services-four">
      <div class="services-four_icon-one" style="background-image:url(assets/images/icons/icon-9.png)"></div>
      <div class="services-four_icon-two" style="background-image:url(assets/images/icons/icon-8.png)"></div>
      <div class="auto-container_two">
        <div class="sec-title centered">
          <div class="sec-title_title">Our Investment Propositions</div>
          <h2 class="sec-title_heading">We’re Offering a Platform <br> for Investment </h2>
        </div>
        <div class="row clearfix">

          <!-- Technology -->
          <div class="service-block_four col-lg-4 col-md-6 col-sm-12">
            <div class="service-block_four-inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
              <div class="service-block_four-icon">
                <img src="assets/images/icons/service-11.png" alt="" />
              </div>
              <h4 class="service-block_four-title">
                <a href="investment-listed.php?sector=Technology">Technology</a>
              </h4>
              <div class="service-block_four-text">Invest in innovative tech businesses and startups.</div>
              <a class="service-block_four-more" href="investment-listed.php?sector=Technology">
                <span>more</span><i class="flaticon-arrow"></i>
              </a>
            </div>
          </div>
          <!-- Agriculture -->
          <div class="service-block_four col-lg-4 col-md-6 col-sm-12">
            <div class="service-block_four-inner wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
              <div class="service-block_four-icon">
                <img src="assets/images/icons/service-12.png" alt="" />
              </div>
              <h4 class="service-block_four-title">
                <a href="investment-listed.php?sector=Agriculture">Agriculture</a>
              </h4>
              <div class="service-block_four-text">Support agricultural projects with high growth potential.</div>
              <a class="service-block_four-more" href="investment-listed.php?sector=Agriculture">
                <span>more</span><i class="flaticon-arrow"></i>
              </a>
            </div>
          </div>
          <!-- Construction -->
          <div class="service-block_four col-lg-4 col-md-6 col-sm-12">
            <div class="service-block_four-inner wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
              <div class="service-block_four-icon">
                <img src="assets/images/icons/service-13.png" alt="" />
              </div>
              <h4 class="service-block_four-title">
                <a href="investment-listed.php?sector=Construction">Construction</a>
              </h4>
              <div class="service-block_four-text">Discover construction businesses seeking investment.</div>
              <a class="service-block_four-more" href="investment-listed.php?sector=Construction">
                <span>more</span><i class="flaticon-arrow"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Services Four -->
    <?php include 'footer.php'; ?>
    <!-- (Counters, CTA, Footer, etc. — copy as in your original) -->

  </div>
  <!-- Scripts (unchanged) -->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- ... other scripts ... -->
</body>

</html>