<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Povium | Post your vision.</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php include_once $_SERVER['DOCUMENT_ROOT'].'/global-inclusion/global-css.php'; ?>
	<link rel="stylesheet" href="css/home.css">
	<?php include_once $_SERVER['DOCUMENT_ROOT'].'/global-inclusion/global-script.php'; ?>
</head>
<body>
	<?php include_once $_SERVER['DOCUMENT_ROOT'].'/global-inclusion/globalnav/globalnav.php'; ?>
	<main id="home-main">
		<section id="popular">
			<div class="post-container">
				<?php
				for ($i = 0; $i < 5; $i++) {
				?>
				<div class="post">
					<img class="img" src="assets/images/post-test-img-2.jpg" alt="">
					<div class="manifesto">
						<h1 class="title">Title placeholder ddddd</h1>
						<p class="contents">Contents placeholder. dddddddddd</p>
						<div class="creator-and-view">
							<a class="creator" href="">Creator</a>
							<span class="view-count">1234</span>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
		</section>
	</main>
</body>
</html>
