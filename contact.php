<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $db->messages->insertOne([
        'name'=>$_POST['name'],
        'email'=>$_POST['email'],
        'message'=>$_POST['message'],
        'created_at'=>new MongoDB\BSON\UTCDateTime()
    ]);
    $msg = "Message sent!";
}
?>
<?php include 'assets/header.html'; ?>
<div class="container mt-5">
  <div class="col-md-7 mx-auto card p-4 shadow-sm">
    <h2 class="mb-3">Contact Us</h2>
    <?php if (isset($msg)) echo '<div class="alert alert-success">'.$msg.'</div>'; ?>
    <form method="POST">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Your Name" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Your Email" required>
      </div>
      <div class="mb-3">
        <textarea name="message" class="form-control" placeholder="Your Message" required></textarea>
      </div>
      <button class="btn btn-success" type="submit">Send</button>
    </form>
  </div>
</div>
<?php include 'assets/footer.html'; ?>