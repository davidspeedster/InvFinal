<?php
require '../db.php'; session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'investor') {
    header("Location: ../auth/login.php"); exit;
}
$inv = $db->investments->findOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['id'])]);
if (!$inv) { echo "Investment not found."; exit; }
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $db->funds->insertOne([
        'investment_id'=>$inv['_id'],
        'user_id'=>new MongoDB\BSON\ObjectId($_SESSION['user_id']),
        'amount'=>(float)$_POST['amount'],
        'created_at'=>new MongoDB\BSON\UTCDateTime()
    ]);
    $msg = "Investment successful!";
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title><?= htmlspecialchars($inv['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2><?= htmlspecialchars($inv['title']) ?></h2>
  <p><?= htmlspecialchars($inv['description']) ?></p>
  <ul>
    <li><b>Sector:</b> <?= htmlspecialchars($inv['sector']) ?></li>
    <li><b>Funding Needed:</b> <?= htmlspecialchars($inv['funding_needed']) ?></li>
    <li><b>Status:</b> <?= htmlspecialchars($inv['status']) ?></li>
  </ul>
  <?php if (isset($msg)) echo '<div class="alert alert-success">'.$msg.'</div>'; ?>
  <form method="post" class="mt-3">
    <input type="number" step="0.01" name="amount" placeholder="Amount to Invest" class="form-control mb-2" required>
    <button type="submit" class="btn btn-success">Invest</button>
  </form>
  <a href="browse-investments.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body></html>