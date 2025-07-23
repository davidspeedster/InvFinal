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

    <!-- Page Title -->
    <section class="page-title">
      <div class="page-title_shapes" style="background-image:url(../assets/images/background/page-title_bg.png)"></div>
      <div class="page-title_bg" style="background-image:url(../assets/images/background/page-title_bg.jpg)"></div>
      <div class="page-title_icons" style="background-image:url(../assets/images/background/page-title_icons.png)"></div>
      <div class="auto-container">
        <h2>Login</h2>
        <ul class="bread-crumb clearfix">
          <li><a href="index.php">Home</a></li>
          <li>Login</li>
        </ul>
      </div>
    </section>
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
  <?php include '../footer.php'; ?>
</body>

</html>