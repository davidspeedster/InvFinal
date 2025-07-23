<?php
require 'db.php';
// Count of businesses (approved investments)
$num_companies = $db->investments->countDocuments(['status' => 'approved']);
// Count of active partners (unique entrepreneurs with at least 1 approved investment)
$partners = $db->investments->distinct('user_id', ['status' => 'approved']);
$num_partners = count($partners);
?>
<!DOCTYPE html>
<html>
<?php $page_title = "About Us";
include 'header.php'; ?>

<head>
	<meta charset="utf-8">
	<title>InvestHub</title>
	<!-- Stylesheets -->
	<link href="assets/css/bootstrap.css" rel="stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/responsive.css" rel="stylesheet">

	<!-- Color Switcher Mockup -->
	<link href="assets/css/color-switcher-design.css" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css2?family=Krona+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

	<link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
	<link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

	<!-- Responsive -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

</head>

<body>


	<!-- Page Title -->
	<section class="page-title">
		<div class="page-title_shapes" style="background-image:url(assets/images/background/page-title_bg.png)"></div>
		<div class="page-title_bg" style="background-image:url(assets/images/background/page-title_bg.png)"></div>
		<div class="page-title_icons" style="background-image:url(assets/images/background/page-title_icons.png)"></div>
		<div class="auto-container">
			<h2>About Us</h2>
			<ul class="bread-crumb clearfix">
				<li><a href="index.php">Home</a></li>
				<li>About</li>
			</ul>
		</div>
	</section>
	<!-- End Page Title -->

	<!-- Experiance One -->
	<section class="experiance-one">
		<div class="auto-container">
			<div class="inner-container">
				<div class="row clearfix">

					<!-- Left Column: Number of companies we work with -->
					<div class="left-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner-column">
							<div class="pattern-layer" style="background-image:url(assets/images/background/cta-pattern.png)"></div>
							<h3><?= $num_companies ?>+ Business</h3>
							<div class="text">We are working with <?= $num_companies ?>+ different businesses.</div>
						</div>
					</div>

					<!-- Right Column: Amount planned to raise (static for now) -->
					<div class="right-column col-lg-6 col-md-12 col-sm-12">
						<div class="inner-column">
							<h3>$2.4m+</h3>
							<div class="text">Planning to raise $2.4m+ for our customers</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>
	<!-- End Experiance One -->

	<!-- Counter One / Style Three -->
	<section class="counter-one style-three">
		<div class="auto-container">
			<div class="row clearfix">

				<!-- Counter Column: Active Partners -->
				<div class="counter-block_one col-xl-3 col-lg-6 col-md-6 col-sm-6">
					<div class="counter-block_one-inner wow flipInX" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="counter-block_one-counter">
							<span class="odometer" data-count="<?= $num_partners ?>"></span>+
						</div>
						<div class="counter-block_one-text">Active partner</div>
					</div>
				</div>

				<!-- Counter Column: (others static, but can also be dynamic) -->
				<div class="counter-block_one col-xl-3 col-lg-6 col-md-6 col-sm-6">
					<div class="counter-block_one-inner wow flipInX" data-wow-delay="150ms" data-wow-duration="1500ms">
						<div class="counter-block_one-counter">$<span class="odometer" data-count="4"></span>%</div>
						<div class="counter-block_one-text">High return rate</div>
					</div>
				</div>
				<div class="counter-block_one col-xl-3 col-lg-6 col-md-6 col-sm-6">
					<div class="counter-block_one-inner wow flipInX" data-wow-delay="450ms" data-wow-duration="1500ms">
						<div class="counter-block_one-counter"><span class="odometer" data-count="90"></span>%</div>
						<div class="counter-block_one-text">Satisfaction</div>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- End Counter One / Style Three -->

	<!-- Team One -->
	<section class="team-one">
		<div class="team-one_icon" style="background-image:url(assets/images/icons/icon-13.png)"></div>
		<div class="auto-container">
			<!-- Sec Title -->
			<div class="sec-title d-flex justify-content-between align-items-center flex-wrap">
				<div class="left-box">
					<div class="sec-title_title">Our Talent Team</div>
					<h2 class="sec-title_heading">Meet Our Professional <br> Talent Team</h2>
				</div>
				<div class="right-box">
					<a class="team-one_review" href="#">View All Team</a>
				</div>
			</div>

			<div class="row clearfix">

				<!-- Team Block One -->
				<div class="team-block_one col-lg-4 col-md-6 col-sm-12">
					<div class="team-block_one-inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="team-block_one-image">
							<img src="assets/images/resource/team 2.png" alt="" />
						</div>
						<div class="team-block_one-content">
							<h5 class="team-block_one-heading"><a href="team-detail.php">Firaol Befikadu</a></h5>
							<div class="team-block_one-designation">FrontEnd & BackEnd Dev </div>
							<div class="team-block_one-rating">
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
							</div>

							<div class="team-block_one-letter">F</div>

						</div>
					</div>
				</div>

				<!-- Team Block One -->
				<div class="team-block_one col-lg-4 col-md-6 col-sm-12">
					<div class="team-block_one-inner wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="team-block_one-image">
							<img src="assets/images/resource/team bald.png" alt="" />
						</div>
						<div class="team-block_one-content">
							<h5 class="team-block_one-heading"><a href="team-detail.php">Essay Biniam</a></h5>
							<div class="team-block_one-designation">ProjectManager</div>
							<div class="team-block_one-rating">
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
							</div>

							<div class="team-block_one-letter">E</div>

						</div>
					</div>
				</div>

				<!-- Team Block One -->
				<div class="team-block_one col-lg-4 col-md-6 col-sm-12">
					<div class="team-block_one-inner wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
						<div class="team-block_one-image">
							<img src="assets/images/resource/team.png" alt="" />
						</div>
						<div class="team-block_one-content">
							<h5 class="team-block_one-heading"><a href="team-detail.php">Kaleb Mengiste</a></h5>
							<div class="team-block_one-designation">UI/UX Designer</div>
							<div class="team-block_one-rating">
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
								<span class="fa fa-star"></span>
							</div>

							<div class="team-block_one-letter">k</div>

						</div>
					</div>
				</div>

			</div>

		</div>
	</section>
	<!-- End Team One -->

	<!-- Faq Two / Style Two -->
	<section class="faq-two style-two">
		<div class="faq-two_icon-one" style="background-image:url(assets/images/icons/icon-10.png)"></div>
		<div class="faq-two_icon-two" style="background-image:url(assets/images/icons/icon-11.png)"></div>
		<div class="auto-container_two">

			<!-- Sec Title -->
			<div class="sec-title centered">
				<div class="sec-title_title">We can help</div>
				<h2 class="sec-title_heading">Get the Answers <br> To Common Questions</h2>
			</div>

			<div class="inner-container">
				<div class="faq-two_icon-three" style="background-image:url(assets/images/icons/icon-12.png)"></div>
				<div class="row clearfix">

					<!-- Content Column -->
					<div class="faq-two_image-column col-lg-5 col-md-12 col-sm-12">
						<div class="faq-two_image-outer">

							<div class="faq-two_image">
								<img src="assets/images/resource/faq-1.png" alt="" />
							</div>
						</div>
					</div>

					<!-- Image Column -->
					<div class="faq-two_accordian-column col-lg-7 col-md-12 col-sm-12">
						<div class="faq-two_accordian-outer">

							<!-- Accordion Box / Style Two -->
							<ul class="accordion-box style-two">

								<!-- Block -->
								<li class="accordion block active-block">
									<div class="acc-btn active">
										<div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>How does the free trial work?
									</div>
									<div class="acc-content current">
										<div class="content">
											<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</div>
										</div>
									</div>
								</li>

								<!-- Block -->
								<li class="accordion block">
									<div class="acc-btn">
										<div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>What are the challenges of Financial Inclusion?
									</div>
									<div class="acc-content">
										<div class="content">
											<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</div>
										</div>
									</div>
								</li>

								<!-- Block -->
								<li class="accordion block">
									<div class="acc-btn">
										<div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>What is investment planning?
									</div>
									<div class="acc-content">
										<div class="content">
											<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</div>
										</div>
									</div>
								</li>

								<!-- Block -->
								<li class="accordion block">
									<div class="acc-btn">
										<div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>Who can I talk to with questions about my bill?
									</div>
									<div class="acc-content">
										<div class="content">
											<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</div>
										</div>
									</div>
								</li>

								<!-- Block -->
								<li class="accordion block">
									<div class="acc-btn">
										<div class="icon-outer"><span class="icon icon-plus fa fa-plus"></span> <span class="icon icon-minus fa fa-minus"></span></div>How can I pay my portion of my bill?
									</div>
									<div class="acc-content">
										<div class="content">
											<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</div>
										</div>
									</div>
								</li>

							</ul>

						</div>
					</div>

				</div>
			</div>
		</div>
	</section>
	<!-- End Faq One -->

	<!-- About Two -->
	<section class="about-two style-two">
		<div class="auto-container">
			<div class="row clearfix">

				<!-- Content Column -->
				<div class="about-two_content-column col-lg-7 col-md-12 col-sm-12">
					<div class="about-two_content-outer">
						<!-- Sec Title -->
						<div class="sec-title">
							<div class="sec-title_title">about us</div>
							<h2 class="sec-title_heading">We Design Exclusive Digital Products</h2>
							<div class="sec-title_text">There are many variations of passages of in free market to available, but the majority have suffered alteration words nor again is there anyone who loves or pursues or desirest</div>
						</div>

						<!-- About List -->
						<ul class="about-two_list">
							<li><i><img src="assets/images/icons/check.svg" alt="" /></i>We know that good design means good business.</li>
							<li><i><img src="assets/images/icons/check.svg" alt="" /></i>Check out Business Planning Options</li>
							<li><i><img src="assets/images/icons/check.svg" alt="" /></i>Balancing ethical responsibilities with commercial realities</li>
						</ul>

						<!-- Button Box -->
						<div class="about-two_button">
							<a href="contact.php" class="theme-btn btn-style-one">
								<span class="btn-wrap">
									<span class="text-one">About agency</span>
									<span class="text-two">About agency</span>
								</span>
							</a>
						</div>

					</div>
				</div>

				<!-- Image Column -->
				<div class="about-two_image-column col-lg-5 col-md-12 col-sm-12">
					<div class="about-two_image-outer">
						<div class="about-two_image">
							<img src="assets/images/resource/about-2.jpg" alt="" />
						</div>
						<div class="mission-box">
							<h3 class="mission-box_title">Our Mission</h3>
							<div class="mission-box_text">Tell us about yours and start executing it today with a trusted software development service provider.</div>
							<div class="mission-box-drop d-flex justify-content-between align-items-center flex-wrap">
								<a class="mission-box_line" href="#">Drop us a line</a>
								<a class="mission-box_arrow" href="#">
									<i class="flaticon-arrow-2"></i>
								</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- End About Two -->






	<!-- Footer Style -->
	<?php include 'footer.php'; ?>
</body>

</html>