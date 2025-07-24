<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php'; // Make sure this connects $db to MongoDB

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $msg = trim($_POST['message'] ?? '');

  if ($username && filter_var($email, FILTER_VALIDATE_EMAIL) && $msg) {
    // Insert into MongoDB messages collection
    $db->messages->insertOne([
      'name' => $username,
      'email' => $email,
      'message' => $msg,
      'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    $message = "Thank you for contacting us! We have received your message.";
  } else {
    $error = "Please fill all fields with valid information.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Contact | InvestHub</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/responsive.css" rel="stylesheet">
  <link href="assets/css/color-switcher-design.css" rel="stylesheet">
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
    <!-- Page Title -->
    <section class="page-title">
      <div class="page-title_shapes" style="background-image:url(assets/images/background/page-title_bg.png)"></div>
      <div class="page-title_bg" style="background-image:url(assets/images/background/page-title_bg.jpg)"></div>
      <div class="page-title_icons" style="background-image:url(assets/images/background/page-title_icons.png)"></div>
      <div class="auto-container">
        <h2>Contact</h2>
        <ul class="bread-crumb clearfix">
          <li><a href="index.php">Home</a></li>
          <li>Contact Us</li>
        </ul>
      </div>
    </section>
    <!-- End Page Title -->

    <!-- Map One -->
    <section class="map-one">
      <div class="map-outer">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001.693643569012!2d38.805187473528136!3d9.001105889428025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b852528b8f94d%3A0xb932691d903f7674!2sUnity%20University%20Gerji!5e1!3m2!1sen!2set!4v1749430960216!5m2!1sen!2set" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </section>
    <!-- End Map One -->

    <!-- Contact Info -->
    <section class="contact-one">
      <div class="contact-one_icon" style="background-image:url(assets/images/icons/icon-11.png)"></div>
      <div class="auto-container">
        <div class="title-box">
          <h4>Are you ready to invest better? How <br> can we help?</h4>
        </div>
        <div class="row clearfix">

          <!-- Info Block One -->
          <div class="info-block_one col-lg-4 col-md-6 col-sm-12">
            <div class="info-block_one-inner">
              <div class="info-block_one-icon">
                <i class="flaticon-personal"></i>
              </div>
              <h5>Plan member queries:</h5>
              <div class="text">12/6 Suite 80 Golden Street Germeney</div>
              <div class="contact-info">Email: financial.planning@gmail.com</div>
            </div>
          </div>

          <!-- Info Block Two -->
          <div class="info-block_one col-lg-4 col-md-6 col-sm-12">
            <div class="info-block_one-inner">
              <div class="info-block_one-icon">
                <i class="flaticon-phone-call"></i>
              </div>
              <h5>Have any question</h5>
              <a class="phone_number" href="tel:+88017-00-14-999">+88 017 00 14 999</a> <br>
              <a class="phone_number" href="tel:+88-017-27-44-222">+88 017 27 44 222</a>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- End Contact Info -->

    <!-- Contact Form Section -->
    <section class="contact-two" style="padding:60px 0; background:#f7f8fc;">
      <div class="auto-container">
        <div class="inner-container" style="max-width: 650px; margin: 0 auto;">
          <div class="title-box text-center mb-4">
            <h4>Complete the Form Below to <br> Get in Touch Today</h4>
          </div>

          <?php if ($message): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($message) ?></div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <div class="contact-form">
            <form method="post" action="" id="contact-form">
              <div class="form-group">
                <input type="text" name="username" placeholder="Your Name" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
              </div>
              <div class="form-group ">
                <input type="email" name="email" placeholder="Email address" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
              </div>
              <div class="form-group">
                <textarea name="message" placeholder="Type your comment" required rows="4"><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
              </div>
              <div class="form-group text-center">
                <div class="button-box">
                  <button type="submit" class="theme-btn btn-style-one">
                    <span class="btn-wrap">
                      <span class="text-one">Send Message</span>
                      <span class="text-two">Send Message</span>
                    </span>
                  </button>
                </div>
              </div>
            </form>
          </div>
          <!-- End Contact Form -->
        </div>
      </div>
    </section>
    <!-- End Contact Form Section -->

  </div>
  <!-- Scripts -->

  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/script.js"></script>
  <?php include 'footer.php'; ?>
</body>

</html>