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

  </div>
  <?php include '../footer.php'; ?>
</body>

</html>