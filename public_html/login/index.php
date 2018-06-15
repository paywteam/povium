<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/global-inclusion/global-meta.php"; ?>
		<title>Povium | 로그인</title>
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/global-inclusion/global-css.php"; ?>
		<link rel="stylesheet" href="css/login.css">
	</head>
	<body>
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/global-inclusion/globalnav/globalnav.php"; ?>

		<main id="login-main">
			<div class="header">
				<img src="/assets/images/key.svg">
				<h1>로그인</h1>
			</div>
			<div class="auth-form">
				<div class="input-wrapper">
					<input class="input-basic" type="text">
					<span class="placeholder">아이디 또는 이메일</span>
				</div>
				<div class="input-wrapper">
					<input class="input-basic" type="password">
					<span class="placeholder">암호</span>
				</div>
				<button class="btn-aqua">로그인</button>
				<div class="auto-login">
					<input id="auto-chk" type="checkbox" class="checkbox-violet">
					<label for="auto-chk">자동로그인</label>
					<!-- <label for="auto-chk">Preserve login</label> -->
				</div>
			</div>
			<span class="pro">지금 <a href="/register">회원가입</a>하고 한 달 동안 무료로 이용해보세요!</span>
		</main>

		<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/global-inclusion/global-script.php"; ?>
	</body>
</html>
