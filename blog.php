<?php
require 'db.php';
$blogs = $db->blog->find([]);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>Blog</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Our Blog</h2>
  <?php foreach ($blogs as $b): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5><?= htmlspecialchars($b['title']) ?></h5>
        <div><?= nl2br(htmlspecialchars($b['content'])) ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</body></html>