<?php $page_title = "Investment Details";
include 'header.php'; ?>
<?php
require 'db.php';
if (!isset($_GET['id'])) {
    // Optionally, redirect to not-found
    die('No investment selected.');
}
$id = $_GET['id'];
try {
    $investment = $db->investments->findOne(['_id' => new MongoDB\BSON\ObjectId($id), 'status' => 'approved']);
} catch (Exception $e) {
    $investment = null;
}
if (!$investment) {
    echo '<div style="padding:2em;text-align:center;"><h3>Investment Not Found</h3><a href="investment.php">Back to Investments</a></div>';
    exit;
}
// Optionally, fetch the business owner's info:
$owner = $db->users->findOne(['_id' => $investment['user_id']]);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($investment['title']) ?> | InvestHub</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/responsive.css" rel="stylesheet">
    <link href="assets/css/color-switcher-design.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="page-wrapper">
        <!-- Main Header (copy from your UI) -->

        <!-- Investment Detail Section -->
        <section class="investment-detail-section py-5" style="background:#f9f9fb;">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-12 col-sm-12 mx-auto">
                        <div class="card shadow-lg p-4">
                            <div class="mb-4">
                                <span class="badge bg-primary"><?= ucfirst($investment['sector']) ?></span>
                            </div>
                            <h2 class="mb-3"><?= htmlspecialchars($investment['title']) ?></h2>
                            <div class="mb-3 text-muted">Requested by: <?= $owner ? htmlspecialchars($owner['name']) : 'Unknown' ?></div>
                            <div class="mb-4 fs-5"><?= nl2br(htmlspecialchars($investment['description'])) ?></div>
                            <div class="mb-3">
                                <strong>Funding Needed:</strong>
                                <span class="fs-4 text-success">$<?= number_format($investment['funding_needed'], 2) ?></span>
                            </div>
                            <!-- Add more business fields as needed -->
                            <div class="mb-4">
                                <strong>Status:</strong>
                                <?= ucfirst($investment['status']) ?>
                            </div>
                            <a href="investment-listed.php?sector=<?= urlencode($investment['sector']) ?>" class="btn btn-outline-secondary mb-2">Back to <?= ucfirst($investment['sector']) ?> investments</a>
                            <a href="investment.php" class="btn btn-link mb-2">All Investment Categories</a>
                            <!-- Investment form/button can go here -->
                            <!--
                        <a href="invest.php?id=<?= $investment['_id'] ?>" class="btn btn-success">Invest Now</a>
                        -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Investment Detail Section -->
        <?php include 'footer.php'; ?>
        <!-- (Optional) Call-to-Action, Counters, Footer, etc. from your UI -->

    </div>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>