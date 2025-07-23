<?php
require '../db.php';
session_start();
if (isset($_SESSION['user_id'])) header('Location: ../index.php');
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $exists = $db->users->findOne(['email'=>$_POST['email']]);
    if ($exists) {
        $msg = "Email already registered.";
    } else {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $db->users->insertOne([
            'name'=>$_POST['name'],
            'email'=>$_POST['email'],
            'password_hash'=>$hash,
            'role'=>$_POST['role'],
            'created_at'=>new MongoDB\BSON\UTCDateTime()
        ]);
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<?php include '../assets/header.html'; ?>
<div class="container mt-5">
  <div class="col-md-5 mx-auto card p-4 shadow-sm">
    <h2 class="mb-3">Sign Up</h2>
    <?php if (isset($msg)) echo '<div class="alert alert-danger">'.$msg.'</div>'; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="mb-3">
        <select name="role" class="form-control" required>
          <option value="">Register as...</option>
          <option value="entrepreneur">Entrepreneur</option>
          <option value="investor">Investor</option>
        </select>
      </div>
      <button class="btn btn-primary w-100" type="submit">Register</button>
    </form>
    <div class="mt-3">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>
</div>
<?php include '../assets/footer.html'; ?>