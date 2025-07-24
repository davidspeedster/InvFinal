<?php
require_once '../db.php'; // MongoDB connection
session_start();
$message = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    // Sanitize & collect form data
    $businessName = trim($_POST['business_name']);
    $tinNumber = trim($_POST['tin_number']);
    $contact = trim($_POST['contact']);
    $founderName = trim($_POST['founder_name']);
    $founderBirthdate = trim($_POST['founder_birthdate']);
    $sector = trim($_POST['sector']);
    $shortDesc = trim($_POST['short_description']);
    $description = trim($_POST['description']);
    $requestedAmount = floatval($_POST['requested_amount']);

    if (!$businessName || !$tinNumber || !$contact || !$founderName || !$founderBirthdate || !$sector || !$shortDesc || !$description || !$requestedAmount) {
      throw new Exception("Please complete all required fields.");
    }

    // Handle logo upload
    $logoFilename = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
      $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
      $logoFilename = uniqid('logo_', true) . '.' . $ext;
      move_uploaded_file($_FILES['logo']['tmp_name'], "../uploads/" . $logoFilename);
    }

    // Store to MongoDB
    $db->investments->insertOne([
      'user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id']),
      'business_name' => $businessName,
      'tin_number' => $tinNumber,
      'contact' => $contact,
      'founder_name' => $founderName,
      'founder_birthdate' => $founderBirthdate,
      'sector' => $sector,
      'short_description' => $shortDesc,
      'description' => $description,
      'requested_amount' => $requestedAmount,
      'logo' => $logoFilename,
      'created_at' => new MongoDB\BSON\UTCDateTime(),
      'status' => 'pending'
    ]);

    $message = "Business proposal submitted successfully.";
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Submit Proposal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 bg-light page-wrapper">
  <header class="dashboard-header py-2 mb-3 sticky-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <a href="dashboard.php" class="d-flex align-items-center text-decoration-none">
        <img src="../assets/images/favicon.png" style="width:32px;height:32px;" class="me-2" alt="InvestHub">
        <span class="fw-bold" style="font-size:1.3rem;color:#32429a;">InvestHub</span>
      </a>
      <nav>
        <ul class="nav">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link active" href="view-submissions.php">My Proposals</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
        </ul>
      </nav>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
          id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../assets/images/resource/user.png" alt="profile" class="rounded-circle" style="width:34px;height:34px;">
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Manage Profile</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <form method="post" action="../auth/logout.php" class="d-inline">
              <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <section class="contact-two pt-5 pb-5">
    <div class="auto-container">
      <div class="inner-container">

        <div class="title-box text-center mb-4">
          <h4>Complete the Form to Submit Your Business</h4>
        </div>

        <?php if ($message): ?>
          <div class="alert alert-success text-center"><?= htmlspecialchars($message) ?></div>
        <?php elseif ($error): ?>
          <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data" class="contact-form" id="proposal-form">
          <div class="row">

            <!-- Business Name -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Business Name *</label>
              <input type="text" name="business_name" class="form-control form-control-lg" placeholder="Your Business Name" required>
            </div>

            <!-- TIN Number -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Business TIN Number *</label>
              <input type="text" name="tin_number" class="form-control form-control-lg" placeholder="1234567890" required>
            </div>

            <!-- Logo Upload -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Business Logo (JPG/PNG)</label>
              <input type="file" name="logo" accept="image/*" class="form-control-file">
            </div>

            <!-- Contact -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Business Contact Info *</label>
              <input type="text" name="contact" class="form-control form-control-lg" placeholder="+2519XXXXXX" required>
            </div>

            <!-- Founder Name -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Founder Full Name *</label>
              <input type="text" name="founder_name" class="form-control form-control-lg" placeholder="John Doe" required>
            </div>

            <!-- Founder Birthdate -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Founder Birthdate *</label>
              <input type="date" name="founder_birthdate" class="form-control form-control-lg" required>
            </div>

            <!-- Sector -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Business Sector *</label>
              <select name="sector" class="form-control form-control-lg" required>
                <option value="" disabled selected>Select Sector</option>
                <option value="technology">Technology</option>
                <option value="agriculture">Agriculture</option>
                <option value="construction">Construction</option>
              </select>
            </div>

            <!-- Requested Amount -->
            <div class="form-group col-md-6 mb-4">
              <label class="form-label">Requested Investment Amount ($)*</label>
              <input type="number" name="requested_amount" class="form-control form-control-lg" step="0.01" placeholder="50000" required>
            </div>

            <!-- Short Description -->
            <div class="form-group col-12 mb-4">
              <label class="form-label">Short Business Summary *</label>
              <textarea name="short_description" class="form-control form-control-lg" rows="2" placeholder="A short summary of your business" required></textarea>
            </div>

            <!-- Full Description -->
            <div class="form-group col-12 mb-4">
              <label class="form-label">Business Description / Pitch *</label>
              <textarea name="description" class="form-control form-control-lg" rows="6" placeholder="Include your pitch and business plan here..." required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-group col-12 text-center">
              <button type="submit" class="theme-btn btn-style-one">
                <span class="btn-wrap">
                  <span class="text-one">Submit Proposal</span>
                  <span class="text-two">Submit Proposal</span>
                </span>
              </button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </section>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<footer class="dashboard-footer mt-auto py-3 bg-light border-top" style="font-size: 0.98rem;">
  <div class="container text-center text-muted">
    <span>
      &copy; <?= date('Y') ?> <b>InvestHub</b>. All rights reserved.
    </span>

  </div>
</footer>

</html>