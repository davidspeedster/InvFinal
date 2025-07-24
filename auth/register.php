<?php
require '../db.php'; // Ensure header.php includes db.php
session_start();

$registerError = '';
$registerSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['reg_username']);
  $email = trim($_POST['reg_email']);
  $password = $_POST['reg_password'];
  $role = $_POST['reg_role'] ?? 'investor';

  if (!$username || !$email || !$password) {
    $registerError = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $registerError = "Invalid email address.";
  } elseif ($db->users->findOne(['$or' => [['username' => $username], ['email' => $email]]])) {
    $registerError = "Username or email already taken.";
  } else {
    $db->users->insertOne([
      'username' => $username,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'role' => $role,
      'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    $registerSuccess = "Registration successful! You can <a href='login.php'>log in now</a>.";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Register | InvestHub</title>
  <link href="../assets/css/bootstrap.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <link href="../assets/css/responsive.css" rel="stylesheet">
  <link href="../assets/css/color-switcher-design.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
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
    <!-- Register Section -->
    <section class="register-one">
      <div class="auto-container">
        <div class="inner-container" style="max-width:480px;margin:0 auto;">
          <div class="title-box text-center mb-4">
            <h2>Sign Up for InvestHub</h2>
          </div>
          <div class="styled-form bg-white rounded-4 shadow-sm p-4">
            <?php if ($registerError): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($registerError) ?></div>
            <?php elseif ($registerSuccess): ?>
              <div class="alert alert-success"><?= $registerSuccess ?></div>
            <?php endif; ?>
            <form method="post" action="">
              <div class="form-group mb-3">
                <input type="text" name="reg_username" value="<?= isset($_POST['reg_username']) ? htmlspecialchars($_POST['reg_username']) : '' ?>" placeholder="Username*" class="form-control form-control-lg" required>
              </div>
              <div class="form-group mb-3">
                <input type="email" name="reg_email" value="<?= isset($_POST['reg_email']) ? htmlspecialchars($_POST['reg_email']) : '' ?>" placeholder="Email address*" class="form-control form-control-lg" required>
              </div>
              <div class="form-group mb-3">
                <input type="password" name="reg_password" placeholder="Password*" class="form-control form-control-lg" required>
              </div>
              <div class="form-group mb-3">
                <select name="reg_role" class="form-control form-control-lg" required>
                  <option value="">Select Role*</option>
                  <option value="investor" <?= (isset($_POST['reg_role']) && $_POST['reg_role'] == 'investor') ? 'selected' : '' ?>>Investor</option>
                  <option value="entrepreneur" <?= (isset($_POST['reg_role']) && $_POST['reg_role'] == 'entrepreneur') ? 'selected' : '' ?>>Entrepreneur / Business</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <div class="check-box">
                  <input type="checkbox" name="terms" id="type-2" required>
                  <label for="type-2">I agree to the Terms and Privacy Policy</label>
                </div>
              </div>
              <div class="form-group mb-0">
                <button class="theme-btn submit-btn w-100" type="submit">Sign Up</button>
              </div>
              <div class="text-center mt-3">
                <span>Already have an account? <a href="login.php">Login here</a></span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- End Register Section -->
    <!-- Footer Style -->
    <footer class="main-footer alternate" style="background-image: url(../assets/images/background/footer-bg.jpg)">
      <div class="footer-icon-four" style="background-image: url(../assets/images/icons/footer-icon-4.png)"></div>
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
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../about.php">About us</a></li>
                        <li><a href="../investment.php">Investment</a></li>
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
  </div>
</body>

</html>