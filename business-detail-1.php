<?php
require 'db.php';
if (!isset($_GET['id'])) { echo "Business not found."; exit; }
$biz = $db->investments->findOne(['_id'=>new MongoDB\BSON\ObjectId($_GET['id'])]);
if (!$biz) { echo "Business not found."; exit; }
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title><?= htmlspecialchars($biz['title']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2><?= htmlspecialchars($biz['title']) ?></h2>
  <div><?= nl2br(htmlspecialchars($biz['description'])) ?></div>
  <ul>
    <li><b>Sector:</b> <?= htmlspecialchars($biz['sector']) ?></li>
    <li><b>Funding Needed:</b> <?= htmlspecialchars($biz['funding_needed']) ?></li>
    <li><b>Status:</b> <?= htmlspecialchars($biz['status']) ?></li>
  </ul>
  <a href="investment.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body></html>