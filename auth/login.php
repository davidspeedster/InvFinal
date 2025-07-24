<?php
require '../db.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  if (!$username || !$password) {
    $error = "Please enter both username/email and password.";
  } else {
    $user = $db->users->findOne([
      '$or' => [
        ['username' => $username],
        ['email' => $username]
      ]
    ]);
    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = (string)$user['_id'];
      $_SESSION['role'] = $user['role'];
      // Redirect by role
      if ($user['role'] == 'admin') {
        header('Location: ../admin/dashboard.php');
      } elseif ($user['role'] == 'entrepreneur') {
        header('Location: ../entrepreneur/dashboard.php');
      } else {
        header('Location: ../investor/dashboard.php');
      }
      exit;
    } else {
      $error = "Invalid credentials.";
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login | InvestHub</title>
  <link href="../assets/css/bootstrap.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <link href="../assets/css/responsive.css" rel="stylesheet">
  <link href="../assets/css/color-switcher-design.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                      <li><a href="../index.php">Home</a> </li>
                      <li><a href="../about.php">About us</a></li>
                      <li><a href="../investment.php">Investment</a></li>
                      <li><a href="../blog.php">Blog</a></li>
                      <li><a href="../contact.php">Contact</a></li>
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
                        <a class="dropdown-item py-2" href="login.php">
                          <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item py-2" href="register.php">
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
    <!-- Page Title -->
    <!-- End Page Title -->

    <!-- Login Form Section -->
    <section class="register-one">
      <div class="auto-container">
        <div class="inner-container" style="max-width:420px;margin:0 auto;">
          <div class="title-box text-center mb-4">
            <h2>Login to Your Account</h2>
          </div>
          <div class="styled-form bg-white rounded-4 shadow-sm p-4">
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="">
              <div class="form-group mb-3">
                <input type="text" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" placeholder="Username or Email*" class="form-control form-control-lg" required>
              </div>
              <div class="form-group mb-3">
                <input type="password" name="password" placeholder="Password*" class="form-control form-control-lg" required>
              </div>
              <div class="form-group d-flex align-items-center flex-wrap mb-2">
                <input type="checkbox" name="remember" id="remember" style="margin-right:6px;">
                <label for="remember" style="margin-bottom:0;">Remember Me?</label>
              </div>
              <div class="form-group mb-0">
                <button class="theme-btn submit-btn w-100" type="submit">Login</button>
              </div>
              <div class="text-center mt-3">
                <a class="forgot-psw" href="#">Forgot your password?</a>
                <br>
                <span>Don’t have an account? <a href="register.php">Register here</a></span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- End Login Form Section -->

  </div>
  <script src="../assets/js/jquery.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/script.js"></script>

  <!-- Footer Style -->
  <footer class="main-footer alternate" style="background-image: url(assets/images/background/footer-bg.jpg)">
    <div class="footer-icon-four" style="background-image: url(assets/images/icons/footer-icon-4.png)"></div>
    <div class="footer-icon-five" style="background-image: url(assets/images/icons/footer-icon-5.png)"></div>
    <div class="auto-container">
      <div class="inner-container">
        <!-- Widgets Section -->
        <div class="widgets-section">
          <div class="row clearfix">

            <!-- Big Column -->
            <div class="big-column col-lg-6 col-md-12 col-sm-12">
              <div class="row clearfix">

                <!-- Footer Column -->
                <div class="footer-column col-lg-7 col-md-6 col-sm-12">
                  <div class="footer-widget logo-widget">
                    <div class="footer-logo"><a href="index.php">
                        <h5>InvestHub</h5>
                      </a></div>
                  </div>
                </div>

                <!-- Footer Column -->
                <div class="footer-column col-lg-5 col-md-6 col-sm-12">
                  <div class="footer-widget links-widget">
                    <h5 class="footer-title">Company</h5>
                    <ul class="footer-list">
                      <li><a href="index.php">Home</a></li>
                      <li><a href="about.php">About us</a></li>
                      <li><a href="investment.php">Investment</a></li>
                      <li><a href="#">Privacy Policy</a></li>
                    </ul>
                  </div>
                </div>

              </div>
            </div>

            <!-- Big Column -->
            <div class="big-column col-lg-6 col-md-12 col-sm-12">
              <div class="row clearfix">

                <!-- Footer Column -->
                <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                  <div class="footer-widget links-widget">
                    <h5 class="footer-title">Links</h5>
                    <ul class="footer-list">
                      <li><a href="faq.php">Faq’s</a></li>
                      <li><a href="about.php">Meet our team</a></li>
                      <li><a href="blog.php">Latest news</a></li>
                      <li><a href="contact.php">Contact</a></li>
                    </ul>
                  </div>
                </div>

                <!-- Footer Column -->
                <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                  <div class="footer-widget newsletter-widget">
                    <h5 class="footer-title">Our Address</h5>
                    <div class="main-footer_text">Unity University, Addis Ababa</div>
                    <div class="address_info">
                      investhub@gmail.com <br>
                      <a href="tel:+88-10.11.44.574">(+251)-965-60-7061</a>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <div class="copyright">copyright &copy; 2025 All rights reserved</div>
          <ul class="footer-nav">
            <li><a href="#">Terms of use</a></li>
            <li><a href="#">cookies</a></li>
            <li><a href="#">Privacy Policy</a></li>
          </ul>
        </div>
      </div>

    </div>
  </footer>
  <!-- End Footer Style -->

  <!-- Options Palate -->
  <div class="color-palate">
    <div class="color-trigger">
      <i class="fa fa-gear"></i>
    </div>
    <div class="color-palate-head">
      <h6>Choose Options</h6>
    </div>
    <h6>RTL Version</h6>
    <ul class="rtl-version option-box">
      <li class="rtl">RTL Version</li>
      <li>LTR Version</li>
    </ul>
    <h6>Boxed Version</h6>
    <ul class="box-version option-box">
      <li class="box">Boxed</li>
      <li>Full width</li>
    </ul>
    <h6>Want Sticky Header</h6>
    <ul class="header-version option-box">
      <li class="box">No</li>
      <li>Yes</li>
    </ul>
  </div>
  <!-- End Options Palate -->

  </div>
  <!-- End PageWrapper -->

  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
  </div>


  <?php /* Rest of the original HTML, unchanged, goes here */ ?>

  <!-- End PageWrapper -->

  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
  </div>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Scripts -->
</body>

</html>