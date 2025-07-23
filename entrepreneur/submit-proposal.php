<?php
require '../db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'entrepreneur') {
  header("Location: ../auth/login.php");
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db->investments->insertOne([
    'user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id']),
    'title' => $_POST['title'],
    'description' => $_POST['description'],
    'sector' => $_POST['sector'],
    'funding_needed' => (float)$_POST['funding_needed'],
    'status' => 'pending',
    'created_at' => new MongoDB\BSON\UTCDateTime()
  ]);
  $msg = "Proposal submitted!";
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Submit Proposal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container mt-5">
    <h2>Submit Business Proposal</h2>
    <?php if (isset($msg)) echo '<div class="alert alert-success">' . $msg . '</div>'; ?>
    <form method="POST">
      <div class="mb-3"><input class="form-control" name="title" placeholder="Title" required></div>
      <div class="mb-3"><textarea class="form-control" name="description" placeholder="Description" required></textarea></div>
      <select class="mb-3" name="sector" class="form-control" required>
        <option value="">-- Select Sector --</option>
        <option value="agriculture">Agriculture</option>
        <option value="technology">Technology</option>
        <option value="construction">Construction</option>
      </select>
      <div class="mb-3"><input class="form-control" name="funding_needed" placeholder="Funding Needed" type="number" required></div>
      <button class="btn btn-primary" type="submit">Submit</button>
    </form>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back</a>
  </div>
</body>

</html>