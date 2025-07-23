<?php
require '../db.php';
session_start();
if (isset($_SESSION['user_id'])) header('Location: ../index.php');
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $user = $db->users->findOne(['email'=>$_POST['email']]);
    if ($user && password_verify($_POST['password'], $user['password_hash'])) {
        $_SESSION['user_id'] = (string)$user['_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: ../'.$_SESSION['user_role'].'/dashboard.php');
        exit;
    } else {
        $msg = "Invalid email or password.";
    }
}
?>
<?php include '../assets/header.html'; ?>
<div class="container mt-5">
  <div class="col-md-5 mx-auto card p-4 shadow-sm">
    <h2 class="mb-3">Login</h2>
    <?php if (isset($msg)) echo '<div class="alert alert-danger">'.$msg.'</div>'; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button class="btn btn-primary w-100" type="submit">Login</button>
    </form>
    <div class="mt-3">
      Don't have an account? <a href="register.php">Register</a>
    </div>
  </div>
</div>
<?php include '../assets/footer.html'; ?>