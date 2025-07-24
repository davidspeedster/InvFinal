<?php
require 'db.php'; // <-- your MongoDB connection
$blogs = $db->blog->find([], ['sort' => ['created_at' => -1]]);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>InvestHub Blog</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/responsive.css" rel="stylesheet">
  <link href="assets/css/color-switcher-design.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
  <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

</head>

<body>
  <div class="page-wrapper">
    <header class="main-header header-style-two">
      <!-- Header Upper -->
      <div class="header-upper">
        <div class="auto-container">
          <div class="inner-container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div class="nav-outer d-flex align-items-center flex-wrap">
                <!-- Logo Box -->
                <div class="logo-box">
                  <div class="logo"><a href="index.php">
                      <h6>InvestHub</h6>
                    </a></div>
                </div>

                <!-- Main Menu -->
                <nav class="main-menu navbar-expand-md">
                  <div class="navbar-header">
                    <!-- Toggle Button -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                  </div>

                  <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                    <ul class="navigation clearfix">
                      <li><a href="index.php">Home</a> </li>
                      <li><a href="about.php">About us</a></li>
                      <li><a href="investment.php">Investment</a></li>
                      <li><a href="blog.php">Blog</a></li>
                      <li><a href="contact.php">Contact</a></li>
                    </ul>
                  </div>
                </nav>
              </div>

              <!-- Main Menu End-->
              <div class="outer-box d-flex align-items-center flex-wrap">

                <!-- Button Box with Dropdown -->
                <!-- Make sure Bootstrap 5 CSS & JS are loaded! -->
                <div class="header_button-box">
                  <div class="dropdown">
                    <a href="auth/login.php" class="btn theme-btn dropdown-toggle px-4 py-2" type="button" id="signinDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight:600; border-radius:30px;">
                      <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                  </div>
                </div>


                <!-- Mobile Navigation Toggler -->
                <div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <!--End Header Upper-->

      <!-- Mobile Menu  -->
      <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="icon flaticon-close-1"></span></div>

        <nav class="menu-box">
          <div class="nav-logo"><a href="index-2.php"><img src="assets/images/mobile-logo.png" alt="" title=""></a></div>
          <div class="menu-outer"></div>
        </nav>
      </div>
      <!-- End Mobile Menu -->

    </header>
    <!-- Optionally: -->

    <!-- Page Title -->
    <section class="page-title">
      <div class="page-title_shapes" style="background-image:url(assets/images/background/page-title_bg.png)"></div>
      <div class="page-title_bg" style="background-image:url(assets/images/background/page-title_bg.jpg)"></div>
      <div class="page-title_icons" style="background-image:url(assets/images/background/page-title_icons.png)"></div>
      <div class="auto-container">
        <h2>Blog</h2>
      </div>
    </section>
    <!-- End Page Title -->

    <!-- News Three -->
    <section class="news-three">
      <div class="news-three_icons" style="background-image:url(assets/images/background/news-three_icons.png)"></div>
      <div class="auto-container">
        <div class="row clearfix">

          <?php foreach ($blogs as $blog): ?>
            <div class="news-block_two col-xl-4 col-lg-6 col-md-6 col-sm-12">
              <div class="news-block_two-inner">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <ul class="news-block_two-meta">
                    <li>
                      <a href="#">
                        <?= isset($blog['created_at']) ? date('d M Y', $blog['created_at']->toDateTime()->getTimestamp()) : '' ?>
                      </a>
                    </li>
                    <li>
                      <a href="#"><?= htmlspecialchars($blog['category'] ?? 'General') ?></a>
                    </li>
                  </ul>
                </div>
                <h4 class="news-block_two-heading">
                  <a href="news-detail.php?id=<?= $blog['_id'] ?>">
                    <?= htmlspecialchars($blog['title']) ?>
                  </a>
                </h4>
                <div class="news-block_two-text">
                  <?= htmlspecialchars($blog['content'])?>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <div class="news-block_two-author">
                    <div class="news-block_two-author_image">
                      <img src="assets/images/resource/<?= htmlspecialchars($blog['writer'] ?? 'author-3.png') ?>" alt="" />
                    </div>
                    <span>Post By</span>
                    <?= htmlspecialchars($blog['writer'] ?? 'Unknown') ?>
                  </div>
                  <a class="news-block_two-more" href="login.php?id=<?= $blog['_id'] ?>"><span>more</span><i class="flaticon-arrow"></i></a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>

        </div>
      </div>
    </section>
    <!-- End News Three -->

    <!-- Optionally: <?php include 'footer.php'; ?> -->

  </div>
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>