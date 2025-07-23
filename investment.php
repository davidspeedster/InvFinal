<?php $page_title = "Investment";
include 'header.php'; ?>
<?php
// investment.php
// No authentication required
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>InvestHub</title>
  <!-- Stylesheets -->
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

    <!-- Main Header (as per your UI) -->
    <!-- ... copy from your uploaded file ... -->

    <!-- Services Four (categories) -->
    <section class="services-four">
      <div class="services-four_icon-one" style="background-image:url(assets/images/icons/icon-9.png)"></div>
      <div class="services-four_icon-two" style="background-image:url(assets/images/icons/icon-8.png)"></div>
      <div class="auto-container_two">
        <div class="sec-title centered">
          <div class="sec-title_title">Our Investment Propositions</div>
          <h2 class="sec-title_heading">We’re Offering a Platform <br> for Investment </h2>
        </div>
        <div class="row clearfix">

          <!-- Technology -->
          <div class="service-block_four col-lg-4 col-md-6 col-sm-12">
            <div class="service-block_four-inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
              <div class="service-block_four-icon">
                <img src="assets/images/icons/service-11.png" alt="" />
              </div>
              <h4 class="service-block_four-title">
                <a href="investment-listed.php?sector=technology">Technology</a>
              </h4>
              <div class="service-block_four-text">Invest in innovative tech businesses and startups.</div>
              <a class="service-block_four-more" href="investment-listed.php?sector=technology">
                <span>more</span><i class="flaticon-arrow"></i>
              </a>
            </div>
          </div>
          <!-- Agriculture -->
          <div class="service-block_four col-lg-4 col-md-6 col-sm-12">
            <div class="service-block_four-inner wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
              <div class="service-block_four-icon">
                <img src="assets/images/icons/service-12.png" alt="" />
              </div>
              <h4 class="service-block_four-title">
                <a href="investment-listed.php?sector=agriculture">Agriculture</a>
              </h4>
              <div class="service-block_four-text">Support agricultural projects with high growth potential.</div>
              <a class="service-block_four-more" href="investment-listed.php?sector=agriculture">
                <span>more</span><i class="flaticon-arrow"></i>
              </a>
            </div>
          </div>
          <!-- Construction -->
          <div class="service-block_four col-lg-4 col-md-6 col-sm-12">
            <div class="service-block_four-inner wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
              <div class="service-block_four-icon">
                <img src="assets/images/icons/service-13.png" alt="" />
              </div>
              <h4 class="service-block_four-title">
                <a href="investment-listed.php?sector=construction">Construction</a>
              </h4>
              <div class="service-block_four-text">Discover construction businesses seeking investment.</div>
              <a class="service-block_four-more" href="investment-listed.php?sector=construction">
                <span>more</span><i class="flaticon-arrow"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Services Four -->
    <?php include 'footer.php'; ?>
    <!-- (Counters, CTA, Footer, etc. — copy as in your original) -->

  </div>
  <!-- Scripts (unchanged) -->
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <!-- ... other scripts ... -->
</body>

</html>